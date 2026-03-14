<?php
/**
 * Admin Dashboard
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();

// Dashboard statistics
$totalLeads = $db->query('SELECT COUNT(*) as cnt FROM leads')->fetch()['cnt'];
$monthlyLeads = $db->query("SELECT COUNT(*) as cnt FROM leads WHERE created_at >= date('now', 'start of month')")->fetch()['cnt'];
$totalProducts = $db->query('SELECT COUNT(*) as cnt FROM products')->fetch()['cnt'];
$totalQuotations = $db->query('SELECT COUNT(*) as cnt FROM quotations')->fetch()['cnt'];
$totalEnquiries = $db->query('SELECT COUNT(*) as cnt FROM enquiries')->fetch()['cnt'];
$totalProjects = $db->query('SELECT COUNT(*) as cnt FROM projects')->fetch()['cnt'];

// Recent leads
$recentLeads = $db->query('SELECT * FROM leads ORDER BY created_at DESC LIMIT 5')->fetchAll();

// Pending reminders
$pendingReminders = $db->query("SELECT lr.*, l.customer_name, l.phone FROM lead_reminders lr JOIN leads l ON lr.lead_id = l.id WHERE lr.is_completed = 0 AND lr.reminder_date <= date('now') ORDER BY lr.reminder_date ASC LIMIT 5")->fetchAll();

// Recent quote requests
$recentQuoteRequests = $db->query('SELECT * FROM quote_requests ORDER BY created_at DESC LIMIT 5')->fetchAll();
?>

<h4 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#1a56db;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $totalLeads; ?></h3>
                    <p>Total Leads</p>
                </div>
                <div class="stat-icon" style="background:#dbeafe;color:#1a56db;"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#10b981;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $monthlyLeads; ?></h3>
                    <p>Monthly Leads</p>
                </div>
                <div class="stat-icon" style="background:#d1fae5;color:#10b981;"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#f59e0b;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $totalProducts; ?></h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-icon" style="background:#fef3c7;color:#f59e0b;"><i class="bi bi-box-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#8b5cf6;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $totalQuotations; ?></h3>
                    <p>Total Quotations</p>
                </div>
                <div class="stat-icon" style="background:#ede9fe;color:#8b5cf6;"><i class="bi bi-file-earmark-text-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#ef4444;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $totalEnquiries; ?></h3>
                    <p>Website Enquiries</p>
                </div>
                <div class="stat-icon" style="background:#fee2e2;color:#ef4444;"><i class="bi bi-envelope-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card" style="border-left-color:#06b6d4;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3><?php echo $totalProjects; ?></h3>
                    <p>Total Projects</p>
                </div>
                <div class="stat-icon" style="background:#cffafe;color:#06b6d4;"><i class="bi bi-building-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Reminders -->
    <?php if (!empty($pendingReminders)): ?>
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bell-fill text-warning me-2"></i>Pending Reminders</span>
                <a href="<?php echo SITE_URL; ?>/website/leads.php?reminders=1" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Customer</th><th>Phone</th><th>Reminder Date</th><th>Note</th></tr></thead>
                    <tbody>
                    <?php foreach ($pendingReminders as $rem): ?>
                    <tr>
                        <td><?php echo sanitize($rem['customer_name']); ?></td>
                        <td><?php echo sanitize($rem['phone']); ?></td>
                        <td><?php echo date('d M Y', strtotime($rem['reminder_date'])); ?></td>
                        <td><?php echo sanitize($rem['reminder_note'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Leads -->
    <div class="col-lg-<?php echo empty($pendingReminders) ? '12' : '6'; ?>">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>Recent Leads</span>
                <a href="<?php echo SITE_URL; ?>/website/leads.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Name</th><th>Phone</th><th>Source</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentLeads as $lead): ?>
                    <tr>
                        <td><?php echo sanitize($lead['customer_name']); ?></td>
                        <td><?php echo sanitize($lead['phone']); ?></td>
                        <td><small><?php echo sanitize($lead['lead_source']); ?></small></td>
                        <td><?php echo getStatusBadge($lead['status']); ?></td>
                        <td><small><?php echo date('d M', strtotime($lead['created_at'])); ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Quote Requests -->
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-dots-fill text-success me-2"></i>Recent Quote Requests</span>
            </div>
            <div class="card-body p-0">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Name</th><th>Phone</th><th>Product</th><th>Quantity</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php if (empty($recentQuoteRequests)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">No quote requests yet.</td></tr>
                    <?php else: ?>
                    <?php foreach ($recentQuoteRequests as $qr): ?>
                    <tr>
                        <td><?php echo sanitize($qr['name']); ?></td>
                        <td><?php echo sanitize($qr['phone']); ?></td>
                        <td><?php echo sanitize($qr['product_name']); ?></td>
                        <td><?php echo sanitize($qr['quantity']); ?></td>
                        <td><small><?php echo date('d M Y', strtotime($qr['created_at'])); ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
function getStatusBadge($status) {
    $badges = [
        'New Lead' => 'badge-new',
        'Contacted' => 'badge-contacted',
        'Quotation Sent' => 'badge-quotation',
        'Negotiation' => 'badge-negotiation',
        'Order Confirmed' => 'badge-confirmed',
        'Lost' => 'badge-lost'
    ];
    $class = $badges[$status] ?? 'badge-new';
    return '<span class="badge ' . $class . '">' . sanitize($status) . '</span>';
}
?>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
