<?php
/**
 * Dynamic XML Sitemap Generator
 */
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/xml; charset=utf-8');

$db = getDB();
$baseUrl = SITE_URL;

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url><loc><?php echo $baseUrl; ?>/index.php</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
    <url><loc><?php echo $baseUrl; ?>/about.php</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo $baseUrl; ?>/products.php</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>
    <url><loc><?php echo $baseUrl; ?>/projects.php</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    <url><loc><?php echo $baseUrl; ?>/contact.php</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>

    <!-- Product Pages -->
    <?php
    $products = $db->query('SELECT slug, updated_at FROM products WHERE status = 1')->fetchAll();
    foreach ($products as $p):
    ?>
    <url>
        <loc><?php echo $baseUrl; ?>/product-detail.php?slug=<?php echo urlencode($p['slug']); ?></loc>
        <lastmod><?php echo date('Y-m-d', strtotime($p['updated_at'])); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <?php endforeach; ?>

    <!-- Product Category Pages -->
    <?php
    $categories = $db->query('SELECT slug FROM product_categories WHERE status = 1')->fetchAll();
    foreach ($categories as $cat):
    ?>
    <url>
        <loc><?php echo $baseUrl; ?>/products.php?category=<?php echo urlencode($cat['slug']); ?></loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
</urlset>
