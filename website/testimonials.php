<?php
/**
 * Admin - Testimonial Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $customerName = sanitize($_POST['customer_name']);
        $companyName = sanitize($_POST['company_name']);
        $rating = (int)$_POST['rating'];
        $reviewText = sanitize($_POST['review_text']);
        $status = (int)$_POST['status'];
        $sortOrder = (int)$_POST['sort_order'];

        $imagePath = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed)) {
                $newName = 'testimonials/testimonial-' . time() . '.' . $ext;
                $uploadDir = UPLOAD_PATH . 'testimonials/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $newName)) {
                    $imagePath = $newName;
                }
            }
        }

        try {
            if ($_POST['action'] === 'add') {
                $stmt = $db->prepare('INSERT INTO testimonials (customer_name, company_name, rating, review_text, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$customerName, $companyName, $rating, $reviewText, $imagePath, $status, $sortOrder]);
                $message = 'Testimonial added successfully.';
            } else {
                $stmt = $db->prepare('UPDATE testimonials SET customer_name=?, company_name=?, rating=?, review_text=?, image=?, status=?, sort_order=? WHERE id=?');
                $stmt->execute([$customerName, $companyName, $rating, $reviewText, $imagePath, $status, $sortOrder, $id]);
                $message = 'Testimonial updated successfully.';
            }
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'delete') {
        $db->prepare('DELETE FROM testimonials WHERE id = ?')->execute([(int)$_POST['id']]);
        $message = 'Testimonial deleted.';
        $messageType = 'success';
    }
}

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM testimonials WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$testimonials = $db->query('SELECT * FROM testimonials ORDER BY sort_order ASC, created_at DESC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-chat-quote-fill me-2"></i>Testimonial Management</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#testimonialModal"><i class="bi bi-plus-lg me-1"></i>Add Testimonial</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead><tr><th>ID</th><th>Customer</th><th>Company</th><th>Rating</th><th>Review</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($testimonials as $t): ?>
            <tr>
                <td><?php echo $t['id']; ?></td>
                <td><strong><?php echo sanitize($t['customer_name']); ?></strong></td>
                <td><?php echo sanitize($t['company_name']); ?></td>
                <td><?php for ($i = 0; $i < $t['rating']; $i++) echo '<i class="bi bi-star-fill text-warning"></i>'; ?></td>
                <td><small><?php echo sanitize(substr($t['review_text'], 0, 80)); ?>...</small></td>
                <td><span class="badge <?php echo $t['status'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $t['status'] ? 'Active' : 'Inactive'; ?></span></td>
                <td>
                    <a href="?edit=<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <form method="POST" style="display:inline;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete?"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Testimonial Modal -->
<div class="modal fade <?php echo $editItem ? 'show' : ''; ?>" id="testimonialModal" tabindex="-1" <?php echo $editItem ? 'style="display:block;" aria-modal="true"' : ''; ?>>
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header"><h5 class="modal-title"><?php echo $editItem ? 'Edit' : 'Add'; ?> Testimonial</h5><a href="<?php echo SITE_URL; ?>/website/testimonials.php" class="btn-close"></a></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?php echo $editItem ? 'edit' : 'add'; ?>">
                    <?php if ($editItem): ?><input type="hidden" name="id" value="<?php echo $editItem['id']; ?>"><input type="hidden" name="existing_image" value="<?php echo sanitize($editItem['image'] ?? ''); ?>"><?php endif; ?>
                    <div class="mb-3"><label class="form-label">Customer Name *</label><input type="text" class="form-control" name="customer_name" required value="<?php echo $editItem ? sanitize($editItem['customer_name']) : ''; ?>"></div>
                    <div class="mb-3"><label class="form-label">Company Name</label><input type="text" class="form-control" name="company_name" value="<?php echo $editItem ? sanitize($editItem['company_name']) : ''; ?>"></div>
                    <div class="mb-3"><label class="form-label">Rating</label><select class="form-select" name="rating"><option value="5" <?php echo ($editItem && $editItem['rating'] == 5) || !$editItem ? 'selected' : ''; ?>>5 Stars</option><option value="4" <?php echo ($editItem && $editItem['rating'] == 4) ? 'selected' : ''; ?>>4 Stars</option><option value="3" <?php echo ($editItem && $editItem['rating'] == 3) ? 'selected' : ''; ?>>3 Stars</option></select></div>
                    <div class="mb-3"><label class="form-label">Review Text *</label><textarea class="form-control" name="review_text" rows="4" required><?php echo $editItem ? sanitize($editItem['review_text']) : ''; ?></textarea></div>
                    <div class="mb-3"><label class="form-label">Photo</label><input type="file" class="form-control" name="image" accept="image/*"></div>
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label">Status</label><select class="form-select" name="status"><option value="1" <?php echo ($editItem && $editItem['status'] == 1) || !$editItem ? 'selected' : ''; ?>>Active</option><option value="0" <?php echo ($editItem && $editItem['status'] == 0) ? 'selected' : ''; ?>>Inactive</option></select></div>
                        <div class="col-6"><label class="form-label">Sort Order</label><input type="number" class="form-control" name="sort_order" value="<?php echo $editItem ? $editItem['sort_order'] : '0'; ?>"></div>
                    </div>
                </div>
                <div class="modal-footer"><a href="<?php echo SITE_URL; ?>/website/testimonials.php" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><?php echo $editItem ? 'Update' : 'Add'; ?></button></div>
            </form>
        </div>
    </div>
</div>
<?php if ($editItem): ?><div class="modal-backdrop fade show"></div><?php endif; ?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
