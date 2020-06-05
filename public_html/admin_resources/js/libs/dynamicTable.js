function initDynamicTablePlugin() {
  oTable = $(".dTable").dataTable({
     "bJQueryUI": false,
     "bAutoWidth": false,
     "sPaginationType": "full_numbers",
     "sDom": '<"H"fl>t<"F"ip>',
     "order": []
   });
   
   $(".dTable").on( 'draw', function() {
    setTimeout(initActivateEvents, 100);console.log('asd')
   });
 
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
    
    $('.dynamictable-row-activator').unbind('change')
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
  initActivateEvents();
}
