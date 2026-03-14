<?php
/**
 * Shree Anukul Steels - Home Page
 * Premium TMT Steel Solutions | Steel Supplier India
 */
require_once __DIR__ . '/includes/header.php';

// Fetch products
$db = getDB();
$products = $db->query('SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.status = 1 ORDER BY p.sort_order ASC LIMIT 4')->fetchAll();

// Fetch projects
$projects = $db->query('SELECT p.*, pc.name as category_name FROM projects p LEFT JOIN project_categories pc ON p.category_id = pc.id WHERE p.status = 1 ORDER BY p.created_at DESC LIMIT 3')->fetchAll();

// Fetch testimonials
$testimonials = $db->query('SELECT * FROM testimonials WHERE status = 1 ORDER BY sort_order ASC')->fetchAll();
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="hero-content">
                        <span class="hero-tagline"><i class="bi bi-star-fill text-warning me-2"></i>Industry Leaders Since 1975</span>
                        <h1 class="hero-title">Premium <span>TMT Steel</span> Solutions</h1>
                        <p class="hero-desc">Shree Anukul Steels is a leading steel supplier in India, providing high-quality TMT bars, structural steel, and construction materials to builders and construction companies across the nation.</p>
                        <div class="hero-buttons">
                            <a href="<?php echo SITE_URL; ?>/products.php" class="btn btn-primary btn-lg">Explore Products</a>
                            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-outline-light btn-lg">Contact Us</a>
                        </div>
                        <div class="hero-stats">
                            <div class="hero-stat-item">
                                <h3>500+</h3>
                                <p>Projects Completed</p>
                            </div>
                            <div class="hero-stat-item">
                                <h3>200K+</h3>
                                <p>Tons Supplied</p>
                            </div>
                            <div class="hero-stat-item">
                                <h3>5000+</h3>
                                <p>Happy Clients</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
                    <div class="hero-image">
                        <div class="img-placeholder" style="height:450px;border-radius:12px;">
                            <i class="bi bi-building"></i>
                        </div>
                        <!-- Replace with: <img src="assets/images/hero-banner.jpg" alt="Premium TMT Steel Solutions - Shree Anukul Steels"> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- App Promotion Section -->
    <section class="app-promo-section section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="img-placeholder" style="height:400px;border-radius:12px;background:rgba(255,255,255,0.1);">
                        <i class="bi bi-phone" style="font-size:5rem;color:rgba(255,255,255,0.3);"></i>
                    </div>
                    <!-- Replace with app mockup image -->
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="hero-tagline"><i class="bi bi-phone-fill me-2"></i>Mobile App</span>
                    <h2 class="app-promo-title mt-3">Trade Steel Easily With Our Mobile App</h2>
                    <p class="app-promo-desc">Users can trade steel materials directly through the mobile application. Browse, order, and track your steel deliveries right from your phone.</p>
                    <ul class="app-feature-list mb-4">
                        <li><i class="bi bi-check-circle-fill"></i> TMT Bars</li>
                        <li><i class="bi bi-check-circle-fill"></i> Structural Steel</li>
                        <li><i class="bi bi-check-circle-fill"></i> Steel Pipes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Steel Wires</li>
                        <li><i class="bi bi-check-circle-fill"></i> Steel Plates</li>
                    </ul>
                    <div>
                        <a href="https://play.google.com/store/apps/details?id=YOUR_APP_ID" target="_blank" rel="noopener" class="store-btn">
                            <i class="bi bi-google-play"></i>
                            <div>
                                <small>GET IT ON</small>
                                Google Play
                            </div>
                        </a>
                        <a href="https://apps.apple.com/app/idYOUR_APP_ID" target="_blank" rel="noopener" class="store-btn">
                            <i class="bi bi-apple"></i>
                            <div>
                                <small>DOWNLOAD ON</small>
                                App Store
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview Section -->
    <section class="about-preview-section section-padding">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="section-subtitle">About Us</span>
                    <h2 class="section-title">Building Strong Foundations Since 1975</h2>
                    <p class="section-desc mb-4">Shree Anukul Steels has been supplying high quality steel products to builders and construction companies for decades. Our commitment to quality and customer satisfaction has made us a trusted name in the steel industry.</p>
                    <a href="<?php echo SITE_URL; ?>/about.php" class="btn btn-primary">Learn More About Us</a>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="about-highlight-card">
                                <div class="icon"><i class="bi bi-shield-check"></i></div>
                                <h5>GEM Registered</h5>
                                <p>Government e-Marketplace registered trader</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-highlight-card">
                                <div class="icon"><i class="bi bi-patch-check"></i></div>
                                <h5>BIS Approved</h5>
                                <p>Bureau of Indian Standards approved products</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-highlight-card">
                                <div class="icon"><i class="bi bi-gear-wide-connected"></i></div>
                                <h5>Quality Control</h5>
                                <p>Advanced quality control processes</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-highlight-card">
                                <div class="icon"><i class="bi bi-truck"></i></div>
                                <h5>Reliable Delivery</h5>
                                <p>Pan-India delivery network</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-subtitle">Our Products</span>
                <h2 class="section-title">Premium Steel Products</h2>
                <p class="section-desc mx-auto">We supply a wide range of high-quality steel products for construction, manufacturing, and industrial applications.</p>
            </div>
            <div class="row g-4">
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
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="<?php echo SITE_URL; ?>/products.php" class="btn btn-outline-primary">View All Products <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </section>

    <!-- Featured Projects Section -->
    <section class="section-padding bg-gray">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-subtitle">Our Projects</span>
                <h2 class="section-title">Featured Construction Projects</h2>
                <p class="section-desc mx-auto">Showcasing construction projects built with premium steel supplied by Shree Anukul Steels.</p>
            </div>
            <div class="row g-4">
                <?php foreach ($projects as $project): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="project-card">
                        <div class="project-card-img">
                            <?php if (!empty($project['image']) && file_exists(UPLOAD_PATH . $project['image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($project['image']); ?>" alt="<?php echo sanitize($project['name']); ?> - Steel Supply Project" loading="lazy">
                            <?php else: ?>
                                <div class="img-placeholder" style="width:100%;height:100%;"><i class="bi bi-building" style="font-size:3rem;"></i></div>
                            <?php endif; ?>
                            <?php if (!empty($project['category_name'])): ?>
                            <span class="badge bg-primary"><?php echo sanitize($project['category_name']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="project-card-body">
                            <h5><?php echo sanitize($project['name']); ?></h5>
                            <div class="project-card-meta">
                                <span><i class="bi bi-geo-alt-fill"></i> <?php echo sanitize($project['location']); ?></span>
                                <?php if (!empty($project['steel_quantity'])): ?>
                                <span><i class="bi bi-box-fill"></i> <?php echo sanitize($project['steel_quantity']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($project['completion_year'])): ?>
                                <span><i class="bi bi-calendar-fill"></i> <?php echo sanitize($project['completion_year']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="<?php echo SITE_URL; ?>/projects.php" class="btn btn-outline-primary">View All Projects <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </section>

    <!-- Trust Statistics Section -->
    <section class="stats-section section-padding">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-item">
                        <div class="stat-number" data-target="500" data-suffix="+">0</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number" data-target="200000" data-suffix="+">0</div>
                        <div class="stat-label">Tons Steel Supplied</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number" data-target="5000" data-suffix="+">0</div>
                        <div class="stat-label">Satisfied Clients</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number" data-target="200" data-suffix="+">0</div>
                        <div class="stat-label">Cities Served</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <?php if (!empty($testimonials)): ?>
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-subtitle">Testimonials</span>
                <h2 class="section-title">What Our Clients Say</h2>
                <p class="section-desc mx-auto">Trusted by thousands of builders and construction companies across India.</p>
            </div>
            <div class="swiper swiper-testimonials" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="testimonial-stars">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                                <?php for ($i = $testimonial['rating']; $i < 5; $i++): ?>
                                <i class="bi bi-star"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonial-text"><?php echo sanitize($testimonial['review_text']); ?></p>
                            <div class="testimonial-author">
                                <h6><?php echo sanitize($testimonial['customer_name']); ?></h6>
                                <p><?php echo sanitize($testimonial['company_name']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Brand Partners Section -->
    <section class="brands-section section-padding">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-subtitle">Our Partners</span>
                <h2 class="section-title">Steel Brands We Supply</h2>
                <p class="section-desc mx-auto">We are authorized distributors of leading steel brands in India.</p>
            </div>
            <div class="row g-3" data-aos="fade-up">
                <?php
                $brands = ['ZEECON', 'VSP TMT', 'AIC OM TMT', 'SMS TMT', 'LOTUS TMT', 'ZARA TMT', 'SUPERSMELT', 'CPC TURBO', 'MAJEE TMT', 'SHRISHHTII TMT', 'SRD SHAKTI TMT', 'HR PLATINUM'];
                foreach ($brands as $brand):
                ?>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="brand-item">
                        <!-- Replace with brand logo: <img src="assets/images/brands/<?php echo strtolower(str_replace(' ', '-', $brand)); ?>.png" alt="<?php echo $brand; ?> Steel Brand"> -->
                        <span><?php echo $brand; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section section-padding">
        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-lg-8">
                    <h2>Ready to Start Your Construction Project?</h2>
                    <p class="mb-4">Get premium quality steel products at competitive prices. Contact us today for a free quotation.</p>
                    <div>
                        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-light btn-lg me-3 mb-2">Contact Us</a>
                        <a href="tel:+918981040333" class="btn btn-outline-light btn-lg mb-2"><i class="bi bi-telephone-fill me-2"></i>Call Now</a>
                    </div>
                    <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.7);font-size:0.9rem;"><i class="bi bi-telephone-fill me-1"></i> +91 8981040333</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Schema Structured Data -->
    <?php foreach ($products as $product): ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "<?php echo sanitize($product['name']); ?>",
        "description": "<?php echo sanitize($product['short_description']); ?>",
        "brand": {
            "@type": "Brand",
            "name": "Shree Anukul Steels"
        },
        "manufacturer": {
            "@type": "Organization",
            "name": "Shree Anukul Steels"
        },
        "category": "Steel Products",
        "url": "<?php echo SITE_URL; ?>/product-detail.php?slug=<?php echo urlencode($product['slug']); ?>"
    }
    </script>
    <?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
