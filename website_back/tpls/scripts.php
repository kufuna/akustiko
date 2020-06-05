<?php
if (!DEBUG) {
    echo '<script src="js/min/all.js"></script>';
} else { ?>
    <script src="js/libs/jquery.js"></script>
    <script src="js/libs/jquery.mobile.js"></script>
    <script src="js/libs/wavesurfer.min.js"></script>
    <script src="js/libs/wavesurfer.cursor.js"></script>
    <script src="js/libs/swiper.min.js"></script>
    <script src="js/libs/countUp.min.js"></script>
    <script src="js/libs/lightgallery.min.js"></script>
    <script src="js/libs/parallax.min.js"></script>
<?php } ?>
<script>
    siteUrl = "<?= SITE_URL ?>";
</script>
