<?php
/**
 * Shree Anukul Steels - Product Detail Page
 */
require_once __DIR__ . '/includes/config.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
if (empty($slug)) {
    header('Location: products.php');
    exit;
}

$db = getDB();
$stmt = $db->prepare('SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.slug = ? AND p.status = 1');
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

// Get product images
$imgStmt = $db->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC');
$imgStmt->execute([$product['id']]);
$productImages = $imgStmt->fetchAll();

// Override SEO for this product
$seo = [
    'meta_title' => $product['name'] . ' - Shree Anukul Steels | Steel Supplier India',
    'meta_description' => $product['short_description'] . ' Buy from Shree Anukul Steels, trusted steel supplier since 1975.',
    'meta_keywords' => strtolower($product['name']) . ' supplier, ' . strtolower($product['name']) . ' india, steel supplier',
    'og_title' => $product['name'] . ' - Shree Anukul Steels',
    'og_description' => $product['short_description']
];

// Get related products
$relStmt = $db->prepare('SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 1 LIMIT 4');
$relStmt->execute([$product['category_id'], $product['id']]);
$relatedProducts = $relStmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

    <!-- Page Hero -->
    <section class="page-hero" style="padding:60px 0 40px;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/products.php">Products</a></li>
                    <?php if (!empty($product['category_name'])): ?>
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/products.php?category=<?php echo urlencode(generateSlug($product['category_name'])); ?>"><?php echo sanitize($product['category_name']); ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo sanitize($product['name']); ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Product Detail -->
    <section class="section-padding">
        <div class="container">
            <div class="row g-5">
                <!-- Product Images -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="product-detail-img">
                        <?php if (!empty($product['image']) && file_exists(UPLOAD_PATH . $product['image'])): ?>
                            <img id="mainProductImg" src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?> - Steel Supplier India">
                        <?php else: ?>
                            <div class="img-placeholder" style="height:400px;"><i class="bi bi-box" style="font-size:4rem;color:var(--gray-300);"></i></div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($productImages)): ?>
                    <div class="product-thumbnail-slider mt-3">
                        <?php if (!empty($product['image'])): ?>
                        <div class="product-thumbnail active" onclick="changeProductImage(this, '<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($product['image']); ?>')">
                            <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?>">
                        </div>
                        <?php endif; ?>
                        <?php foreach ($productImages as $img): ?>
                        <div class="product-thumbnail" onclick="changeProductImage(this, '<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($img['image_path']); ?>')">
                            <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($img['image_path']); ?>" alt="<?php echo sanitize($product['name']); ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6" data-aos="fade-left">
                    <?php if (!empty($product['category_name'])): ?>
                    <span class="badge bg-primary mb-2"><?php echo sanitize($product['category_name']); ?></span>
                    <?php endif; ?>
                    <h1 class="mb-3" style="font-size:2rem;"><?php echo sanitize($product['name']); ?></h1>
                    <p class="text-muted mb-4"><?php echo nl2br(sanitize($product['description'])); ?></p>

                    <!-- Specifications -->
                    <?php if (!empty($product['specifications'])): ?>
                    <h5 class="mt-4 mb-3"><i class="bi bi-list-check text-primary me-2"></i>Technical Specifications</h5>
                    <table class="table spec-table">
                        <tbody>
                        <?php
                        $specs = explode('|', $product['specifications']);
                        foreach ($specs as $spec):
                            $parts = explode(':', $spec, 2);
                            if (count($parts) === 2):
                        ?>
                        <tr>
                            <td><?php echo sanitize(trim($parts[0])); ?></td>
                            <td><?php echo sanitize(trim($parts[1])); ?></td>
                        </tr>
                        <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>

                    <!-- Features -->
                    <?php if (!empty($product['features'])): ?>
                    <h5 class="mt-4 mb-3"><i class="bi bi-star-fill text-primary me-2"></i>Product Features</h5>
                    <ul class="feature-list">
                        <?php
                        $features = explode('|', $product['features']);
                        foreach ($features as $feature):
                        ?>
                        <li><i class="bi bi-check-circle-fill"></i> <?php echo sanitize(trim($feature)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="mt-4 pt-3 border-top">
                        <button class="btn btn-primary btn-lg me-2 mb-2" onclick="openQuoteModal('<?php echo sanitize($product['name']); ?>')">
                            <i class="bi bi-chat-dots-fill me-2"></i>Get Quote
                        </button>
                        <a href="https://wa.me/<?php echo sanitize($whatsapp); ?>?text=Hi%2C%20I%20am%20interested%20in%20<?php echo urlencode($product['name']); ?>.%20Please%20share%20the%20price." class="btn btn-success btn-lg mb-2" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="tel:+918981040333" class="btn btn-outline-primary btn-lg mb-2">
                            <i class="bi bi-telephone-fill me-2"></i>Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <section class="section-padding bg-gray">
        <div class="container">
            <h3 class="mb-4">Related Products</h3>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $rel): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <div class="product-card-img">
                            <?php if (!empty($rel['image']) && file_exists(UPLOAD_PATH . $rel['image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($rel['image']); ?>" alt="<?php echo sanitize($rel['name']); ?>" loading="lazy">
                            <?php else: ?>
                                <div class="img-placeholder" style="width:100%;height:100%;"><i class="bi bi-box placeholder-icon"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-body">
                            <h5><?php echo sanitize($rel['name']); ?></h5>
                            <p><?php echo sanitize($rel['short_description']); ?></p>
                            <a href="<?php echo SITE_URL; ?>/product-detail.php?slug=<?php echo urlencode($rel['slug']); ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Product Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "<?php echo sanitize($product['name']); ?>",
        "description": "<?php echo sanitize($product['short_description']); ?>",
        "brand": { "@type": "Brand", "name": "Shree Anukul Steels" },
        "manufacturer": { "@type": "Organization", "name": "Shree Anukul Steels" },
        "category": "<?php echo sanitize($product['category_name'] ?? 'Steel Products'); ?>",
        "url": "<?php echo SITE_URL; ?>/product-detail.php?slug=<?php echo urlencode($product['slug']); ?>"
    }
    </script>

    <script>
    function changeProductImage(thumb, src) {
        document.getElementById('mainProductImg').src = src;
        document.querySelectorAll('.product-thumbnail').forEach(function(t) { t.classList.remove('active'); });
        thumb.classList.add('active');
    }
    </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
