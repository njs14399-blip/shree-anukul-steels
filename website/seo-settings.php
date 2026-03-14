<?php
/**
 * Admin - SEO Settings
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pageName = sanitize($_POST['page_name']);
    $metaTitle = sanitize($_POST['meta_title']);
    $metaDescription = sanitize($_POST['meta_description']);
    $metaKeywords = sanitize($_POST['meta_keywords']);
    $ogTitle = sanitize($_POST['og_title']);
    $ogDescription = sanitize($_POST['og_description']);

    try {
        $stmt = $db->prepare('UPDATE seo_settings SET meta_title=?, meta_description=?, meta_keywords=?, og_title=?, og_description=?, updated_at=CURRENT_TIMESTAMP WHERE page_name=?');
        $stmt->execute([$metaTitle, $metaDescription, $metaKeywords, $ogTitle, $ogDescription, $pageName]);
        $message = 'SEO settings for "' . $pageName . '" updated successfully.';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error updating SEO settings.';
        $messageType = 'danger';
    }
}

$pages = $db->query('SELECT * FROM seo_settings ORDER BY id ASC')->fetchAll();
$editPage = isset($_GET['page']) ? sanitize($_GET['page']) : '';
$editData = null;
if (!empty($editPage)) {
    foreach ($pages as $p) {
        if ($p['page_name'] === $editPage) {
            $editData = $p;
            break;
        }
    }
}
?>

<h4 class="mb-4"><i class="bi bi-search me-2"></i>SEO Settings</h4>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">
    <!-- Page List -->
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">Pages</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($pages as $p): ?>
                    <a href="?page=<?php echo urlencode($p['page_name']); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo $editPage === $p['page_name'] ? 'active' : ''; ?>">
                        <span><i class="bi bi-file-earmark-text me-2"></i><?php echo ucfirst(sanitize($p['page_name'])); ?></span>
                        <?php if (!empty($p['meta_title'])): ?><i class="bi bi-check-circle-fill text-success"></i><?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Form -->
    <div class="col-lg-8">
        <?php if ($editData): ?>
        <div class="admin-card">
            <div class="card-header">SEO Settings for: <strong><?php echo ucfirst(sanitize($editData['page_name'])); ?></strong></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="page_name" value="<?php echo sanitize($editData['page_name']); ?>">

                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" value="<?php echo sanitize($editData['meta_title']); ?>" maxlength="70">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea class="form-control" name="meta_description" rows="3" maxlength="200"><?php echo sanitize($editData['meta_description']); ?></textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Meta Keywords</label>
                        <textarea class="form-control" name="meta_keywords" rows="2"><?php echo sanitize($editData['meta_keywords']); ?></textarea>
                        <small class="text-muted">Comma-separated keywords</small>
                    </div>

                    <hr>
                    <h6>Open Graph / Social Media</h6>

                    <div class="mb-3">
                        <label class="form-label">OG Title</label>
                        <input type="text" class="form-control" name="og_title" value="<?php echo sanitize($editData['og_title'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">OG Description</label>
                        <textarea class="form-control" name="og_description" rows="2"><?php echo sanitize($editData['og_description'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save SEO Settings</button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="admin-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-search" style="font-size:3rem;color:#d1d5db;"></i>
                <p class="text-muted mt-3">Select a page from the list to edit its SEO settings.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
