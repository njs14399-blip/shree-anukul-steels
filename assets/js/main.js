/**
 * Shree Anukul Steels - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 50
    });

    // Navbar scroll effect
    initNavbarScroll();

    // Counter animation
    initCounterAnimation();

    // Testimonials slider
    initTestimonialsSlider();

    // Quote form
    initQuoteForm();

    // Contact form
    initContactForm();

    // Lazy loading images
    initLazyLoading();

    // Security features
    initSecurityFeatures();

    // Smooth scroll
    initSmoothScroll();
});

/**
 * Navbar scroll effect
 */
function initNavbarScroll() {
    var navbar = document.querySelector('.navbar');
    if (!navbar) return;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('shadow');
            navbar.style.padding = '6px 0';
        } else {
            navbar.classList.remove('shadow');
            navbar.style.padding = '12px 0';
        }
    });
}

/**
 * Animated counter
 */
function initCounterAnimation() {
    var counters = document.querySelectorAll('.stat-number');
    if (counters.length === 0) return;

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(function(counter) {
        observer.observe(counter);
    });
}

function animateCounter(element) {
    var target = parseInt(element.getAttribute('data-target')) || 0;
    var suffix = element.getAttribute('data-suffix') || '';
    var prefix = element.getAttribute('data-prefix') || '';
    var duration = 2000;
    var step = target / (duration / 16);
    var current = 0;

    function update() {
        current += step;
        if (current >= target) {
            current = target;
            element.textContent = prefix + formatNumber(target) + suffix;
            return;
        }
        element.textContent = prefix + formatNumber(Math.floor(current)) + suffix;
        requestAnimationFrame(update);
    }
    update();
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Testimonials Swiper slider
 */
function initTestimonialsSlider() {
    var swiperEl = document.querySelector('.swiper-testimonials');
    if (!swiperEl) return;

    new Swiper('.swiper-testimonials', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
}

/**
 * Quote form submission
 */
function initQuoteForm() {
    var form = document.getElementById('quoteForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        var msgDiv = document.getElementById('quoteFormMessage');
        var submitBtn = form.querySelector('button[type="submit"]');
        var originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        fetch(getSiteUrl() + '/api/quote-request.php', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            msgDiv.style.display = 'block';
            if (data.success) {
                msgDiv.className = 'mt-3 alert alert-success';
                msgDiv.textContent = data.message || 'Quote request submitted successfully! We will contact you soon.';
                form.reset();
            } else {
                msgDiv.className = 'mt-3 alert alert-danger';
                msgDiv.textContent = data.message || 'Failed to submit. Please try again.';
            }
        })
        .catch(function() {
            msgDiv.style.display = 'block';
            msgDiv.className = 'mt-3 alert alert-danger';
            msgDiv.textContent = 'An error occurred. Please try again later.';
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
}

/**
 * Contact form submission
 */
function initContactForm() {
    var form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        var msgDiv = document.getElementById('contactFormMessage');
        var submitBtn = form.querySelector('button[type="submit"]');
        var originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        fetch(getSiteUrl() + '/api/contact.php', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            msgDiv.style.display = 'block';
            if (data.success) {
                msgDiv.className = 'mt-3 alert alert-success';
                msgDiv.textContent = data.message || 'Message sent successfully! We will get back to you soon.';
                form.reset();
            } else {
                msgDiv.className = 'mt-3 alert alert-danger';
                msgDiv.textContent = data.message || 'Failed to send. Please try again.';
            }
        })
        .catch(function() {
            msgDiv.style.display = 'block';
            msgDiv.className = 'mt-3 alert alert-danger';
            msgDiv.textContent = 'An error occurred. Please try again later.';
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
}

/**
 * Lazy loading images
 */
function initLazyLoading() {
    var lazyImages = document.querySelectorAll('img[data-src]');
    if (lazyImages.length === 0) return;

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.getAttribute('data-src');
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        lazyImages.forEach(function(img) { observer.observe(img); });
    } else {
        lazyImages.forEach(function(img) {
            img.src = img.getAttribute('data-src');
        });
    }
}

/**
 * Security features (disable right-click and inspect shortcuts)
 */
function initSecurityFeatures() {
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable common inspect shortcuts
    document.addEventListener('keydown', function(e) {
        // F12
        if (e.key === 'F12') {
            e.preventDefault();
        }
        // Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+Shift+C
        if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i' || e.key === 'J' || e.key === 'j' || e.key === 'C' || e.key === 'c')) {
            e.preventDefault();
        }
        // Ctrl+U (View Source)
        if (e.ctrlKey && (e.key === 'U' || e.key === 'u')) {
            e.preventDefault();
        }
    });
}

/**
 * Smooth scroll for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/**
 * Get site URL
 */
function getSiteUrl() {
    return window.location.protocol + '//' + window.location.host;
}

/**
 * Open quote modal with product name
 */
function openQuoteModal(productName) {
    var modal = new bootstrap.Modal(document.getElementById('quoteModal'));
    var productInput = document.getElementById('quoteProduct');
    if (productInput && productName) {
        productInput.value = productName;
    }
    modal.show();
}
