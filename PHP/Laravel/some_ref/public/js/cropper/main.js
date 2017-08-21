//   https://github.com/fengyuanchen/cropper

$(function () {

  'use strict';

  var console = window.console || { log: function () {} };
  var $image = $('#image');
  var $download = $('#download');
  var $dataX = $('#dataX');
  var $dataY = $('#dataY');
  var $dataHeight = $('#dataHeight');
  var $dataWidth = $('#dataWidth');
  var $dataRotate = $('#dataRotate');
  var $dataScaleX = $('#dataScaleX');
  var $dataScaleY = $('#dataScaleY');
  var options = {
        aspectRatio:  35/45, // 16 / 9,
        preview: '.img-preview',
        viewMode: 2,
        autoCropArea : 1,
        background : false, // don't show the grid background in Canvas
        crop: function (e) {
          console.log("crop options : " + " h : " + e.height 
                                        + " w : " + e.width
                                        + " x : " + e.x
                                        + " y : " + e.y
                                        + " scaleX: " + e.scaleX
                                        + " scaleY : " + e.scaleY                                        
                                        );
          
          $dataX.val(Math.round(e.x));
          $dataY.val(Math.round(e.y));
          $dataHeight.val(Math.round(e.height));
          $dataWidth.val(Math.round(e.width));

          $dataRotate.val(e.rotate);
          $dataScaleX.val(e.scaleX);
          $dataScaleY.val(e.scaleY);
        }
      };



  // Tooltip
  $('[data-toggle="tooltip"]').tooltip();


  // Cropper
  $image.on({
    'build.cropper': function (e) {
      console.log(e.type);
      console.log('build.cropper' + e.currentTarget.naturalHeight);
      console.log('build.cropper' + e.currentTarget.naturalWidth);
    },
    'built.cropper': function (e) {
      console.log(e.type);
      console.log('built.cropper' + e.currentTarget.naturalHeight);
      console.log('built.cropper' + e.currentTarget.naturalWidth);

    },
    'cropstart.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'cropmove.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'cropend.cropper': function (e) {
      console.log(e.type, e.action);
    },
    'crop.cropper': function (e) {
      console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
    },
    'zoom.cropper': function (e) {
      console.log(e.type, e.ratio);
    }
  }).cropper(options);


  // Buttons
  if (!$.isFunction(document.createElement('canvas').getContext)) {
    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
  }

  if (typeof document.createElement('cropper').style.transition === 'undefined') {
    $('button[data-method="rotate"]').prop('disabled', true);
    $('button[data-method="scale"]').prop('disabled', true);
  }


  // Download
  if (typeof $download[0].download === 'undefined') {
    $download.addClass('disabled');
  }


  // Options
  $('.docs-toggles').on('change', 'input', function () {
    var $this = $(this);
    var name = $this.attr('name');
    var type = $this.prop('type');
    var cropBoxData;
    var canvasData;

    if (!$image.data('cropper')) {
      return;
    }

    if (type === 'checkbox') {
      options[name] = $this.prop('checked');
      cropBoxData = $image.cropper('getCropBoxData');
      canvasData = $image.cropper('getCanvasData');

      options.built = function () {
        $image.cropper('setCropBoxData', cropBoxData);
        $image.cropper('setCanvasData', canvasData);
      };
    } else if (type === 'radio') {
      options[name] = $this.val();
    }

    $image.cropper('destroy').cropper(options);
  });


  // Methods
  $('.docs-buttons').on('click', '[data-method]', function () {
    var $this = $(this);
    var data = $this.data();
    var $target;
    var result;

    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }

    if ($image.data('cropper') && data.method) {
      data = $.extend({}, data); // Clone a new one

      if (typeof data.target !== 'undefined') {
        $target = $(data.target);

        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
            console.log(e.message);
          }
        }
      }

      if (data.method === 'rotate') {
        $image.cropper('clear');
      }

      result = $image.cropper(data.method, data.option, data.secondOption);

      if (data.method === 'rotate') {
        $image.cropper('crop');
      }

      switch (data.method) {
        case 'scaleX':
        case 'scaleY':
          $(this).data('option', -data.option);
          break;

        case 'getCroppedCanvas':
          if (result) {

            //Draw result img on Bootstrap's Modal
            //$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

            if (!$download.hasClass('disabled')) {
              // Set image data to result modal's download button. 
              //$download.attr('href', result.toDataURL('image/jpeg'));



              
              // Option 1 (form post): 
              var imageDataURL = result.toDataURL('image/jpeg');
              var hidden_elem = document.getElementById("upload_hidden");
              hidden_elem.value = imageDataURL;
              if (typeof onImgDataURLReady === "function"){
                onImgDataURLReady(imageDataURL);
              }
              // End of Option 1

/*
              // Option 2 (ajax): 
              // Upload cropped image to server if the browser supports `HTMLCanvasElement.toBlob`
              result.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob);
                $.ajax('http://54.250.167.66/mycare/public/admission/test_upload', {
                  method: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (data, status) {
                    console.log('Upload success');
                    console.log(JSON.stringify(status)); 
                    console.log(JSON.stringify(data));
                  },
                  error: function () {
                    console.log('Upload error');
                  }
                });
              });
              // End of Option 2
*/
              


            }
          }

          break;
      }

      if ($.isPlainObject(result) && $target) {
        try {
          $target.val(JSON.stringify(result));
        } catch (e) {
          console.log(e.message);
        }
      }

    }
  });


  // Keyboard
  $(document.body).on('keydown', function (e) {

    if (!$image.data('cropper') || this.scrollTop > 300) {
      return;
    }

    switch (e.which) {
      case 37:
        e.preventDefault();
        $image.cropper('move', -1, 0);
        break;

      case 38:
        e.preventDefault();
        $image.cropper('move', 0, -1);
        break;

      case 39:
        e.preventDefault();
        $image.cropper('move', 1, 0);
        break;

      case 40:
        e.preventDefault();
        $image.cropper('move', 0, 1);
        break;
    }

  });


  // Import image
  var $inputImage = $('#inputImage');
  var URL = window.URL || window.webkitURL;
  var blobURL;

  if (URL) {
    $inputImage.change(function () {
      console.log("inputImage.change ...");
      var files = this.files;
      var file;

      if (!$image.data('cropper')) {
        return;
      }

      if (files && files.length) {
        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
          blobURL = URL.createObjectURL(file);


          // Check image size and resize image window
          var img_check_size = new Image;
          img_check_size.onload = function() {

            console.log("img_check_size " + " w "+img_check_size.width + " h "+ img_check_size.height);            
       

            /*  Resizing image canvas causes cropping area inaccurate.
            // Set image container w/h when loading
            var img_containe_width = 768/2;
            var img_containe_height = img_containe_width * img_check_size.height / img_check_size.width;
            console.log('scaled height ' + img_containe_height);
            console.log('scaled width ' + img_containe_width);
            //$('#image').css({ "height": img_containe_height });
            //$('#image').css({ "width": img_containe_width });
            $('#img-container').css({ "height": img_containe_height });
            $('#img-container').css({ "width": img_containe_width });
            // End of Set image container w/h
            */


            // Draw image...
            $image.one('built.cropper', function () {
              // Revoke when load complete
              URL.revokeObjectURL(blobURL);
            }).cropper('reset').cropper('replace', blobURL);
            $inputImage.val('');
            console.log("inputImage.change done");
            // End of Draw image...

          };
          img_check_size.src = blobURL;


        } else {
          window.alert('Please choose an image file.');
        }
      }
    });
  } else {
    $inputImage.prop('disabled', true).parent().addClass('disabled');
  }

});








