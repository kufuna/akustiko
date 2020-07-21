<div id="main-section" class="h-container">
    <div class="text-container">
        <h1><?= $data->home->intro_title ?></h1>
        <div class="text">
            <?= $data->home->intro_sub_title ?>
        </div>
    </div>
    <img src="img/main.svg" alt="">
    <svg xmlns="http://www.w3.org/2000/svg" width="85" height="122" viewBox="0 0 85 122">
        <g id="Group_905" data-name="Group 905" transform="translate(-918 -1078)">
            <rect id="Rectangle_63" data-name="Rectangle 63" width="2" height="100" transform="translate(959 1100)"
                  fill="#182b51"/>
            <rect id="Rectangle_64" data-name="Rectangle 64" width="2" height="45" transform="translate(959 1100)"
                  fill="#26c296"/>
            <text id="დასქროლე" transform="translate(918 1087)" fill="#fff" font-size="12"
                  font-family="BPGLEStudio02Caps, 'BPG LE Studio 02 Caps'">
                <tspan x="0" y="0"><? L('დასქროლე') ?></tspan>
            </text>
        </g>
    </svg>
</div>
<div id="why-section" class="h-container">
    <div style="width: 100%;">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->why_title ?></h2>
            <div class="text">
                <?= $data->home->why_sub_title ?>
            </div>
        </div>
        <div class="container">
            <?php foreach ($data->why as $key => $value): ?>
                <div class="box">
                    <div class="rect">
                        <?= file_get_contents(ROOT_URL . 'uploads/why/' . $value->icon) ?>
                    </div>
                    <h3><?= $value->name ?></h3>
                    <div class="text">
                        <?= $value->sub_title ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="services-section" class="h-container">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->services_title ?></h2>
        </div>
        <div class="container">
            <?php foreach ($data->services as $key => $value): ?>
                <div class="box">
                    <div class="rect">
                        <?= file_get_contents(ROOT_URL . 'uploads/services/' . $value->icon) ?>
                    </div>
                    <h3><?= $value->name ?></h3>
                    <div class="text">
                        <?= $value->sub_title ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="container">
            <div class="text">

                უამრავი ვერსია არსებობს. მათი დიდი ნაწილი ხუმრობით არის შეცვლილი, ბევრი კი უბრალოდ სიტყვების შემთხვევითი
                გენერირებით დაიწერა. ინტერნეტში არსებული გენერატორები, როგორც წესი, წინასწარ განსაზღვრული ტექსტის
                ნაგლეჯს იმეორებენ ხოლმე, ამიტომ ეს პირველი ნამდვილი გენერატორია.

                უამრავი ვერსია არსებობს. მათი დიდი ნაწილი ხუმრობით არის შეცვლილი, ბევრი კი უბრალოდ სიტყვების შემთხვევითი
                გენერირებით დაიწერა. ინტერნეტში არსებული გენერატორები, როგორც წესი, წინასწარ განსაზღვრული ტექსტის
                ნაგლეჯს იმეორებენ ხოლმე, ამიტომ ეს პირველი ნამდვილი გენერატორია.

            </div>
        </div>
    </div>
</div>

<div id="projects-section" class="h-container">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->projects_title ?></h2>
            <div class="text">
                <?= $data->home->projects_sub_title ?>
            </div>
            <a href="<?= SITE_URL . 'projects' ?>">
                <span><?=L('იხილეთ ყველა პროექტი')?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="11.225" height="18.165" viewBox="0 0 11.225 18.165">
                    <path id="Path_740" data-name="Path 740"
                          d="M16.023,48.907,9.083,55.848,2.142,48.907,0,51.049l9.083,9.083L13.5,55.719l4.67-4.67Z"
                          transform="translate(-48.907 18.165) rotate(-90)" fill="#26c296"/>
                </svg>
            </a>
        </div>
        <div class="container">
            <div class="swiper-container projects-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($data->projects as $key => $value): ?>
                        <div class="swiper-slide">
                            <a href="<?= SITE_URL . 'project/' . URL::escapeUrl($value->title) . '/' . $value->id ?>">
                                <div class="img">
                                    <img src="<?= ROOT_URL.'uploads/projects/'.$value->image_outer ?>" alt="">
                                </div>
                                <h3><?= $value->title ?></h3>
                            </a>
                        <div class="text"><?= $value->sub_title ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="news-section" class="h-container news-section">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->news_title ?></h2>
            <div class="text">
                <?= $data->home->news_sub_title ?>
            </div>
            <a href="<?= SITE_URL . 'news' ?>">
                <span><?= L('იხილეთ ყველა სიახლე') ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="11.225" height="18.165" viewBox="0 0 11.225 18.165">
                    <path id="Path_740" data-name="Path 740"
                          d="M16.023,48.907,9.083,55.848,2.142,48.907,0,51.049l9.083,9.083L13.5,55.719l4.67-4.67Z"
                          transform="translate(-48.907 18.165) rotate(-90)" fill="#26c296"/>
                </svg>
            </a>
        </div>
        <div class="container">
            <div class="swiper-container projects-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($data->news as $key => $value): ?>
                        <div class="swiper-slide">
                            <a href="<?= SITE_URL . 'article/' . URL::escapeUrl($value->title) . '/' . $value->id ?>">
                                <div class="img">
                                    <img src="<?= ROOT_URL.'uploads/news/'.$value->image_outer ?>" alt="">
                                </div>
                                <h3><?= $value->title ?></h3>
                                <div class="text"><?= $value->short_text ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="faq-section" class="h-container">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim" style="color: #182B51"><?= $data->home->faq_title ?></h2>
            <div class="text">
                <?= $data->home->faq_sub_title ?>
            </div>
        </div>
        <div class="container">
            <?php foreach ($data->faq as $key => $value): ?>
                <div class="question-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20.083" height="9.834" viewBox="0 0 20.083 9.834">
                        <g id="Group_67" data-name="Group 67" transform="translate(18.676 1.407) rotate(90)">
                            <line id="Line_18" data-name="Line 18" x2="7.021" y2="8.635" fill="none" stroke="#182b51"
                                  stroke-linecap="round" stroke-width="2"/>
                            <line id="Line_19" data-name="Line 19" y1="8.635" x2="7.021" transform="translate(0 8.635)"
                                  fill="none" stroke="#182b51" stroke-linecap="round" stroke-width="2"/>
                        </g>
                    </svg>
                    <div class="question">
                        <?= $value->name ?>
                    </div>
                    <div class="answer">
                        <?= $value->text ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="team-section" class="h-container">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->team_title ?></h2>
            <div class="text">
                <?= $data->home->team_sub_title ?>
            </div>
        </div>
        <div class="container">
            <?php foreach ($data->team as $key => $value): ?>
                <div class="box">
                    <div class="img">
                        <img src="<?= ROOT_URL . 'uploads/team/' . $value->image ?>" alt="">
                    </div>
                    <h3><?= $value->name ?></h3>
                    <h4><?= $value->sub_title ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="statistic-section" class="h-container">
    <div style="width: 100%">
        <div class="heading">
            <h2 class="kfn_anim"><?= $data->home->statistics_title ?></h2>
            <div class="text">
                <?= $data->home->statistics_sub_title ?>
            </div>
        </div>
        <div class="container kfn_anim">
            <?php foreach ($data->statistics as $key => $value): ?>
                <div class="box">
                    <h2 data-count="<?= $value->number ?>"><?= $value->number ?></h2>
                    <h3><?= $value->name ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>