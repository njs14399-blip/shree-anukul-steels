<?php
/**
 * Shree Anukul Steels - Projects Page
 */
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Get project categories
$categories = $db->query('SELECT * FROM project_categories ORDER BY name ASC')->fetchAll();

// Get selected category
$selectedCategory = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Get projects
if (!empty($selectedCategory)) {
    $stmt = $db->prepare('SELECT p.*, pc.name as category_name FROM projects p LEFT JOIN project_categories pc ON p.category_id = pc.id WHERE p.status = 1 AND pc.slug = ? ORDER BY p.created_at DESC');
    $stmt->execute([$selectedCategory]);
} else {
    $stmt = $db->query('SELECT p.*, pc.name as category_name FROM projects p LEFT JOIN project_categories pc ON p.category_id = pc.id WHERE p.status = 1 ORDER BY p.created_at DESC');
}
$projects = $stmt->fetchAll();
?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Our Construction Projects</h1>
            <p>Showcasing Projects Built With Premium Steel From Shree Anukul Steels</p>
            <nav aria-label="breadcrumb" class="mt-3">
                <ol class="breadcrumb breadcrumb-custom mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Projects</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="section-padding">
        <div class="container">
            <!-- Category Filter -->
            <div class="category-filter mb-4" data-aos="fade-up">
                <a href="<?php echo SITE_URL; ?>/projects.php" class="btn <?php echo empty($selectedCategory) ? 'btn-primary' : 'btn-outline-primary'; ?>">All Projects</a>
                <?php foreach ($categories as $cat): ?>
                <a href="<?php echo SITE_URL; ?>/projects.php?category=<?php echo urlencode($cat['slug']); ?>" class="btn <?php echo $selectedCategory === $cat['slug'] ? 'btn-primary' : 'btn-outline-primary'; ?>"><?php echo sanitize($cat['name']); ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Projects Grid -->
            <div class="row g-4">
                <?php if (empty($projects)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-building" style="font-size:3rem;color:var(--gray-300);"></i>
                    <p class="text-muted mt-3">No projects found in this category.</p>
                </div>
                <?php else: ?>
                <?php foreach ($projects as $project): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="project-card">
                        <div class="project-card-img">
                            <?php if (!empty($project['image']) && file_exists(UPLOAD_PATH . $project['image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/website/uploads/<?php echo sanitize($project['image']); ?>" alt="<?php echo sanitize($project['name']); ?> - Construction Project" loading="lazy">
                            <?php else: ?>
                                <div class="img-placeholder" style="width:100%;height:100%;"><i class="bi bi-building" style="font-size:3rem;color:var(--gray-300);"></i></div>
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
                            <?php if (!empty($project['description'])): ?>
                            <p class="text-muted mt-2 mb-0" style="font-size:0.9rem;"><?php echo sanitize(substr($project['description'], 0, 150)); ?><?php echo strlen($project['description']) > 150 ? '...' : ''; ?></p>
                            <?php endif; ?>
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
            <h2>Have a Construction Project?</h2>
            <p class="mb-4">Get premium quality steel products at competitive prices for your next project.</p>
            <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-light btn-lg me-2">Contact Us</a>
            <a href="tel:+918981040333" class="btn btn-outline-light btn-lg"><i class="bi bi-telephone-fill me-2"></i>Call Now</a>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
