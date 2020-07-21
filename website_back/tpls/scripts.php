<?php
if (!DEBUG) {
    echo '<script src="js/min/all.js"></script>';
} else { ?>
    <script src="js/libs/jquery.js"></script>
    <script src="js/custom/swiper.min.js"></script>
    <script src="js/libs/lightgallery.min.js"></script>
    <script src="js/libs/lg-thumbnail.js"></script>
    <script src="js/custom/countUp.min.js"></script>
    <script src="js/custom/kfn.js"></script>
    <script src="js/custom/functions.js"></script>
<?php } ?>
<script>
    siteUrl = "<?= SITE_URL ?>";
</script>
