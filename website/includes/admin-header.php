<?php
/**
 * Admin Panel Header
 */
require_once __DIR__ . '/../../includes/config.php';

// Check admin login (except for login page)
$currentAdminPage = basename($_SERVER['PHP_SELF'], '.php');
if ($currentAdminPage !== 'login' && !isAdminLoggedIn()) {
    redirect(SITE_URL . '/website/login.php');
}

$adminName = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Panel - Shree Anukul Steels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/website/assets/css/admin.css" rel="stylesheet">
</head>
<body>
<?php if ($currentAdminPage !== 'login'): ?>
    <!-- Admin Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h5 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Admin Panel</h5>
            <small class="text-white-50">Shree Anukul Steels</small>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo SITE_URL; ?>/website/index.php" class="sidebar-link <?php echo $currentAdminPage === 'index' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="<?php echo SITE_URL; ?>/website/leads.php" class="sidebar-link <?php echo $currentAdminPage === 'leads' ? 'active' : ''; ?>">
                <i class="bi bi-people-fill"></i> Leads
            </a>
            <a href="<?php echo SITE_URL; ?>/website/products-manage.php" class="sidebar-link <?php echo $currentAdminPage === 'products-manage' ? 'active' : ''; ?>">
                <i class="bi bi-box-fill"></i> Products
            </a>
            <a href="<?php echo SITE_URL; ?>/website/categories.php" class="sidebar-link <?php echo $currentAdminPage === 'categories' ? 'active' : ''; ?>">
                <i class="bi bi-tags-fill"></i> Categories
            </a>
            <a href="<?php echo SITE_URL; ?>/website/projects-manage.php" class="sidebar-link <?php echo $currentAdminPage === 'projects-manage' ? 'active' : ''; ?>">
                <i class="bi bi-building-fill"></i> Projects
            </a>
            <a href="<?php echo SITE_URL; ?>/website/testimonials.php" class="sidebar-link <?php echo $currentAdminPage === 'testimonials' ? 'active' : ''; ?>">
                <i class="bi bi-chat-quote-fill"></i> Testimonials
            </a>
            <a href="<?php echo SITE_URL; ?>/website/quotations.php" class="sidebar-link <?php echo $currentAdminPage === 'quotations' ? 'active' : ''; ?>">
                <i class="bi bi-file-earmark-text-fill"></i> Quotations
            </a>
            <a href="<?php echo SITE_URL; ?>/website/enquiries.php" class="sidebar-link <?php echo $currentAdminPage === 'enquiries' ? 'active' : ''; ?>">
                <i class="bi bi-envelope-fill"></i> Enquiries
            </a>
            <a href="<?php echo SITE_URL; ?>/website/seo-settings.php" class="sidebar-link <?php echo $currentAdminPage === 'seo-settings' ? 'active' : ''; ?>">
                <i class="bi bi-search"></i> SEO Settings
            </a>
            <a href="<?php echo SITE_URL; ?>/website/settings.php" class="sidebar-link <?php echo $currentAdminPage === 'settings' ? 'active' : ''; ?>">
                <i class="bi bi-gear-fill"></i> Settings
            </a>
            <hr class="border-secondary mx-3">
            <a href="<?php echo SITE_URL; ?>/index.php" class="sidebar-link" target="_blank">
                <i class="bi bi-globe"></i> View Website
            </a>
            <a href="<?php echo SITE_URL; ?>/website/logout.php" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="admin-main" id="adminMain">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <?php
                // Check for pending reminders
                $reminderCount = 0;
                try {
                    $db = getDB();
                    $reminderStmt = $db->query("SELECT COUNT(*) as cnt FROM lead_reminders WHERE is_completed = 0 AND reminder_date <= date('now')");
                    $reminderResult = $reminderStmt->fetch();
                    $reminderCount = $reminderResult['cnt'] ?? 0;
                } catch (Exception $e) {}
                ?>
                <?php if ($reminderCount > 0): ?>
                <a href="<?php echo SITE_URL; ?>/website/leads.php?reminders=1" class="btn btn-sm btn-warning me-3">
                    <i class="bi bi-bell-fill"></i> <?php echo $reminderCount; ?> Reminder<?php echo $reminderCount > 1 ? 's' : ''; ?>
                </a>
                <?php endif; ?>
                <span class="text-muted me-2"><i class="bi bi-person-circle me-1"></i> <?php echo sanitize($adminName); ?></span>
            </div>
        </div>
        <div class="admin-content">
<?php endif; ?>
