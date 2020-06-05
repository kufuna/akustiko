function initPhotoUpload(el, photoContainer, cropName, onChange, maxCount) {
  var mainPhotoCrop = null;
  var lastJcrop = null;
  var currentImage = null;
  
  var chosenImage;
  var cropInput;
  var photoContainer;
  
  var lightboxBack, lightboxImg, lightboxDiv, lightboxCropBtnWrapper, lightboxCropBtn
  
  maxCount = maxCount || 5;
  cropName = cropName === false ? false : (cropName || 'photo_crop');
  
  /* Create elements */
  function createElements() {
    lightboxBack = $('<div>').addClass('poster-lightbox-back')
    lightboxImg = $('<img>')
    
    lightboxCropBtn = $('<div>').addClass('grid5').css({ marginBottom: '10px' }).append(
      $('<input>').prop({ type: 'submit', value: 'OK' }).addClass('buttonS bLightBlue crop-photo-btn')
    )
    var clear = $('<div>').addClass('clear')
    lightboxCropBtnWrapper = $('<div>').addClass('formRow noBorderB').append(lightboxCropBtn).append(clear)
    
    lightboxDiv = $('<div>').addClass('poster-lightbox').append(lightboxImg).append(lightboxCropBtnWrapper)
    $('body').append(lightboxBack).append(lightboxDiv)
  }
  createElements();
  
  /* init events */
  function initEvents() {
    var imgs = photoContainer.children('.photo-preview').children('img')
    imgs.unbind('click')
    imgs.click(expandPoster);
  }
  initEvents();
  
  lightboxBack.click(function(e) {
    if(e.originalEvent.target.className == 'poster-lightbox-back') closePoster();
  })
  
  $(document).keydown(function(e) {
    if(e.which == 27) closePoster()
  })
  
  lightboxCropBtn.click(function() {
    smartCrop(mainPhotoCrop);
    closePoster();
  })

  $(el).change(function() {
    if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
      alert('The File APIs are not fully supported in this browser.');
      return;
    }

    if(this.files.length < 1) return false;
    for(var i = 0; i < this.files.length; i++) {
      var f = this.files[i];
      photoContainer.children('.photo-preview:not(.no-remove)').remove()
      
      var reader = new FileReader();
      
      reader.onload = (function(theFile, ord) {
        return function(e) {
          if(photoContainer.children('.photo-preview').length == maxCount) {
            return alert('დასაშვებია მხოლოდ ' + maxCount + ' დამატებითი ფოტოს ატვირთვა');
          }
        
          chosenImage = $('<div>').addClass('file').prop({  draggable: false, title: escape(theFile.name) }).text(theFile.name)
          cropInput = $('<input />').prop({ type: 'hidden', name: cropName })
          preview = $('<div>').addClass('photo-preview files').data({ ord: ord, new: true }).append(chosenImage).append(cropInput)
          var clear = photoContainer.children('.clear');
          if(clear.length) preview.insertBefore(clear)
          else photoContainer.append(preview)
          onChange && onChange();
          initEvents();
        };
      })(f, i);

      reader.readAsDataURL(f);
    }
  })

  function expandPoster() {
    if(cropName === false) return;
  
    currentImage = $(this);
    if(currentImage.parent().data().moving) return; // for sortable
  
    lightboxImg.remove()
    lightboxDiv.find('.jcrop-holder').remove()
    
    var img = $('<img>');
    img.insertBefore(lightboxCropBtnWrapper)
    img.prop({ src: this.src })
    lightboxDiv.css({ display: 'block', width: img.width() + 'px', height: img.height() + 60 + 'px' })
    lightboxBack.css({ display: 'block' })
    img.load(function() {
      lightboxDiv.css({  width: img.width() + 'px', height: img.height() + 60 + 'px' })

      img.Jcrop({
        onSelect: onCropSelect
      });
    })
  }

  function onCropSelect(c){
    var bounds = this.getBounds();
    lastJcrop = this;

    mainPhotoCrop = {
      w: c.w,
      h: c.h,
      x: c.x, 
      y: c.y, 
      x2: c.x2,
      y2: c.y2,
      W: bounds[0],
      H: bounds[1]
    };
  }
  
  function closePoster() {
    lightboxDiv.css({ display: 'none' })
    lightboxBack.css({ display: 'none' })
  }

  function smartCrop(cfg) {
    if(!currentImage) return;
  
    var currentPreviewDiv = currentImage.parent();
    var currentCropInput = currentPreviewDiv.children('input')
    var img = $('<img>')
    img.prop({ src: currentImage.prop('src') }).css({ position: 'absolute', zIndex: -1, top: '-5000px' })
    $('body').append(img)
    img.load(function() {
      var RW = img.width();
      var RH = img.height();
      var frameH = currentPreviewDiv.height();
      
      var scale = RW / cfg.W;
      cfg.w = Math.round(cfg.w * scale);
      cfg.h = Math.round(cfg.h * scale);
      cfg.x = Math.round(cfg.x * scale);
      cfg.y = Math.round(cfg.y * scale);
      cfg.x2 = Math.round(cfg.x2 * scale);
      cfg.y2 = Math.round(cfg.y2 * scale);
      
      var previewReduceScale = cfg.h / frameH;
      var imageHeight = RH / previewReduceScale;
      var frameWidth = cfg.w / previewReduceScale;
      var imageX = -1 * cfg.x / previewReduceScale;
      var imageY = -1 * cfg.y / previewReduceScale;
      currentImage.css({ height: imageHeight + 'px', left: imageX + 'px', top: imageY + 'px' })
      currentPreviewDiv.css({ width: frameWidth + 'px' })
      
      currentCropInput.val(JSON.stringify(cfg))
      
      currentImage = null;
    })
  }
  
  return {
    reInitEvents: initEvents
  }
}
