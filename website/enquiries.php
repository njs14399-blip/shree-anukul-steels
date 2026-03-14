<?php
/**
 * Admin - Enquiry Management
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $id = (int)$_POST['id'];
        $status = sanitize($_POST['status']);
        $db->prepare('UPDATE enquiries SET status = ? WHERE id = ?')->execute([$status, $id]);
        $message = 'Enquiry status updated.';
        $messageType = 'success';
    }
    if ($_POST['action'] === 'delete') {
        $db->prepare('DELETE FROM enquiries WHERE id = ?')->execute([(int)$_POST['id']]);
        $message = 'Enquiry deleted.';
        $messageType = 'success';
    }
}

$enquiries = $db->query('SELECT * FROM enquiries ORDER BY created_at DESC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-envelope-fill me-2"></i>Website Enquiries</h4>
    <button class="btn btn-sm btn-success" onclick="exportTableToCSV('enquiriesTable', 'enquiries-export.csv')"><i class="bi bi-download me-1"></i>Export CSV</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0" id="enquiriesTable">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th class="action-col">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($enquiries)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">No enquiries yet.</td></tr>
                <?php else: ?>
                <?php foreach ($enquiries as $enq): ?>
                <tr>
                    <td><?php echo $enq['id']; ?></td>
                    <td><strong><?php echo sanitize($enq['name']); ?></strong></td>
                    <td><a href="mailto:<?php echo sanitize($enq['email']); ?>"><?php echo sanitize($enq['email']); ?></a></td>
                    <td><?php echo sanitize($enq['phone']); ?></td>
                    <td><?php echo sanitize($enq['subject']); ?></td>
                    <td><small><?php echo sanitize(substr($enq['message'], 0, 100)); ?></small></td>
                    <td><small><?php echo date('d M Y', strtotime($enq['created_at'])); ?></small></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $enq['id']; ?>">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:100px;">
                                <option value="New" <?php echo $enq['status'] === 'New' ? 'selected' : ''; ?>>New</option>
                                <option value="Read" <?php echo $enq['status'] === 'Read' ? 'selected' : ''; ?>>Read</option>
                                <option value="Replied" <?php echo $enq['status'] === 'Replied' ? 'selected' : ''; ?>>Replied</option>
                                <option value="Closed" <?php echo $enq['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </form>
                    </td>
                    <td class="action-col">
                        <form method="POST" style="display:inline;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $enq['id']; ?>"><button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this enquiry?"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
