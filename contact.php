<?php
/**
 * Shree Anukul Steels - Contact Page
 */
require_once __DIR__ . '/includes/header.php';
$recaptchaSiteKey = getSetting('recaptcha_site_key', '');
?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Get in Touch With Shree Anukul Steels for Steel Product Enquiries</p>
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="section-padding">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="contact-info-card">
                        <div class="icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <h6>Office Address</h6>
                        <p>Unit 3, 2nd Floor, Mahabir Highrise, 356 Canal St, Sreebhumi, Lake Town, Kolkata - 700048</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info-card">
                        <div class="icon"><i class="bi bi-telephone-fill"></i></div>
                        <h6>Phone Number</h6>
                        <p><a href="tel:+918981040333" class="text-dark">+91 8981040333</a></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info-card">
                        <div class="icon"><i class="bi bi-envelope-fill"></i></div>
                        <h6>Email Address</h6>
                        <p><a href="mailto:contact@shreeanukulsteels.com" class="text-dark">contact@shreeanukulsteels.com</a></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-info-card">
                        <div class="icon"><i class="bi bi-whatsapp"></i></div>
                        <h6>WhatsApp</h6>
                        <p><a href="https://wa.me/918981040333" target="_blank" rel="noopener" class="text-dark">+91 8981040333</a></p>
                    </div>
                </div>
            </div>

            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="contact-form-card">
                        <h3 class="mb-1">Send Us a Message</h3>
                        <p class="text-muted mb-4">Fill out the form below and we will get back to you as soon as possible.</p>
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contactName" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control" id="contactName" name="name" required placeholder="Enter your name">
                                </div>
                                <div class="col-md-6">
                                    <label for="contactEmail" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="contactEmail" name="email" required placeholder="Enter your email">
                                </div>
                                <div class="col-md-6">
                                    <label for="contactPhone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="contactPhone" name="phone" required placeholder="Enter your phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="contactSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="contactSubject" name="subject" placeholder="Enter subject">
                                </div>
                                <div class="col-12">
                                    <label for="contactMessage" class="form-label">Message *</label>
                                    <textarea class="form-control" id="contactMessage" name="message" rows="5" required placeholder="Write your message here..."></textarea>
                                </div>
                                <?php if (!empty($recaptchaSiteKey)): ?>
                                <div class="col-12">
                                    <div class="g-recaptcha" data-sitekey="<?php echo sanitize($recaptchaSiteKey); ?>"></div>
                                </div>
                                <?php endif; ?>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-send-fill me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="contactFormMessage" class="mt-3" style="display:none;"></div>
                    </div>
                </div>

                <!-- Map -->
                <div class="col-lg-5" data-aos="fade-left">
                    <h3 class="mb-3">Our Location</h3>
                    <div class="map-container mb-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.5!2d88.4017!3d22.5958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDM1JzQ0LjkiTiA4OMKwMjQnMDYuMSJF!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Shree Anukul Steels Office Location - Kolkata"></iframe>
                    </div>
                    <div class="bg-light rounded-3 p-4">
                        <h5><i class="bi bi-clock-fill text-primary me-2"></i>Business Hours</h5>
                        <table class="table table-borderless mb-0" style="font-size:0.9rem;">
                            <tr><td>Monday - Saturday</td><td class="text-end fw-600">9:00 AM - 6:00 PM</td></tr>
                            <tr><td>Sunday</td><td class="text-end fw-600 text-danger">Closed</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($recaptchaSiteKey)): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
