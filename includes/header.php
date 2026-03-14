<?php
require_once __DIR__ . '/config.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$seo = getSEO($currentPage === 'index' ? 'home' : $currentPage);
$siteName = getSetting('site_name', 'Shree Anukul Steels');
$phone = getSetting('phone', '+91 8981040333');
$email = getSetting('email', 'contact@shreeanukulsteels.com');
$whatsapp = getSetting('whatsapp', '918981040333');
$address = getSetting('address', 'Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St, Sreebhumi, Lake Town, Kolkata - 700048, West Bengal, India');

$metaTitle = !empty($seo['meta_title']) ? $seo['meta_title'] : $siteName . ' - Premium TMT Steel Solutions';
$metaDescription = !empty($seo['meta_description']) ? $seo['meta_description'] : 'Shree Anukul Steels is a leading steel supplier in India since 1975.';
$metaKeywords = !empty($seo['meta_keywords']) ? $seo['meta_keywords'] : 'steel supplier india, tmt bars, steel trader';
$ogTitle = !empty($seo['og_title']) ? $seo['og_title'] : $metaTitle;
$ogDescription = !empty($seo['og_description']) ? $seo['og_description'] : $metaDescription;
$ogImage = !empty($seo['og_image']) ? SITE_URL . '/' . $seo['og_image'] : SITE_URL . '/assets/images/og-default.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title><?php echo sanitize($metaTitle); ?></title>
    <meta name="description" content="<?php echo sanitize($metaDescription); ?>">
    <meta name="keywords" content="<?php echo sanitize($metaKeywords); ?>">
    <meta name="author" content="Shree Anukul Steels">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL . '/' . ($currentPage === 'index' ? '' : $currentPage . '.php'); ?>">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo sanitize($ogTitle); ?>">
    <meta property="og:description" content="<?php echo sanitize($ogDescription); ?>">
    <meta property="og:image" content="<?php echo sanitize($ogImage); ?>">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Shree Anukul Steels">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo sanitize($ogTitle); ?>">
    <meta name="twitter:description" content="<?php echo sanitize($ogDescription); ?>">
    <meta name="twitter:image" content="<?php echo sanitize($ogImage); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo SITE_URL; ?>/assets/css/style.css" rel="stylesheet">

    <!-- Structured Data - Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Shree Anukul Steels",
        "url": "<?php echo SITE_URL; ?>",
        "logo": "<?php echo SITE_URL; ?>/assets/images/logo.png",
        "description": "Leading steel supplier in India since 1975. Supplying TMT Bars, Steel Pipes, Steel Plates, and construction steel materials.",
        "telephone": "<?php echo sanitize($phone); ?>",
        "email": "<?php echo sanitize($email); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St",
            "addressLocality": "Kolkata",
            "addressRegion": "West Bengal",
            "postalCode": "700048",
            "addressCountry": "IN"
        },
        "sameAs": [
            "<?php echo getSetting('facebook', ''); ?>",
            "<?php echo getSetting('linkedin', ''); ?>",
            "<?php echo getSetting('instagram', ''); ?>"
        ],
        "foundingDate": "1975",
        "founder": {
            "@type": "Person",
            "name": "Mr. Rahul Paul"
        }
    }
    </script>

    <!-- Structured Data - Local Business -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Shree Anukul Steels",
        "image": "<?php echo SITE_URL; ?>/assets/images/logo.png",
        "telephone": "<?php echo sanitize($phone); ?>",
        "email": "<?php echo sanitize($email); ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St, Sreebhumi, Lake Town",
            "addressLocality": "Kolkata",
            "addressRegion": "West Bengal",
            "postalCode": "700048",
            "addressCountry": "IN"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "22.5958",
            "longitude": "88.4017"
        },
        "openingHours": "Mo-Sa 09:00-18:00",
        "priceRange": "$$",
        "url": "<?php echo SITE_URL; ?>"
    }
    </script>

    <!-- Google Analytics placeholder -->
    <?php $gaCode = getSetting('google_analytics', ''); if (!empty($gaCode)): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo sanitize($gaCode); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo sanitize($gaCode); ?>');
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="top-bar-info">
                        <span><i class="bi bi-telephone-fill"></i> <?php echo sanitize($phone); ?></span>
                        <span class="ms-4"><i class="bi bi-envelope-fill"></i> <?php echo sanitize($email); ?></span>
                        <span class="ms-4"><i class="bi bi-geo-alt-fill"></i> Kolkata, West Bengal, India</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="top-bar-social">
                        <?php if ($fb = getSetting('facebook')): ?>
                        <a href="<?php echo sanitize($fb); ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                        <?php endif; ?>
                        <?php if ($tw = getSetting('twitter')): ?>
                        <a href="<?php echo sanitize($tw); ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a>
                        <?php endif; ?>
                        <?php if ($li = getSetting('linkedin')): ?>
                        <a href="<?php echo sanitize($li); ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = getSetting('instagram')): ?>
                        <a href="<?php echo sanitize($ig); ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = getSetting('youtube')): ?>
                        <a href="<?php echo sanitize($yt); ?>" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>/index.php">
                <!-- Replace with your logo -->
                <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="Shree Anukul Steels - Steel Supplier India" height="50" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
                <span class="logo-text" style="display:none"><strong>Shree Anukul</strong> Steels</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'about' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'products' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'projects' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                    </li>
                    <li class="nav-item ms-2 d-none d-lg-block">
                        <a class="btn btn-primary btn-sm" href="<?php echo SITE_URL; ?>/contact.php">Get Quote</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
