<?php
/**
 * Shree Anukul Steels - Products Page
 */
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Get categories
$categories = $db->query('SELECT * FROM product_categories WHERE status = 1 ORDER BY sort_order ASC')->fetchAll();

// Get selected category
$selectedCategory = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Get products
if (!empty($selectedCategory)) {
    $stmt = $db->prepare('SELECT p.*, pc.name as category_name, pc.slug as category_slug FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.status = 1 AND pc.slug = ? ORDER BY p.sort_order ASC');
    $stmt->execute([$selectedCategory]);
} else {
    $stmt = $db->query('SELECT p.*, pc.name as category_name, pc.slug as category_slug FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.status = 1 ORDER BY p.sort_order ASC');
}
$products = $stmt->fetchAll();
?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Our Steel Products</h1>
            <p>Premium Quality Steel Products for Construction & Industrial Applications</p>
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Products Section -->
    <section class="section-padding">
        <div class="container">
            <!-- Category Filter -->
            <div class="category-filter mb-4" data-aos="fade-up">
                <a href="<?php echo SITE_URL; ?>/products.php" class="btn <?php echo empty($selectedCategory) ? 'btn-primary' : 'btn-outline-primary'; ?>">All Products</a>
                <?php foreach ($categories as $cat): ?>
                <a href="<?php echo SITE_URL; ?>/products.php?category=<?php echo urlencode($cat['slug']); ?>" class="btn <?php echo $selectedCategory === $cat['slug'] ? 'btn-primary' : 'btn-outline-primary'; ?>"><?php echo sanitize($cat['name']); ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Products Grid -->
            <div class="row g-4">
                <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box" style="font-size:3rem;color:var(--gray-300);"></i>
                    <p class="text-muted mt-3">No products found in this category.</p>
                </div>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="product-card">
                        <div class="product-card-img">
                            <?php if (!empty($product['image']) && file_exists(UPLOAD_PATH . $product['image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?> Supplier India" loading="lazy">
                            <?php else: ?>
                                <div class="img-placeholder" style="width:100%;height:100%;"><i class="bi bi-box placeholder-icon"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-body">
                            <span class="badge bg-primary-light text-primary mb-2" style="background:var(--primary-light)!important;"><?php echo sanitize($product['category_name'] ?? 'Steel'); ?></span>
                            <h5><?php echo sanitize($product['name']); ?></h5>
                            <p><?php echo sanitize($product['short_description']); ?></p>
                            <div class="product-card-actions">
                                <a href="<?php echo SITE_URL; ?>/product-detail.php?slug=<?php echo urlencode($product['slug']); ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                <button class="btn btn-primary btn-sm" onclick="openQuoteModal('<?php echo sanitize($product['name']); ?>')">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section section-padding">
        <div class="container text-center" data-aos="fade-up">
            <h2>Need a Custom Steel Solution?</h2>
            <p class="mb-4">Contact us for bulk orders, custom specifications, and competitive pricing.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-light btn-lg me-2">Get a Quote</a>
            <a href="tel:+918981040333" class="btn btn-outline-light btn-lg"><i class="bi bi-telephone-fill me-2"></i>Call Now</a>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
