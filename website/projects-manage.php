<?php
/**
 * Admin - Project Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = sanitize($_POST['name']);
        $categoryId = (int)$_POST['category_id'];
        $slug = generateSlug($name);
        $location = sanitize($_POST['location']);
        $description = sanitize($_POST['description']);
        $steelQuantity = sanitize($_POST['steel_quantity']);
        $completionYear = sanitize($_POST['completion_year']);
        $status = (int)$_POST['status'];

        $imagePath = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $newName = 'projects/' . $slug . '-' . time() . '.' . $ext;
                $uploadDir = UPLOAD_PATH . 'projects/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $newName)) {
                    $imagePath = $newName;
                }
            }
        }

        try {
            if ($_POST['action'] === 'add') {
                $stmt = $db->prepare('INSERT INTO projects (category_id, name, slug, location, description, steel_quantity, completion_year, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$categoryId, $name, $slug, $location, $description, $steelQuantity, $completionYear, $imagePath, $status]);
                $message = 'Project added successfully.';
            } else {
                $stmt = $db->prepare('UPDATE projects SET category_id=?, name=?, slug=?, location=?, description=?, steel_quantity=?, completion_year=?, image=?, status=?, updated_at=CURRENT_TIMESTAMP WHERE id=?');
                $stmt->execute([$categoryId, $name, $slug, $location, $description, $steelQuantity, $completionYear, $imagePath, $status, $id]);
                $message = 'Project updated successfully.';
            }
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'delete') {
        $db->prepare('DELETE FROM projects WHERE id = ?')->execute([(int)$_POST['id']]);
        $message = 'Project deleted successfully.';
        $messageType = 'success';
    }
}

$editProject = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editProject = $stmt->fetch();
}

$projectCategories = $db->query('SELECT * FROM project_categories ORDER BY name ASC')->fetchAll();
$projects = $db->query('SELECT p.*, pc.name as category_name FROM projects p LEFT JOIN project_categories pc ON p.category_id = pc.id ORDER BY p.created_at DESC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-building-fill me-2"></i>Project Management</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="document.querySelector('#projectModal form').reset();"><i class="bi bi-plus-lg me-1"></i>Add Project</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Location</th><th>Category</th><th>Steel Qty</th><th>Year</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($projects as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td>
                        <?php if (!empty($p['image']) && file_exists(UPLOAD_PATH . $p['image'])): ?>
                        <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($p['image']); ?>" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        <?php else: ?>
                        <div style="width:50px;height:50px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-image text-muted"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo sanitize($p['name']); ?></strong></td>
                    <td><?php echo sanitize($p['location']); ?></td>
                    <td><?php echo sanitize($p['category_name'] ?? '-'); ?></td>
                    <td><?php echo sanitize($p['steel_quantity']); ?></td>
                    <td><?php echo sanitize($p['completion_year']); ?></td>
                    <td><span class="badge <?php echo $p['status'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $p['status'] ? 'Active' : 'Inactive'; ?></span></td>
                    <td>
                        <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" style="display:inline;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this project?"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Project Form Modal -->
<div class="modal fade <?php echo $editProject ? 'show' : ''; ?>" id="projectModal" tabindex="-1" <?php echo $editProject ? 'style="display:block;" aria-modal="true" role="dialog"' : ''; ?>>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header"><h5 class="modal-title"><?php echo $editProject ? 'Edit Project' : 'Add Project'; ?></h5><a href="<?php echo SITE_URL; ?>/website/projects-manage.php" class="btn-close"></a></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?php echo $editProject ? 'edit' : 'add'; ?>">
                    <?php if ($editProject): ?><input type="hidden" name="id" value="<?php echo $editProject['id']; ?>"><input type="hidden" name="existing_image" value="<?php echo sanitize($editProject['image'] ?? ''); ?>"><?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label">Project Name *</label><input type="text" class="form-control" name="name" required value="<?php echo $editProject ? sanitize($editProject['name']) : ''; ?>"></div>
                        <div class="col-md-4"><label class="form-label">Category</label><select class="form-select" name="category_id"><option value="0">-- Select --</option><?php foreach ($projectCategories as $cat): ?><option value="<?php echo $cat['id']; ?>" <?php echo ($editProject && $editProject['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo sanitize($cat['name']); ?></option><?php endforeach; ?></select></div>
                        <div class="col-md-6"><label class="form-label">Location</label><input type="text" class="form-control" name="location" value="<?php echo $editProject ? sanitize($editProject['location']) : ''; ?>"></div>
                        <div class="col-md-3"><label class="form-label">Steel Quantity</label><input type="text" class="form-control" name="steel_quantity" value="<?php echo $editProject ? sanitize($editProject['steel_quantity']) : ''; ?>" placeholder="e.g., 2500 Tons"></div>
                        <div class="col-md-3"><label class="form-label">Completion Year</label><input type="text" class="form-control" name="completion_year" value="<?php echo $editProject ? sanitize($editProject['completion_year']) : ''; ?>" placeholder="e.g., 2024"></div>
                        <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?php echo $editProject ? sanitize($editProject['description']) : ''; ?></textarea></div>
                        <div class="col-md-6"><label class="form-label">Project Image</label><input type="file" class="form-control" name="image" accept="image/*"><?php if ($editProject && !empty($editProject['image'])): ?><small class="text-muted">Current: <?php echo sanitize($editProject['image']); ?></small><?php endif; ?></div>
                        <div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status"><option value="1" <?php echo ($editProject && $editProject['status'] == 1) || !$editProject ? 'selected' : ''; ?>>Active</option><option value="0" <?php echo ($editProject && $editProject['status'] == 0) ? 'selected' : ''; ?>>Inactive</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><a href="<?php echo SITE_URL; ?>/website/projects-manage.php" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><?php echo $editProject ? 'Update' : 'Add Project'; ?></button></div>
            </form>
        </div>
    </div>
</div>
<?php if ($editProject): ?><div class="modal-backdrop fade show"></div><?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
