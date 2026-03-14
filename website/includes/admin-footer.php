<?php $currentAdminPage = basename($_SERVER['PHP_SELF'], '.php'); ?>
<?php if ($currentAdminPage !== 'login'): ?>
        </div><!-- /.admin-content -->
    </div><!-- /.admin-main -->
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/website/assets/js/admin.js"></script>
</body>
</html>
