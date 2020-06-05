function initDynamicTablePlugin(columnFilter) {
  if(columnFilter) {
    $('.dTable tfoot th').each( function () {
        var title = $(this).text();
        
        if(title == 'actions' || title == 'active') {
          $(this).text( '' )
        } else {
          $(this).html( '<input type="search" placeholder="Search '+title+'" />' );
        }
    } );
  }

  var rows_selected = [];
  
  oTable = $(".dTable")
    .on( 'order.dt',  onDraw )
    .on( 'search.dt', onDraw )
    .on( 'page.dt',   onDraw )
    .DataTable({
      "bJQueryUI": false,
      "bAutoWidth": false,
      "sPaginationType": "full_numbers",
      "sDom": '<"H"fl>t<"F"ip>',
      "order": [],
      'columnDefs': [{
               'targets': 0,
               'searchable':false,
               'orderable':false,
               'width':'1%',
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                   return '<input type="checkbox" class="wtf">';
               }
            }],
      'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('input.wtf[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
      }
    });
  
  if(columnFilter) {
    oTable.columns().every( function () {
      var that = this;

      $( 'input', this.footer() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
            .search( this.value )
            .draw();
        }
      });
    });
  }
   
  function onDraw() {
  setTimeout(initActivateEvents, 100);
  }

  $("#dyn2 .tOptions").click(function () {
     $("#dyn2 .tablePars").slideToggle(200);
   });
 
  $("select, .check, .check :checkbox, input:radio, input:file").uniform();
 
  $(".lightbox").fancybox({ 'padding': 2 });
  
  function initActivateEvents() {
    $('.actBtns.removeBtn').unbind('click')
    $('.actBtns.removeBtn').click(function() {
      if(!confirm('Are you sure?')) return;
      var self = this;
    
      var data = $(self).data();
      
      $.post(data.url, { id: data.id }, function(r) {
        if(r.error) alert(r.error)
        else $(self).parent().parent().parent().remove();
      })
    })
  
    $('.on_off :checkbox, .on_off :radio').iButton({ labelOn: "", labelOff: "" });
    
    $('.dynamictable-row-activator').change(function(e) {
      if(!e.isTrigger) return;
    
      var self = $(this)
      var active = self.prop('checked') ? 1 : 0;
      var itemId = self.data().itemid;
      var url = self.data().url;
      
      $.post(url, { id: itemId, setactive: active }, function(r) {
        if(r.error) {
          alert(r.error);
          self.click()
        }
      })
      
      e.stopImmediatePropagation();
    })
  }

//
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input.wtf[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input.wtf[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input.select_all_wtf[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}


   // Handle click on checkbox
   $('.dTable tbody').on('click', 'input.wtf[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');
      // Get row data
      var data = oTable.row($row).data();

      // Get row ID
      var rowId = data[1];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(!$row.hasClass('selected') && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if ($row.hasClass('selected') && index !== -1){
         rows_selected.splice(index, 1);
      }

      if(!$row.hasClass('selected')){
        // console.log(this.checked)
        $row.addClass('selected');
      } else {
        // console.log(this.checked)
        $row.removeClass('selected');
      }

      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(oTable);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on oTable cells with checkboxes
   $('.dTable').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input.wtf[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input.select_all_wtf[name="select_all"]', oTable.table().container()).on('click', function(e){
      if(this.checked){
         $('.dTable tbody input.wtf[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('.dTable tbody input.wtf[type="checkbox"]:checked').trigger('click');
      }

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle oTable draw event
   oTable.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(oTable);
   });
    
   // Handle form submission event 
   $('#frm-example').on('submit', function(e){

      // console.log('aaa');

      var salePrice = prompt("Please enter sale percentage");
      var form = this;

      // console.log(rows_selected);

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element 
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });

      var url = $(this).data('url');
      var redirect = $(this).data('redirect');
      var _data = $(form).serializeArray();

      _data.push({name: 'sale_price',value: salePrice});
      
      $.ajax({
         type: "POST",
         url: url,
         // dataType: "json",
         data: _data,
         success: function(data)
         {
             window.location.href = redirect;
         }
       });

      e.preventDefault();

      // FOR DEMONSTRATION ONLY     
      
      // Output form data to a console     
      // $('.dTable-console').text($(form).serialize());
       
      // Remove added elements
      $('input[name="id\[\]"]', form).remove();
       
      // Prevent actual form submission
      e.preventDefault();
   });



   $('.sendButton').on('click', function(e){
      var url = $(this).data('href')
      var emails = new Array();
      var formData = { };

      $('table.dataTable tr.selected').each(function(){
        var email = $(this).find('td').eq(2).text();
        emails.push(email);
      })

      formData['emails'] = emails;
      $('.sidePad.sendMessage').text('მეილები იგზავნება გთხოვთ დაიცადოთ')

      $.ajax({
         type: "POST",
         url: url,
         data: formData,
         success: function(data)
         {
            $('.sidePad.sendMessage').text(data)
         }
       });
   })


   $('.saleButton').on('click', function(e){

    // console.log('aaa');

      $('#frm-example').submit();

      e.preventDefault();
   })
// });

  initActivateEvents();

}
