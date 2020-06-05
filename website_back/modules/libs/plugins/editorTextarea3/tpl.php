<div class="widget" style="margin: 19px 16px;">
  <div class="whead"><h6><?= $cfg->label ?></h6><div class="clear"></div></div>
  <textarea id="<?= $cfg->id ?>" name="<?= $cfg->field_name ?>" cols="<?= $cfg->cols ?>" rows="<?= $cfg->rows ?>"><?= $cfg->value ?></textarea>
</div>

<!-- <script type="text/javascript" src="js/tinymce/all.min.js"></script> -->
<!-- <link type="text/css" rel="stylesheet" href="js/tinymce/all.min.css" /> -->

<!-- <script type="text/javascript" src="js/tinymce3/tiny_mce.js"></script> -->

<script type="text/javascript">

  $(function() {
    loadScript(["js/tinymce3/tiny_mce.js" ], onload);
    
    function onload() {
      setTimeout(initTinyMce, 500)
    }
    
    function initTinyMce() {
      tinyMCE.init({
        mode : "exact",
        elements : '<?= $cfg->id ?>',
        theme : "advanced",
        skin : "o2k7",
        relative_urls : false,
        remove_script_host: false,
        convert_urls: false,
        //escape p
        force_br_newlines : true,
          force_p_newlines : false,
          forced_root_block : '',
        skin_variant : "silver",
        plugins : "imagemanager,filemanager,safari,pagebreak,style,layer,table,template,save,advhr,advimage,advlink,iespell,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,cut,copy,paste,pastetext,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,insertfile,cleanup,code,|,preview,|,sub,sup,|,charmap,iespell,media,advhr,|,ltr,rtl,|,fullscreen,|,forecolor,backcolor,table, tablecontrols, template",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
        theme_advanced_resizing_use_cookie : false,
        template_templates : [
          {
                  title : "Lessons Price Table",
                  src : "/admin_resources/js/tinymce3/templates/lessons_price_table.html",
                  // description : ""
          }
        ]
      });
    }
  })


// window.staticInc = window.staticInc || 0;
// window.staticInc++;

// setTimeout(function() {
//   tinymce.PluginManager.load('moxiecut', '<?= ROOT_URL.'admin_resources/' ?>js/tinymce/plugins/moxiecut/plugin.min.js');
//   tinymce.init({
//       selector: "textarea#<?= $cfg->id ?>",
//       theme : "advanced",
//         plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",

//         // Theme options
//         theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
//         theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
//         theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//         theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
//         theme_advanced_toolbar_location : "top",
//         theme_advanced_toolbar_align : "left",
//         theme_advanced_statusbar_location : "bottom",
//         theme_advanced_resizing : true,

//         // Skin options
//         skin : "o2k7",
//         skin_variant : "silver",

//         // Example content CSS (should be your site CSS)
//         content_css : "css/example.css",

//         // Drop lists for link/image/media/template dialogs
//         template_external_list_url : "js/template_list.js",
//         external_link_list_url : "js/link_list.js",
//         external_image_list_url : "js/image_list.js",
//         media_external_list_url : "js/media_list.js",

//         // Replace values for the template plugin
//         template_replace_values : {
//                 username : "Some User",
//                 staffid : "991234"
//         }
//   });
// }, window.staticInc * 1000);
</script>
