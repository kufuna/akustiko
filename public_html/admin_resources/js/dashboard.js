$(function() {
	$(".nNote").click(function() {
		$(this).fadeTo(200, 0.00, function(){ //fade
			$(this).slideUp(200, function() { //slide up
				$(this).remove(); //then remove from the DOM
			});
		});
	});
	
	//===== Left navigation styling =====//
	$('.subNav li a.this').parent('li').addClass('activeli');

	//===== User nav dropdown =====//
	$('a.leftUserDrop').click(function () {
		$('.leftUser').slideToggle(200);
	});
	
	// USERS //
	$('.btn-remove-user').click(function() {
	  var tr = $(this).parent().parent()
	    if(confirm('გსურთ წაშალოთ არჩეული მომხმარებელი?')) {
	      var data = { delete: tr.data().id }
	    
	      $.post(location.href, data, function(r) {
	        if(r.success == true) {
	          tr.remove()
	        } else {
	          alert('დაფიქსირდა შეცდომა, სცადეთ მოგვიანებით ან მიმართეთ ადმინისტრაციას')
	        }
	      })
	    }
	})
	
	// Add new lang text //
	$('.add-lang-text').click(function() {
	  var table = $('#lang_texts_table')
	
	  var tr = $('<tr>').html('<td valign="top">\
          <textarea class="editable key-prop"></textarea>\
        </td>\
        <td valign="top">\
          <textarea class="editable right-small"></textarea>\
          <div class="saveDiv" style="display: none;">\
            <a href="javascript:void(0)" style="font-weight:bold" class="btn-save">Save</a>&nbsp;&nbsp;&nbsp;\
            <a href="javascript:void(0)" class="btn-cancel">Cancel</a>\
          </div>\
          <td class="tableActs noBorderB" style="width: 20px">\
				    <a href="javascript:void(0)" class="tablectrl_small bDefault tipS remove-lang-text" original-title="წაშლა"><span class="iconb" data-icon=""></span></a>\
			    </td>\
        </td>')
        
    table.append(tr)
    
    initLangTextEvents();
	})
	
	initLangTextEvents();
});

function initLangTextEvents() {
  //===== ULang edits =====//
  $('textarea.editable').unbind('focus')
	$('textarea.editable').focus(function() {
	  var table = $('#lang_texts_table')
	  // table.find('textarea').css({ height: 'auto' })
	  table.find('.saveDiv').css({ display: 'none' })
	  
	  var tr = $(this).parent().parent()
	  // tr.find('textarea').css({ height: '182px' })
	  // tr.find('textarea.right-small').css({ height: '155px' })
	  tr.find('.saveDiv').css({ display: 'block' })
	})
	
	$('.saveDiv a.btn-save').unbind('click')
	$('.saveDiv a.btn-save').click(function() {
	  var tr = $(this).closest('.lang-edit-col');
	  var table = $('#lang_texts_table')
	  var data = { key: tr.find('textarea.key-prop').val(), value: tr.find('textarea.right-small').val() }
	  console.log(data);
	  $.post(location.href, data, function(r) {
	    if(r.success == true) {
	      // table.find('textarea').css({ height: '27px' })
	      table.find('.saveDiv').css({ display: 'none' })
	      tr.find('textarea.key-prop').addClass('noneditable').prop({ readonly: 'readonly' })
	    } else {
	      alert('An error has been occured, please try again later or contact your administrator.');
	    }
	  })
	})
	
	$('.saveDiv a.btn-cancel').unbind('click')
	$('.saveDiv a.btn-cancel').click(function() {
	  var table = $('#lang_texts_table')
	  // table.find('textarea').css({ height: '27px' })
	  table.find('.saveDiv').css({ display: 'none' })
	})
	
	$('.remove-lang-text').unbind('click')
	$('.remove-lang-text').click(function() {
	  var tr = $(this).closest('.lang-edit-col');
	  if(confirm('Do you want to delete the selected record in all languages?')) {
	    var data = { delete: tr.find('textarea.key-prop').val() }
	  
	    $.post(location.href, data, function(r) {
	      if(r.success == true) {
	        tr.remove()
	      } else {
	        alert('An error has been occured, please try again later or contact your administrator.')
	      }
	    })
	  }
	})
}

	
