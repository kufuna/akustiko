function initSortable(selector, onChange, orderInput) {
  var moving = false;
  var moved = false;
  var startX = null;
  var startY = null;
  var diffX = null;
  var diffY = null;
  var els = [];
  
  function initEvents() {
    els = $(selector);
    els.unbind('mousedown')
    els.mousedown(function(e) {
      moving = $(this)
      startX = e.pageX
      startY = e.pageY
    })
  }
  initEvents();  
  
  $(document).mouseup(function(e) {
    if(!moving || !moved) {
      moving = false;
      moved = false;
      return;
    };
  
    var minD = 10000;
    var closest = null;
    for(var i = 0; i < els.length; i++) {
      var el = $(els[i])
      var d = findDistance(el, moving)
      if(d < minD) {
        minD = d;
        closest = el;
      }
    }
  
    moving.css({ left: 0, top: 0, zIndex: 0 });
    (function(el) {
      setTimeout(function() {
        el.data().moving = false;
      }, 100)
    })(moving)
    
    if(closest && closest[0] != moving[0]) {
      var flag = $('<span>')
      var movingData = moving.data()
      var closestData = closest.data()
      flag.insertAfter(moving)
      moving.remove();
      moving.insertBefore(closest)
      closest.remove()
      closest.insertBefore(flag)
      flag.remove()
      moving.data(movingData)
      closest.data(closestData)
      initEvents();
      calculateReorders();
      onChange && onChange();
    }
    
    startX = null;
    startY = null;
    moving = false;
    moved = false;
  }).mousemove(drag)
  
  function drag(e) {
    if(!moving) return;
    
    moving.data().moving = true;
    
    diffX = e.pageX - startX;
    diffY = e.pageY - startY;
    
    moving.css({ left: diffX, top: diffY, zIndex: 10 })
    
    moved = true;
  }
  
  function findDistance(a, b) {
    var ao = a.offset();
    var bo = b.offset();
  
    if(a[0] == b[0]) {
      var x = Math.abs(diffX)
      var y = Math.abs(diffY)
    } else {
      var x = Math.abs(ao.left - bo.left);
      var y = Math.abs(ao.top - bo.top);
    }
  
    return x + y;
  }
  
  function calculateReorders() {
    els = $(selector)
    var result = [];
    for(var i = 0; i < els.length; i++) {
      var el = $(els[i]);
      
      if(el.data().new) result.push({ new: el.data().ord })
      else result.push({ old: el.data().ord })
    }
    
    result = JSON.stringify(result);
    orderInput.val(result)
  }
  
  return {
    reInitEvents: initEvents,
    calculateReorders: calculateReorders
  }
}
