
<div class="projects--container containerr">
    <div class="header">
        <h2 class="noto-bold"><?= L( 'სიახლეები' ) ?></h2>
    </div>
    <div class="item--container">
        <?php foreach ($data->news as $key => $value): ?>
            <div class="item">
                <a href="<?= SITE_URL . 'article/' . URL::escapeUrl($value->title) . '/' . $value->id ?>">
                    <div class="image">
                        <picture>
                            <img src="<?= ROOT_URL.'uploads/news/'.$value->image_outer ?>" class="" alt="">
                        </picture>
                    </div>
                    <h2 class="noto-bold"><?= $value->title ?></h2>
                    <h3 class="noto-regular"><?= $value->short_text ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include('pagination.php') ?>
</div>


