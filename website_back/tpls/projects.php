<div id="filter" style="display: none;" class="container">
    <form method="get" onsubmit="return filterData();">
        <input type="hidden" name="services">
        <input type="hidden" name="sectors">
        <div class="side">
            <h2 class="noto-bold"><?= L( 'სექტორი' ) ?></h2>
            <div class="checkboxes">
                <?php foreach ( array_chunk( $data->sectors, 3 ) as $key => $value ): ?>
                    <div class="item">
                        <?php foreach ( $value as $k => $v ): ?>
                            <div class="checkbox">
                                <input type="checkbox" name="sector" value="<?= $v->id ?>">
                                <div class="box">
                                    <div class="rect">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12.569" height="9.373"
                                             viewBox="0 0 12.569 9.373">
                                            <path id="Icon_awesome-check" data-name="Icon awesome-check"
                                                  d="M4.269,13.766.184,9.681a.628.628,0,0,1,0-.889L1.073,7.9a.628.628,0,0,1,.889,0l2.752,2.752,5.894-5.894a.628.628,0,0,1,.889,0l.889.889a.628.628,0,0,1,0,.889L5.158,13.766A.628.628,0,0,1,4.269,13.766Z"
                                                  transform="translate(0 -4.577)" fill="#fff"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="noto-regular"><?= $v->name ?></h3>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <div class="stick"></div>
            </div>

        </div>
        <div class="side">
            <h2 class="noto-bold"><?= L( 'სერვისები' ) ?></h2>
            <div class="checkboxes">
                <?php foreach ( array_chunk( $data->sectors, 3 ) as $key => $value ): ?>
                    <div class="item">
                        <?php foreach ( $value as $k => $v ): ?>
                            <div class="checkbox">
                                <input type="checkbox" name="service" value="<?= $v->id ?>">
                                <div class="box">
                                    <div class="rect">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12.569" height="9.373"
                                             viewBox="0 0 12.569 9.373">
                                            <path id="Icon_awesome-check" data-name="Icon awesome-check"
                                                  d="M4.269,13.766.184,9.681a.628.628,0,0,1,0-.889L1.073,7.9a.628.628,0,0,1,.889,0l2.752,2.752,5.894-5.894a.628.628,0,0,1,.889,0l.889.889a.628.628,0,0,1,0,.889L5.158,13.766A.628.628,0,0,1,4.269,13.766Z"
                                                  transform="translate(0 -4.577)" fill="#fff"/>
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="noto-regular"><?= $v->name ?></h3>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button class="noto-bold" type="submit"><?= L( 'გაფილტვრა' ) ?></button>
    </form>
</div>

<div class="projects--container containerr">
    <div class="header">
        <h2 class="noto-bold"><?= L( 'პროექტები' ) ?></h2>
        <a href="" id="open-filter">
            <svg id="magnifying-search-lenses-tool" xmlns="http://www.w3.org/2000/svg" width="15.988" height="15.99"
                 viewBox="0 0 15.988 15.99">
                <path id="Path_640" data-name="Path 640"
                      d="M15.666,14.1l-3.379-3.38A6.776,6.776,0,0,0,6.78,0,6.78,6.78,0,0,0,0,6.779a6.771,6.771,0,0,0,10.723,5.507l3.379,3.38A1.105,1.105,0,1,0,15.666,14.1ZM3.55,10.01a4.569,4.569,0,1,1,3.23,1.338A4.54,4.54,0,0,1,3.55,10.01Z"
                      transform="translate(-0.001 0)" fill="#26c296"/>
            </svg>
            <span class="noto-bold"><?= L( 'ფილტრი' ) ?></span>
        </a>
    </div>
    <div class="item--container">
        <?php foreach ( $data->projects as $key => $value ): ?>
            <div class="item">
                <a href="<?= SITE_URL . 'project/' . URL::escapeUrl( $value->title ) . '/' . $value->id ?>">
                    <div class="image">
                        <picture>
                            <img src="<?= ROOT_URL . 'uploads/projects/' . $value->image_outer ?>" class="" alt="">
                        </picture>
                    </div>
                    <h2 class="noto-bold"><?= $value->title ?></h2>
                    <h3 class="noto-regular"><?= $value->sub_title ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include( 'pagination.php' ) ?>
</div>