function initSortableListPlugin(POSTMODULE_URL, depth, reorderMethodName, removeMethodName, selector) {
  depth = depth || 1;
	reorderMethodName = reorderMethodName || 'reorder';
	removeMethodName = removeMethodName || 'remove';

	var EL = selector ? $(selector) : $('ol.sortable');

  EL.nestedSortable({
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 50,
		tolerance: 'pointer',
		toleranceElement: '> div',
		maxLevels: depth,

		isTree: true,
		expandOnHover: 700,
		startCollapsed: false,
		
		sortChange: function() {
		  setTimeout(sync, 1000)
		}
	});

  EL.find('.disclose').on('click', function() {
		$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
	});

  EL.find('a.del-btn').click(function() {
	  if(confirm('Are you sure?')) {
	    var me = $(this)
	    $.post(POSTMODULE_URL + removeMethodName + '/', { id: me.data().id }, function(r) {
	      if(r.deleted) me.parent().parent().remove();
	    })
	  }
	});
	
  function sync(){
		var serialized = EL.nestedSortable('toHierarchy', {startDepthCount: 0});
		$.post(POSTMODULE_URL + reorderMethodName + '/', { data: JSON.stringify(serialized), post: true }, function(r) {
      if(r.error) alert(r.error)
    });
	}
}