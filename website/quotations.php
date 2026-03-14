<?php
/**
 * Admin - Quotation Generator & History
 */
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'generate') {
        $customerName = sanitize($_POST['customer_name']);
        $customerPhone = sanitize($_POST['customer_phone']);
        $customerEmail = sanitize($_POST['customer_email']);
        $product = sanitize($_POST['product']);
        $quantity = sanitize($_POST['quantity']);
        $price = (float)$_POST['price'];
        $transportCharges = (float)$_POST['transport_charges'];
        $totalAmount = $price + $transportCharges;
        $notes = sanitize($_POST['notes']);

        try {
            $stmt = $db->prepare('INSERT INTO quotations (customer_name, customer_phone, customer_email, product, quantity, price, transport_charges, total_amount, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$customerName, $customerPhone, $customerEmail, $product, $quantity, $price, $transportCharges, $totalAmount, $notes, 'Generated']);
            $lastId = $db->lastInsertId();
            $message = 'Quotation #' . $lastId . ' generated successfully.';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error generating quotation.';
            $messageType = 'danger';
        }
    }
    if ($_POST['action'] === 'delete') {
        $db->prepare('DELETE FROM quotations WHERE id = ?')->execute([(int)$_POST['id']]);
        $message = 'Quotation deleted.';
        $messageType = 'success';
    }
}

$quotations = $db->query('SELECT * FROM quotations ORDER BY created_at DESC')->fetchAll();

