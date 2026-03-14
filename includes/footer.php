    <!-- Footer -->
    <footer class="footer bg-dark text-white">
        <div class="container">
            <div class="row py-5">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Shree Anukul Steels</h5>
                    <p class="text-white-50">Industry leaders since 1975. Supplying premium quality steel products to builders and construction companies across India.</p>
                    <div class="footer-social mt-3">
                        <?php if ($fb = getSetting('facebook')): ?>
                        <a href="<?php echo sanitize($fb); ?>" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-facebook fs-5"></i></a>
                        <?php endif; ?>
                        <?php if ($tw = getSetting('twitter')): ?>
                        <a href="<?php echo sanitize($tw); ?>" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-twitter-x fs-5"></i></a>
                        <?php endif; ?>
                        <?php if ($li = getSetting('linkedin')): ?>
                        <a href="<?php echo sanitize($li); ?>" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-linkedin fs-5"></i></a>
                        <?php endif; ?>
                        <?php if ($ig = getSetting('instagram')): ?>
                        <a href="<?php echo sanitize($ig); ?>" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-instagram fs-5"></i></a>
                        <?php endif; ?>
                        <?php if ($yt = getSetting('youtube')): ?>
                        <a href="<?php echo sanitize($yt); ?>" target="_blank" rel="noopener" class="text-white"><i class="bi bi-youtube fs-5"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products.php">Products</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/projects.php">Projects</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Our Products</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="<?php echo SITE_URL; ?>/products.php?category=tmt-bars">TMT Bars</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products.php?category=steel-pipes">Steel Pipes</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products.php?category=steel-plates">Steel Plates</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products.php?category=steel-angles">Steel Angles</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/products.php?category=ms-beam">MS Beam</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Contact Info</h5>
                    <ul class="list-unstyled footer-contact">
                        <li><i class="bi bi-geo-alt-fill text-primary me-2"></i> <?php echo sanitize($address); ?></li>
                        <li class="mt-2"><i class="bi bi-telephone-fill text-primary me-2"></i> <a href="tel:<?php echo str_replace(' ', '', $phone); ?>" class="text-white-50"><?php echo sanitize($phone); ?></a></li>
                        <li class="mt-2"><i class="bi bi-envelope-fill text-primary me-2"></i> <a href="mailto:<?php echo sanitize($email); ?>" class="text-white-50"><?php echo sanitize($email); ?></a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="row py-3">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Shree Anukul Steels. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-white-50">GEM Registered | BIS Approved Products</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/<?php echo sanitize($whatsapp); ?>?text=Hello%2C%20I%20am%20interested%20in%20your%20steel%20products." class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav d-lg-none">
        <a href="<?php echo SITE_URL; ?>/index.php" class="mobile-nav-item <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
            <i class="bi bi-house-fill"></i>
            <span>Home</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/products.php" class="mobile-nav-item <?php echo $currentPage === 'products' ? 'active' : ''; ?>">
            <i class="bi bi-box-fill"></i>
            <span>Products</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/projects.php" class="mobile-nav-item <?php echo $currentPage === 'projects' ? 'active' : ''; ?>">
            <i class="bi bi-building-fill"></i>
            <span>Projects</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="mobile-nav-item <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">
            <i class="bi bi-telephone-fill"></i>
            <span>Contact</span>
        </a>
        <a href="https://wa.me/<?php echo sanitize($whatsapp); ?>?text=Hello%2C%20I%20am%20interested%20in%20your%20steel%20products." class="mobile-nav-item whatsapp" target="_blank" rel="noopener">
            <i class="bi bi-whatsapp"></i>
            <span>WhatsApp</span>
        </a>
    </nav>

    <!-- Quote Request Modal -->
    <div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="quoteModalLabel">Request a Quote</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quoteForm">
                        <div class="mb-3">
                            <label for="quoteName" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="quoteName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="quotePhone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="quotePhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="quoteEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="quoteEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="quoteProduct" class="form-label">Product *</label>
                            <input type="text" class="form-control" id="quoteProduct" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="quoteQuantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quoteQuantity" name="quantity" placeholder="e.g., 100 Tons">
                        </div>
                        <div class="mb-3">
                            <label for="quoteMessage" class="form-label">Additional Details</label>
                            <textarea class="form-control" id="quoteMessage" name="message" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Quote Request</button>
                    </form>
                    <div id="quoteFormMessage" class="mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
