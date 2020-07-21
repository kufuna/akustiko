
<div class="pagination">
    <?php foreach ($data->paging->switchers as $switcher): ?>
        <?php if ($switcher->type == 'prev') { ?>
            <a href="<?= $switcher->link ?>" class="prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="6.921" height="11.781" viewBox="0 0 6.921 11.781">
                    <path id="Path_1011" data-name="Path 1011"
                          d="M.188,6.35l5.243,5.243a.646.646,0,0,0,.911,0l.386-.386a.645.645,0,0,0,0-.911l-4.4-4.4L6.733,1.486a.646.646,0,0,0,0-.912L6.347.188a.646.646,0,0,0-.911,0L.188,5.436a.65.65,0,0,0,0,.915Z"
                          fill="#0c286e"/>
                </svg>
            </a>
        <?php } ?>
    <?php endforeach ?>

    <div class="pagination-list">
        <?php foreach ($data->paging->switchers as $switcher): ?>
            <?php if ($switcher->type == 'normal') { ?>
                <a href="<?= $switcher->link ?>"
                   class="<?= $switcher->active ? 'active' : '' ?>"><?= $switcher->text ?></a>
            <?php } ?>
        <?php endforeach ?>
    </div>

    <?php foreach ($data->paging->switchers as $switcher): ?>
        <?php if ($switcher->type == 'next') { ?>

            <a href="<?= $switcher->link ?>" class="next active">
                <svg xmlns="http://www.w3.org/2000/svg" width="6.921" height="11.781" viewBox="0 0 6.921 11.781">
                    <path id="Path_1012" data-name="Path 1012"
                          d="M6.733,6.35,1.491,11.593a.646.646,0,0,1-.911,0l-.386-.386a.645.645,0,0,1,0-.911l4.4-4.4L.188,1.486a.646.646,0,0,1,0-.912L.574.188a.646.646,0,0,1,.911,0L6.733,5.436a.65.65,0,0,1,0,.915Z"
                          fill="#0c286e"/>
                </svg>
            </a>
        <?php } ?>
    <?php endforeach ?>
</div>