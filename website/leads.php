<?php
/**
 * Admin - Lead Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $leadId = (int)$_POST['lead_id'];
        $status = sanitize($_POST['status']);
        $stmt = $db->prepare('UPDATE leads SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        $stmt->execute([$status, $leadId]);
        $message = 'Lead status updated successfully.';
        $messageType = 'success';
    }
    if ($_POST['action'] === 'add_reminder') {
        $leadId = (int)$_POST['lead_id'];
        $reminderDate = sanitize($_POST['reminder_date']);
        $reminderNote = sanitize($_POST['reminder_note']);
        $stmt = $db->prepare('INSERT INTO lead_reminders (lead_id, reminder_date, reminder_note) VALUES (?, ?, ?)');
        $stmt->execute([$leadId, $reminderDate, $reminderNote]);
        $message = 'Reminder added successfully.';
        $messageType = 'success';
    }
    if ($_POST['action'] === 'complete_reminder') {
        $remId = (int)$_POST['reminder_id'];
        $stmt = $db->prepare('UPDATE lead_reminders SET is_completed = 1 WHERE id = ?');
        $stmt->execute([$remId]);
        $message = 'Reminder marked as completed.';
        $messageType = 'success';
    }
    if ($_POST['action'] === 'delete_lead') {
        $leadId = (int)$_POST['lead_id'];
        $db->prepare('DELETE FROM lead_reminders WHERE lead_id = ?')->execute([$leadId]);
        $db->prepare('DELETE FROM leads WHERE id = ?')->execute([$leadId]);
        $message = 'Lead deleted successfully.';
        $messageType = 'success';
    }
}

// Filter
$statusFilter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$searchQuery = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$showReminders = isset($_GET['reminders']) && $_GET['reminders'] == '1';

// Build query
$where = [];
$params = [];

if (!empty($statusFilter)) {
    $where[] = 'l.status = ?';
    $params[] = $statusFilter;
}
if (!empty($searchQuery)) {
    $where[] = '(l.customer_name LIKE ? OR l.phone LIKE ? OR l.email LIKE ? OR l.city LIKE ?)';
    $searchTerm = '%' . $searchQuery . '%';
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$leads = $db->prepare("SELECT l.* FROM leads l {$whereClause} ORDER BY l.created_at DESC");
$leads->execute($params);
$leads = $leads->fetchAll();

// Get reminders if needed
$reminders = [];
if ($showReminders) {
    $reminders = $db->query("SELECT lr.*, l.customer_name, l.phone, l.email FROM lead_reminders lr JOIN leads l ON lr.lead_id = l.id WHERE lr.is_completed = 0 ORDER BY lr.reminder_date ASC")->fetchAll();
}

$statuses = ['New Lead', 'Contacted', 'Quotation Sent', 'Negotiation', 'Order Confirmed', 'Lost'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Lead Management</h4>
    <div>
        <button class="btn btn-sm btn-success" onclick="exportTableToCSV('leadsTable', 'leads-export.csv')"><i class="bi bi-download me-1"></i>Export CSV</button>
    </div>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
    <?php echo sanitize($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Reminders Section -->
<?php if ($showReminders && !empty($reminders)): ?>
<div class="admin-card mb-4">
    <div class="card-header bg-warning text-dark">
        <i class="bi bi-bell-fill me-2"></i>Pending Follow-up Reminders
    </div>
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead><tr><th>Customer</th><th>Phone</th><th>Reminder Date</th><th>Note</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($reminders as $rem): ?>
            <tr>
                <td><?php echo sanitize($rem['customer_name']); ?></td>
                <td><?php echo sanitize($rem['phone']); ?></td>
                <td><?php echo date('d M Y', strtotime($rem['reminder_date'])); ?></td>
                <td><?php echo sanitize($rem['reminder_note'] ?? ''); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="complete_reminder">
                        <input type="hidden" name="reminder_id" value="<?php echo $rem['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Done</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="admin-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="<?php echo sanitize($searchQuery); ?>" placeholder="Name, phone, email...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo $statusFilter === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-3">
                <a href="<?php echo SITE_URL; ?>/website/leads.php" class="btn btn-outline-secondary">Reset</a>
                <a href="<?php echo SITE_URL; ?>/website/leads.php?reminders=1" class="btn btn-outline-warning ms-1"><i class="bi bi-bell-fill"></i> Reminders</a>
            </div>
        </form>
    </div>
</div>

<!-- Leads Table -->
<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0" id="leadsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Requirement</th>
                        <th>Source</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($leads)): ?>
                <tr><td colspan="10" class="text-center text-muted py-4">No leads found.</td></tr>
                <?php else: ?>
                <?php foreach ($leads as $lead): ?>
                <tr>
                    <td><?php echo $lead['id']; ?></td>
                    <td><strong><?php echo sanitize($lead['customer_name']); ?></strong></td>
                    <td><?php echo sanitize($lead['phone']); ?></td>
                    <td><?php echo sanitize($lead['email']); ?></td>
                    <td><?php echo sanitize($lead['city']); ?></td>
                    <td><small><?php echo sanitize(substr($lead['requirement'] ?? '', 0, 80)); ?></small></td>
                    <td><small><?php echo sanitize($lead['lead_source']); ?></small></td>
                    <td><small><?php echo date('d M Y', strtotime($lead['created_at'])); ?></small></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:130px;">
                                <?php foreach ($statuses as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $lead['status'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td class="action-col">
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reminderModal<?php echo $lead['id']; ?>" title="Set Reminder"><i class="bi bi-bell"></i></button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_lead">
                            <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Are you sure you want to delete this lead?"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- Reminder Modal -->
                <div class="modal fade" id="reminderModal<?php echo $lead['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header"><h6 class="modal-title">Set Follow-up Reminder</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="add_reminder">
                                    <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Reminder Date</label>
                                        <input type="date" class="form-control" name="reminder_date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" name="reminder_note" rows="2" placeholder="Follow-up note..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Set Reminder</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
