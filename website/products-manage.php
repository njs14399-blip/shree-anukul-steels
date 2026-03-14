<?php
/**
 * Admin - Product Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = sanitize($_POST['name']);
        $categoryId = (int)$_POST['category_id'];
        $slug = generateSlug($name);
        $shortDesc = sanitize($_POST['short_description']);
        $description = sanitize($_POST['description']);
        $specifications = sanitize($_POST['specifications']);
        $features = sanitize($_POST['features']);
        $status = (int)$_POST['status'];
        $sortOrder = (int)$_POST['sort_order'];

        // Handle image upload
        $imagePath = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $newName = 'products/' . $slug . '-' . time() . '.' . $ext;
                $uploadDir = UPLOAD_PATH . 'products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $newName)) {
                    $imagePath = $newName;
                }
            }
        }

        try {
            if ($_POST['action'] === 'add') {
                $stmt = $db->prepare('INSERT INTO products (category_id, name, slug, short_description, description, specifications, features, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$categoryId, $name, $slug, $shortDesc, $description, $specifications, $features, $imagePath, $status, $sortOrder]);
                $message = 'Product added successfully.';
            } else {
                $stmt = $db->prepare('UPDATE products SET category_id=?, name=?, slug=?, short_description=?, description=?, specifications=?, features=?, image=?, status=?, sort_order=?, updated_at=CURRENT_TIMESTAMP WHERE id=?');
                $stmt->execute([$categoryId, $name, $slug, $shortDesc, $description, $specifications, $features, $imagePath, $status, $sortOrder, $id]);
                $message = 'Product updated successfully.';
            }
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }

    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        $db->prepare('DELETE FROM product_images WHERE product_id = ?')->execute([$id]);
        $db->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
        $message = 'Product deleted successfully.';
        $messageType = 'success';
    }

    // Handle additional image upload
    if ($_POST['action'] === 'add_image') {
        $productId = (int)$_POST['product_id'];
        if (isset($_FILES['additional_image']) && $_FILES['additional_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['additional_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($ext, $allowed)) {
                $newName = 'products/extra-' . $productId . '-' . time() . '.' . $ext;
                $uploadDir = UPLOAD_PATH . 'products/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['additional_image']['tmp_name'], UPLOAD_PATH . $newName)) {
                    $stmt = $db->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?, ?)');
                    $stmt->execute([$productId, $newName]);
                    $message = 'Additional image uploaded.';
                    $messageType = 'success';
                }
            }
        }
    }
}

// Get editing product
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

$categories = $db->query('SELECT * FROM product_categories WHERE status = 1 ORDER BY sort_order ASC')->fetchAll();
$products = $db->query('SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id ORDER BY p.sort_order ASC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-box-fill me-2"></i>Product Management</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()"><i class="bi bi-plus-lg me-1"></i>Add Product</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
    <?php echo sanitize($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Products Table -->
<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($products as $p): ?>
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
                    <td><?php echo sanitize($p['category_name'] ?? '-'); ?></td>
                    <td><span class="badge <?php echo $p['status'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $p['status'] ? 'Active' : 'Inactive'; ?></span></td>
                    <td><?php echo $p['sort_order']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this product?"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Product Form Modal -->
<div class="modal fade <?php echo $editProduct ? 'show' : ''; ?>" id="productModal" tabindex="-1" <?php echo $editProduct ? 'style="display:block;" aria-modal="true" role="dialog"' : ''; ?>>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editProduct ? 'Edit Product' : 'Add Product'; ?></h5>
                    <a href="<?php echo SITE_URL; ?>/website/products-manage.php" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit' : 'add'; ?>">
                    <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                    <input type="hidden" name="existing_image" value="<?php echo sanitize($editProduct['image'] ?? ''); ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Product Name *</label>
                            <input type="text" class="form-control" name="name" required value="<?php echo $editProduct ? sanitize($editProduct['name']) : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id">
                                <option value="0">-- Select --</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo sanitize($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <input type="text" class="form-control" name="short_description" value="<?php echo $editProduct ? sanitize($editProduct['short_description']) : ''; ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description</label>
                            <textarea class="form-control" name="description" rows="4"><?php echo $editProduct ? sanitize($editProduct['description']) : ''; ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Specifications (pipe | separated, key:value)</label>
                            <textarea class="form-control" name="specifications" rows="3" placeholder="Grade: Fe-500D|Diameter: 8mm to 32mm|Length: 12m"><?php echo $editProduct ? sanitize($editProduct['specifications']) : ''; ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Features (pipe | separated)</label>
                            <textarea class="form-control" name="features" rows="3" placeholder="High tensile strength|Corrosion resistant"><?php echo $editProduct ? sanitize($editProduct['features']) : ''; ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <?php if ($editProduct && !empty($editProduct['image'])): ?>
                            <small class="text-muted">Current: <?php echo sanitize($editProduct['image']); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="1" <?php echo ($editProduct && $editProduct['status'] == 1) || !$editProduct ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo ($editProduct && $editProduct['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="<?php echo $editProduct ? $editProduct['sort_order'] : '0'; ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?php echo SITE_URL; ?>/website/products-manage.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><?php echo $editProduct ? 'Update Product' : 'Add Product'; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if ($editProduct): ?><div class="modal-backdrop fade show"></div><?php endif; ?>

<script>
function resetForm() {
    document.querySelector('#productModal form').reset();
    document.querySelector('#productModal input[name="action"]').value = 'add';
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
