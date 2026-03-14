<?php
/**
 * Admin - Category Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = sanitize($_POST['name']);
        $slug = generateSlug($name);
        $description = sanitize($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        try {
            $stmt = $db->prepare('INSERT INTO product_categories (name, slug, description, sort_order) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $slug, $description, $sortOrder]);
            $message = 'Category added successfully.';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error adding category.';
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $name = sanitize($_POST['name']);
        $slug = generateSlug($name);
        $description = sanitize($_POST['description'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $status = (int)$_POST['status'];
        $stmt = $db->prepare('UPDATE product_categories SET name=?, slug=?, description=?, sort_order=?, status=? WHERE id=?');
        $stmt->execute([$name, $slug, $description, $sortOrder, $status, $id]);
        $message = 'Category updated successfully.';
        $messageType = 'success';
    }
    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        $db->prepare('DELETE FROM product_categories WHERE id = ?')->execute([$id]);
        $message = 'Category deleted successfully.';
        $messageType = 'success';
    }
}

$categories = $db->query('SELECT pc.*, (SELECT COUNT(*) FROM products WHERE category_id = pc.id) as product_count FROM product_categories pc ORDER BY pc.sort_order ASC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Category Management</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal"><i class="bi bi-plus-lg me-1"></i>Add Category</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
    <?php echo sanitize($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?php echo $cat['id']; ?></td>
                <td><strong><?php echo sanitize($cat['name']); ?></strong></td>
                <td><code><?php echo sanitize($cat['slug']); ?></code></td>
                <td><?php echo $cat['product_count']; ?></td>
                <td><span class="badge <?php echo $cat['status'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $cat['status'] ? 'Active' : 'Inactive'; ?></span></td>
                <td><?php echo $cat['sort_order']; ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCat<?php echo $cat['id']; ?>"><i class="bi bi-pencil"></i></button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this category?"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <!-- Edit Modal -->
            <div class="modal fade" id="editCat<?php echo $cat['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" name="name" value="<?php echo sanitize($cat['name']); ?>" required></div>
                                <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"><?php echo sanitize($cat['description'] ?? ''); ?></textarea></div>
                                <div class="row g-3">
                                    <div class="col-6"><label class="form-label">Status</label><select class="form-select" name="status"><option value="1" <?php echo $cat['status'] ? 'selected' : ''; ?>>Active</option><option value="0" <?php echo !$cat['status'] ? 'selected' : ''; ?>>Inactive</option></select></div>
                                    <div class="col-6"><label class="form-label">Sort Order</label><input type="number" class="form-control" name="sort_order" value="<?php echo $cat['sort_order']; ?>"></div>
                                </div>
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5 class="modal-title">Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" required placeholder="e.g., TMT Bars"></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" class="form-control" name="sort_order" value="0"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add Category</button></div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
