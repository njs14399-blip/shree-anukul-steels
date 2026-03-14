<?php
/**
 * Admin - Website Settings
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => sanitize($_POST['site_name'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'email' => sanitize($_POST['email'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
        'whatsapp' => sanitize($_POST['whatsapp'] ?? ''),
        'facebook' => sanitize($_POST['facebook'] ?? ''),
        'twitter' => sanitize($_POST['twitter'] ?? ''),
        'linkedin' => sanitize($_POST['linkedin'] ?? ''),
        'instagram' => sanitize($_POST['instagram'] ?? ''),
        'youtube' => sanitize($_POST['youtube'] ?? ''),
        'google_analytics' => sanitize($_POST['google_analytics'] ?? ''),
        'google_search_console' => sanitize($_POST['google_search_console'] ?? ''),
        'recaptcha_site_key' => sanitize($_POST['recaptcha_site_key'] ?? ''),
        'recaptcha_secret_key' => sanitize($_POST['recaptcha_secret_key'] ?? '')
    ];

    try {
        $stmt = $db->prepare('UPDATE website_settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?');
        foreach ($settings as $key => $value) {
            $stmt->execute([$value, $key]);
        }
        $message = 'Website settings updated successfully.';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error updating settings.';
        $messageType = 'danger';
    }
}

// Load current settings
$settingsData = [];
$rows = $db->query('SELECT setting_key, setting_value FROM website_settings')->fetchAll();
foreach ($rows as $row) {
    $settingsData[$row['setting_key']] = $row['setting_value'];
}
?>

<h4 class="mb-4"><i class="bi bi-gear-fill me-2"></i>Website Settings</h4>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST">
    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>General Information</div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Site Name</label><input type="text" class="form-control" name="site_name" value="<?php echo sanitize($settingsData['site_name'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Phone Number</label><input type="text" class="form-control" name="phone" value="<?php echo sanitize($settingsData['phone'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Email Address</label><input type="email" class="form-control" name="email" value="<?php echo sanitize($settingsData['email'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">WhatsApp Number (without +)</label><input type="text" class="form-control" name="whatsapp" value="<?php echo sanitize($settingsData['whatsapp'] ?? ''); ?>" placeholder="918981040333"></div>
                    <div class="mb-3"><label class="form-label">Office Address</label><textarea class="form-control" name="address" rows="3"><?php echo sanitize($settingsData['address'] ?? ''); ?></textarea></div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-share me-2"></i>Social Media Links</div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label"><i class="bi bi-facebook text-primary me-1"></i>Facebook URL</label><input type="url" class="form-control" name="facebook" value="<?php echo sanitize($settingsData['facebook'] ?? ''); ?>" placeholder="https://facebook.com/..."></div>
                    <div class="mb-3"><label class="form-label"><i class="bi bi-twitter-x me-1"></i>Twitter / X URL</label><input type="url" class="form-control" name="twitter" value="<?php echo sanitize($settingsData['twitter'] ?? ''); ?>" placeholder="https://x.com/..."></div>
                    <div class="mb-3"><label class="form-label"><i class="bi bi-linkedin text-primary me-1"></i>LinkedIn URL</label><input type="url" class="form-control" name="linkedin" value="<?php echo sanitize($settingsData['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/..."></div>
                    <div class="mb-3"><label class="form-label"><i class="bi bi-instagram text-danger me-1"></i>Instagram URL</label><input type="url" class="form-control" name="instagram" value="<?php echo sanitize($settingsData['instagram'] ?? ''); ?>" placeholder="https://instagram.com/..."></div>
                    <div class="mb-3"><label class="form-label"><i class="bi bi-youtube text-danger me-1"></i>YouTube URL</label><input type="url" class="form-control" name="youtube" value="<?php echo sanitize($settingsData['youtube'] ?? ''); ?>" placeholder="https://youtube.com/..."></div>
                </div>
            </div>
        </div>

        <!-- Analytics & Security -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-graph-up me-2"></i>Analytics</div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Google Analytics Tracking ID</label><input type="text" class="form-control" name="google_analytics" value="<?php echo sanitize($settingsData['google_analytics'] ?? ''); ?>" placeholder="G-XXXXXXXXXX"></div>
                    <div class="mb-3"><label class="form-label">Google Search Console Verification</label><input type="text" class="form-control" name="google_search_console" value="<?php echo sanitize($settingsData['google_search_console'] ?? ''); ?>" placeholder="Verification meta tag content"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-shield-check me-2"></i>Google reCAPTCHA</div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">reCAPTCHA Site Key</label><input type="text" class="form-control" name="recaptcha_site_key" value="<?php echo sanitize($settingsData['recaptcha_site_key'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">reCAPTCHA Secret Key</label><input type="text" class="form-control" name="recaptcha_secret_key" value="<?php echo sanitize($settingsData['recaptcha_secret_key'] ?? ''); ?>"></div>
                    <small class="text-muted">Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA</a></small>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save me-2"></i>Save All Settings</button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
