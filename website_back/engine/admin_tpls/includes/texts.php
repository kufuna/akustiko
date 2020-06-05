<div class="row">
    <div class="col-xl-12">
        <div id="panel-12" class="panel">
            <div class="panel-hdr">
                <h2>
                    Edit Texts
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="panel-tag">
                        In this section you can modify text that are used in this application
                    </div>
                    <table class="table m-0 table-bordered table-hover" id="lang_texts_table">
                        <thead>
                            <tr>
                                <th>Text</th>
                                <th class="text-actions">Action</th>
                                <th><?= $CFG['LANG_NAMES'][$data->activeLang] ?> Translation</th>
                                <th class="text-actions">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->allLangs as $k => $v) { ?>
                            <tr class="lang-edit-col">
                                <td>
                                    <div class="form-group">
                                        <textarea class="form-control key-prop noneditable edit-text" readonly=""><?= $k ?></textarea>
                                    </div>
                                </td>
                                <td class="text-action-btn">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-icon waves-effect waves-themed copy-lang-text">
                                        <i class="fal fa-chevron-right"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <textarea class="form-control editable right-small primary-text"><?= isset($data->currentLangs->$k) ? $data->currentLangs->$k : ''; ?></textarea>
                                        <div class="saveDiv" style="display: none;">
                                            <a href="javascript:void(0)" style="font-weight:bold" class="btn-save"><?= L('save') ?></a>&nbsp;&nbsp;&nbsp;
                                            <a href="javascript:void(0)" class="btn-cancel"><?= L('cancel') ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-action-btn">
                                    <?php if($data->allowAddDelete) { ?>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-icon waves-effect waves-themed remove-lang-text">
                                        <i class="fal fa-times"></i>
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var copies = document.getElementsByClassName('copy-lang-text');
    for (var i = copies.length - 1; i >= 0; i--) {
        copies[i].addEventListener('click', function() {
            var parent_tr = this.parentNode.parentNode;
            var edited_val = parent_tr.querySelector('.edit-text').value;
            parent_tr.querySelector('.primary-text').value = edited_val.replace(/_/g, ' ');

        })
    }
</script>