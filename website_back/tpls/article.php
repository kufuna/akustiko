<div class="inner-page">
    <div class="container">
        <div class="image">
            <picture>
                <source media="(max-width:767px)" srcset="<?= ROOT_URL.'uploads/news/'.$data->article->mobile_image ?>">
                <source media="(max-width:1023px)" srcset="<?= ROOT_URL.'uploads/news/'.$data->article->tablet_image ?>">
                <img src="<?= ROOT_URL.'uploads/news/'.$data->article->image ?>" class="" alt="">
            </picture>
        </div>
        <div class="text-container">
            <h1 class="noto-bold"><?= $data->article->title ?></h1>
            <div class="text">
                <?= $data->article->text ?>
            </div>
            <a onclick="history.back()" style="cursor:pointer;" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" width="17.747" height="18.99" viewBox="0 0 17.747 18.99">
                    <g id="Icon_feather-arrow-right" data-name="Icon feather-arrow-right" transform="translate(1.5 2.121)">
                        <path id="Path_743" data-name="Path 743" d="M22.247,18H7.5" transform="translate(-7.5 -10.626)" fill="none" stroke="#26c296" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                        <path id="Path_744" data-name="Path 744" d="M25.374,7.5,18,14.874l7.374,7.374" transform="translate(-18 -7.5)" fill="none" stroke="#26c296" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                    </g>
                </svg>
                <span class="noto-bold"><?=L('უკან')?></span>
            </a>
            <div class="stick"></div>
        </div>
    </div>
</div>


<?php
$gallery = $data->article->gallery
    ? ( json_decode( $data->article->gallery, true ) ? json_decode( $data->article->gallery, true ) : null )
    : null;
?>

<?php if ( $gallery ): ?>
    <div class="gallery-container container">
        <div class="wrapper">
            <div class="header">
                <h2 class="noto-bold"><?= L( 'გალერეა' ) ?></h2>
            </div>
            <div class="swiper-container" id="lightgallery">
                <div class="swiper-wrapper">
                    <?php foreach ( $gallery as $key => $value ): ?>
                        <div class="swiper-slide">
                            <a href="<?= ROOT_URL . 'uploads/news/' . $value ?>" class="lg">
                                <div class="image">
                                    <picture>
                                        <img src="<?= ROOT_URL . 'uploads/news/' . $value ?>" class="" alt="">
                                    </picture>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                                        <g id="Group_2462" data-name="Group 2462"
                                           transform="translate(5355.365 -896.365) rotate(90)">
                                            <rect id="Rectangle_187" data-name="Rectangle 187" width="4" height="40"
                                                  rx="2" transform="translate(936.365 5333.365) rotate(90)"
                                                  fill="#fff"/>
                                            <rect id="Rectangle_191" data-name="Rectangle 191" width="4" height="40"
                                                  rx="2" transform="translate(918.365 5355.365) rotate(180)"
                                                  fill="#fff"/>
                                        </g>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="projects--container containerr">
    <div class="header">
        <h2 class="noto-bold"><?= L( 'სიახლეები' ) ?></h2>
        <a href="<?= SITE_URL.'news' ?>" class="all">
            <span class="noto-bold"><?= L( 'ყველა' ) ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="17.747" height="18.99" viewBox="0 0 17.747 18.99">
                <g id="Icon_feather-arrow-right" data-name="Icon feather-arrow-right" transform="translate(-6 -5.379)">
                    <path id="Path_743" data-name="Path 743" d="M7.5,18H22.247" transform="translate(0 -3.126)"
                          fill="none" stroke="#26c296" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                    <path id="Path_744" data-name="Path 744" d="M18,7.5l7.374,7.374L18,22.247"
                          transform="translate(-3.126 0)" fill="none" stroke="#26c296" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="3"/>
                </g>
            </svg>
        </a>
    </div>
    <div class="item--container">
        <?php foreach ( $data->news as $key => $value ): ?>
            <div class="item">
                <a href="<?= SITE_URL . 'news/' . URL::escapeUrl($value->title) . '/' . $value->id ?>">
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
</div>