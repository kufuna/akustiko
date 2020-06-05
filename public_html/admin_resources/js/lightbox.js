function expandPoster() {
  var img = $('.poster-lightbox img')
  img.prop({ src: this.src })
  $('.poster-lightbox').css({ display: 'block', width: img.width() + 'px', height: img.height() + 40 + 'px' })
  $('.poster-lightbox-back').css({ display: 'block' })
  img.load(function() {
    $('.poster-lightbox').css({ display: 'block', width: img.width() + 'px', height: img.height() + 40 + 'px' })
  })
}

function init_lightbox(el) {
  $(function() {
    el.click(expandPoster)
    
    $('.poster-lightbox-back').click(function(e) {
      if(e.originalEvent.target.className == 'poster-lightbox-back') closePoster();
    })
    
    $(document).keydown(function(e) {
      if(e.which == 27) closePoster()
    })
  })
  
  function closePoster() {
    $('.poster-lightbox').css({ display: 'none' })
    $('.poster-lightbox-back').css({ display: 'none' })
  }
}