// If downloading PDF
if (isset($_GET['download']) && is_numeric($_GET['download'])) {
    $stmt = $db->prepare('SELECT * FROM quotations WHERE id = ?');
    $stmt->execute([(int)$_GET['download']]);
    $quote = $stmt->fetch();
    if ($quote) {
        $phone = getSetting('phone', '+91 8981040333');
        $email = getSetting('email', 'contact@shreeanukulsteels.com');
        $address = getSetting('address', 'Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St, Sreebhumi, Lake Town, Kolkata - 700048');

        // Generate HTML-based printable quotation
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Quotation #' . $quote['id'] . '</title>';
        echo '<style>body{font-family:Arial,sans-serif;max-width:800px;margin:0 auto;padding:40px;color:#333;}';
        echo '.header{text-align:center;border-bottom:3px solid #1a56db;padding-bottom:20px;margin-bottom:30px;}';
        echo '.header h1{color:#1a56db;margin:0;font-size:24px;}.header p{margin:5px 0;color:#666;font-size:13px;}';
        echo '.quote-info{display:flex;justify-content:space-between;margin-bottom:30px;}';
        echo '.quote-info div{flex:1;}.label{font-weight:bold;color:#555;font-size:13px;}';
        echo 'table{width:100%;border-collapse:collapse;margin:20px 0;}';
        echo 'th{background:#1a56db;color:#fff;padding:10px;text-align:left;font-size:13px;}';
        echo 'td{padding:10px;border-bottom:1px solid #eee;font-size:13px;}';
        echo '.total-row td{font-weight:bold;font-size:15px;border-top:2px solid #333;}';
        echo '.footer{margin-top:40px;text-align:center;color:#888;font-size:12px;border-top:1px solid #eee;padding-top:20px;}';
        echo '@media print{body{padding:20px;}}</style></head><body>';
        echo '<div class="header"><h1>SHREE ANUKUL STEELS</h1>';
        echo '<p>' . sanitize($address) . '</p>';
        echo '<p>Phone: ' . sanitize($phone) . ' | Email: ' . sanitize($email) . '</p>';
        echo '<h2 style="margin-top:20px;color:#333;">QUOTATION</h2></div>';
        echo '<div class="quote-info"><div>';
        echo '<p><span class="label">Quote ID:</span> #' . $quote['id'] . '</p>';
        echo '<p><span class="label">Date:</span> ' . date('d M Y', strtotime($quote['created_at'])) . '</p></div>';
        echo '<div style="text-align:right;">';
        echo '<p><span class="label">Customer:</span> ' . sanitize($quote['customer_name']) . '</p>';
        if (!empty($quote['customer_phone'])) echo '<p><span class="label">Phone:</span> ' . sanitize($quote['customer_phone']) . '</p>';
        if (!empty($quote['customer_email'])) echo '<p><span class="label">Email:</span> ' . sanitize($quote['customer_email']) . '</p>';
        echo '</div></div>';
        echo '<table><thead><tr><th>Product</th><th>Quantity</th><th>Price (INR)</th></tr></thead>';
        echo '<tbody><tr><td>' . sanitize($quote['product']) . '</td><td>' . sanitize($quote['quantity']) . '</td><td>' . number_format($quote['price'], 2) . '</td></tr>';
        echo '<tr><td colspan="2" style="text-align:right;">Transport Charges</td><td>' . number_format($quote['transport_charges'], 2) . '</td></tr>';
        echo '<tr class="total-row"><td colspan="2" style="text-align:right;">TOTAL AMOUNT</td><td>INR ' . number_format($quote['total_amount'], 2) . '</td></tr>';
        echo '</tbody></table>';
        if (!empty($quote['notes'])) echo '<p><strong>Notes:</strong> ' . sanitize($quote['notes']) . '</p>';
        echo '<div class="footer"><p>This is a computer-generated quotation from Shree Anukul Steels.</p>';
        echo '<p>For queries, contact us at ' . sanitize($phone) . ' or ' . sanitize($email) . '</p></div>';
        echo '<script>window.print();</script></body></html>';
        exit;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Quotation Generator</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#quoteGenModal"><i class="bi bi-plus-lg me-1"></i>Generate Quotation</button>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show"><?php echo sanitize($message); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Quotation History -->
<div class="admin-card">
    <div class="card-header"><i class="bi bi-clock-history me-2"></i>Quotation History</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0" id="quotationsTable">
                <thead><tr><th>Quote ID</th><th>Customer</th><th>Product</th><th>Quantity</th><th>Amount (INR)</th><th>Date</th><th>Status</th><th class="action-col">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($quotations)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No quotations generated yet.</td></tr>
                <?php else: ?>
                <?php foreach ($quotations as $q): ?>
                <tr>
                    <td>#<?php echo $q['id']; ?></td>
                    <td><strong><?php echo sanitize($q['customer_name']); ?></strong><br><small class="text-muted"><?php echo sanitize($q['customer_phone']); ?></small></td>
                    <td><?php echo sanitize($q['product']); ?></td>
                    <td><?php echo sanitize($q['quantity']); ?></td>
                    <td><strong><?php echo number_format($q['total_amount'], 2); ?></strong></td>
                    <td><small><?php echo date('d M Y', strtotime($q['created_at'])); ?></small></td>
                    <td><span class="badge bg-success"><?php echo sanitize($q['status']); ?></span></td>
                    <td class="action-col">
                        <a href="?download=<?php echo $q['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Download/Print"><i class="bi bi-download"></i></a>
                        <form method="POST" style="display:inline;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $q['id']; ?>"><button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="Delete this quotation?"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Generate Quotation Modal -->
<div class="modal fade" id="quoteGenModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header"><h5 class="modal-title">Generate Quotation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="generate">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Customer Name *</label><input type="text" class="form-control" name="customer_name" required></div>
                        <div class="col-md-4"><label class="form-label">Phone</label><input type="tel" class="form-control" name="customer_phone"></div>
                        <div class="col-md-4"><label class="form-label">Email</label><input type="email" class="form-control" name="customer_email"></div>
                        <div class="col-md-6"><label class="form-label">Product *</label><input type="text" class="form-control" name="product" required placeholder="e.g., TMT Bars Fe-500D"></div>
                        <div class="col-md-6"><label class="form-label">Quantity</label><input type="text" class="form-control" name="quantity" placeholder="e.g., 100 Tons"></div>
                        <div class="col-md-4"><label class="form-label">Price (INR) *</label><input type="number" class="form-control" name="price" step="0.01" required id="qPrice" onchange="calcTotal()"></div>
                        <div class="col-md-4"><label class="form-label">Transport Charges (INR)</label><input type="number" class="form-control" name="transport_charges" step="0.01" value="0" id="qTransport" onchange="calcTotal()"></div>
                        <div class="col-md-4"><label class="form-label">Total Amount</label><input type="text" class="form-control" id="qTotal" readonly style="font-weight:bold;background:#f0f9ff;"></div>
                        <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-plus me-1"></i>Generate Quotation</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function calcTotal() {
    var price = parseFloat(document.getElementById('qPrice').value) || 0;
    var transport = parseFloat(document.getElementById('qTransport').value) || 0;
    document.getElementById('qTotal').value = 'INR ' + (price + transport).toFixed(2);
}
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
