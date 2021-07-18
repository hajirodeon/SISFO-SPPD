/* Shockwave 3D slideshow (v0.9)
* Created: June 26th, 2012. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
*/

(function($) {

   /**
    * Creates a shockwave.Slider inside the jQuery element that calls this method.
    * The images used in the slider are those contained inside the jQuery element 
    * $elementContainingImages.
    * 
    * Options:
    *     'slider-type': determines the type of Slider.  Can be one of
    *                    Slider, FadeSlider,FlipSlider, FlipEdgeSlider, UnhookedSlider, CubeSlider, BillboardSlider or a custom slider.
    *                    Note that if the browser does not support CCS 3D transforms, the slider type
    *                    is overridden to the basic 'Slider'. 
    * The options are passed to both the Controller and the Slider; see those class declarations
    * for their options.
    */
   $.fn.shockwave = function(imagesDataArray, options) {
      var settings = $.extend({
         'slider-type': 'FlipSlider'
      }, options);

      var $this = this;
      var $elementContainingImages = $('<div/>'); // this will never be displayed.

      $.each(imagesDataArray, function(index, imageWithData) {
         var image = new Image();
         image.src = imageWithData.src;
         $elementContainingImages.append(image);
         image.url = imageWithData.url;
         image.target = imageWithData.target;
         image.description = imageWithData.description;
      });
   
      // $imageArray is a jQuery object (array) containing raw images elements.
      $elementContainingImages.imagesLoaded(function($imageArray, $properImages, $brokenImages) { 
         $imageArray = $properImages; // Images which did not load properly are ignored.
         var sliderConstructor = $.fn.shockwave[settings['slider-type']];
         if (!Modernizr.csstransforms3d) {
            sliderConstructor = settings['slider-type'] = $.fn.shockwave.Slider;
            settings['tiles-in-x'] = 15;
            settings['tiles-in-y'] = 15;
         }

         // The copied image array below seems like a useless step, but the plugin will not work with IE9 if it is not done.
         var arrayCopy = [];
         for (var ii = 0; ii < $imageArray.length; ii++) {
            var copy = new Image();
            copy.src = $imageArray[ii].src;
            copy.url = $imageArray[ii].url;
            copy.target = $imageArray[ii].target;
            copy.description = $imageArray[ii].description;
            arrayCopy[ii] = copy;
         }
         $this.slider = new sliderConstructor($this, arrayCopy, settings);
         new $.fn.shockwave.Controller($this, $this.slider, settings);
      });

      return $this;
   }


   ///////////////////////////////////////////////////////////////////////////////////////
   /**
    * A basic Slider that replaces the old tiles with the tiles from the new image.
    * They are not all replaced at the same time: the closer a tile is to the wave origin,
    * the sooner it is updated.
    * 
    * This class is used as the super-class for other Sliders.  The method setUpTimeAnimation
    * is the one that needs to be overriden by subclasses.  The options are passed to the subclasses.
    * 
    * Options:
    *   'wave-type': 'concentric' or 'linear'.  See also the options of ConcentricWave and LinearWave:
    *                the options passed to this constructor are also passed to the wave contructor.
    *   'tiles-in-x': the number of tile slices in the x-direction.  See ImageSplitter.
    *   'tiles-in-y': the number of tile slices in the y-direction.  See ImageSplitter.
    *   'viewport-dimension': the size for the displayed images.  If it is not specified, the 
    *             dimension of the first image, imageArray[0], is used.  
    *             This dimension should be specified with an object of the form {width: ..., height:...},
    *             where the units are implicitly pixels.  
    *             If some of the images displayed are not of this dimension, they are rescaled and 
    *             the extremities that do not fit in the viewport are cut off.  See ImageSplitter.
    *   'perspective': standard CSS option.  The distance of the camera from the image.
    *                          Must include the units.
    */
   $.fn.shockwave.Slider = function($container, imageArray, options) {
      var settings = $.extend({
         'wave-type': 'concentric', 
         'tiles-in-x': 5,
         'tiles-in-y': 5,
         'viewport-dimension': null,
         'perspective': '1000px'
      }, options);
      
      this.waveType = settings['wave-type'];
      this.settings = settings;
      var viewportDimension = settings['viewport-dimension'] || {
         width: imageArray[0].width,
         height: imageArray[0].height
      }
      this.imageSplitter = new $.fn.shockwave.ImageSplitter(viewportDimension,
         settings['tiles-in-x'], settings['tiles-in-y']);
      if (Modernizr.csstransforms3d)
         $.fn.shockwave.cssAutomaticPrefix($container, 'perspective', settings['perspective']);

      this.$container = $container;
      
      this.$container.height(viewportDimension.height);
      this.$container.width(viewportDimension.width);
      this.$container.css('position', 'relative');
      
      this.imageDistributor = new $.fn.shockwave.ImageDistributor(imageArray);
      
      this.queueName = 'shockwave';
      this.tilesToAnimate = [];
      this.nActiveAnimations = 0; // when down to 0, all time animations are done.
   }
   
   $.fn.shockwave.Slider.usesCSSTransforms3D = false;
   
   /**
    * Puts the first image on screen, without any animation.  The size of the $container is set
    * according to the dimensions of the first image.  The first image is the image
    * that is returned by the first call to this.imageDistributor.next().  
    * This method also returns the first image.
    * It was not included in the constructor of the Slider since it should be the job of
    * the Controller to set up the first image.
    */
   $.fn.shockwave.Slider.prototype.setUpFirstImage = function() {
      var firstImage = this.imageDistributor.next();
      this.tilesCurrent = this.imageSplitter.split(firstImage);
      var _thisSlider = this;
      $.each(this.tilesCurrent, function(index, $tile) {
         _thisSlider.$container.append($tile);
      });
      return firstImage;
   }
   
   /**
    * Starts animating the next image and returns the next image.  
    * If an animation is on-going on the current image, this method
    * does nothing and returns null.
    */
   $.fn.shockwave.Slider.prototype.changeImage = function(waveOriginType, backward) {
      if (this.nActiveAnimations > 0) 
         return null; // ignore method call if previous animation is not done.
      if (!backward)
         var nextImage = this.imageDistributor.next();
      else 
         nextImage = this.imageDistributor.previous();
      var tilesNext = this.imageSplitter.split(nextImage);
      var waveOrigin = $.fn.shockwave.ConcentricWave.computeWaveOrigin(waveOriginType, 
         nextImage.width, nextImage.height);
      if (this.waveType == 'concentric') 
         this.wave = new $.fn.shockwave.ConcentricWave(waveOrigin, this.settings);
      else if (this.waveType == 'linear')
         this.wave = new $.fn.shockwave.LinearWave(waveOrigin, this.settings);
      else 
         throw new Error('Unrecognized wave-type: ' + this.waveType);

      // Set the animation queue of each tile.
      for (var tileIndex = 0; tileIndex < this.tilesCurrent.length; tileIndex++) {
         this.setUpTileAnimation(this.tilesCurrent[tileIndex], tilesNext[tileIndex], backward);
      }

      // Launch all animation (nearly) simultaneaously.
      var _this = this;
      $.each(this.tilesToAnimate, function(index, $tile) {
         $tile.dequeue(_this.queueName);
      });
      this.tilesToAnimate = [];
      this.tilesCurrent = tilesNext;
      return nextImage;
   }

   /**
    * The specified callback function is executed immediately if there is no current animation,
    * otherwise it is put in a queue and will be executed when the animation is done.
    */
   $.fn.shockwave.Slider.prototype.whenAnimationIsDone = function(callback) {
      if (this.nActiveAnimations == 0)
         callback();
      else {
         this.$container.queue('animationIsDone', function(next) {
            callback();
            next();
         });
      }
   }
   
   /**
    * Decrements the number of active animations.  When it reaches 0, the callback functions that
    * where registered with whenAnimationIsDone are executed.
    */
   $.fn.shockwave.Slider.prototype.decrementNActiveAnimations = function() {
      this.nActiveAnimations--;
      if (this.nActiveAnimations <= 0) {
         this.$container.dequeue('animationIsDone');
      }
   }

   /**
    * Increments the number of active animations.  It is usually called when starting a tile animation.
    * The tile animation should then call decrementNActiveAnimations when it is done.
    */
   $.fn.shockwave.Slider.prototype.incrementNActiveAnimations = function() {
      this.nActiveAnimations++;
   }


   /**
    * Decrements the number of active animations.  When it reaches 0, the callback functions that
    * where registered with whenAnimationIsDone are executed.
    */
   $.fn.shockwave.Slider.prototype.decrementNActiveAnimations = function() {
      this.nActiveAnimations--;
      if (this.nActiveAnimations <= 0) {
         this.$container.dequeue('animationIsDone');
      }
   }

   /**
    * Override this method to create custom animations.
    * Here are a few important items to remember:
    *  - Add $nextTile to the container (at some point).
    *  - Remove $tile from the container (at some point).
    *  - Add the elements whose animation is started by method launchAnimations to the array
    *    this.tilesToAnimate.
    *  - Correctly increment and decrement nActiveAnimations, to avoid the possibility of
    *    having more than one animation active at any time.
    */
   $.fn.shockwave.Slider.prototype.setUpTileAnimation = function($tile, $nextTile) {
      var center = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(center);
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms

      var _thisSlider = this;
      $tile.queue(this.queueName, function (next) {
         setTimeout(function() {
            $tile.replaceWith($nextTile);
            _thisSlider.decrementNActiveAnimations();
         }, delay);
      });
      
      this.nActiveAnimations++;
      this.tilesToAnimate.push($tile);
   }
  
   /**
    * Add the prefixes -ms-, -webkit-, -moz- and -o- to some css options, and
    * apply the css options the $element.  Also apply the plain css option without any prefix.
    * The css options supported are 'perspective', 'transform-origin', 'transition-timing-function',
    * 'backface-visibility'.
    * If the option is not supported, the plain option without the prefixes is still applied.
    */
   $.fn.shockwave.cssAutomaticPrefix = function($element, cssOption, cssValue) {
      $element.css(cssOption, cssValue);
      var prefixArray = ['-ms-', '-webkit-', '-moz-', '-o-'];
      var supportedOptions = ['perspective', 'transform-origin', 'transition', 'transition-timing-function', 
      'transform', 'backface-visibility'];
      var isValidOption = supportedOptions.some(function(option) {
         return option == cssOption;
      });
      if (isValidOption) 
         $.each(prefixArray, function(index, prefix) {
            $element.css(prefix + cssOption, cssValue);
         });
   }
   
   /**
    * Same as above, but also adds the prefix to the value corresponding the the css option.
    * The only supported option is 'transition'.
    */
   $.fn.shockwave.cssAutomaticPrefixAndValue = function($element, cssOption, cssValue) {
      $element.css(cssOption, cssValue);
      var prefixArray = ['-ms-', '-webkit-', '-moz-', '-o-'];
      var supportedOptions = ['transition'];
      var isValidOption = supportedOptions.some(function(option) {
         return option == cssOption;
      });
      if (isValidOption) 
         $.each(prefixArray, function(index, prefix) {
            $element.css(prefix + cssOption, prefix + cssValue);
         });
   }

   ///////////////////////////////////////////////////////////////////////////
   /**
    * A wave that propagates radially from a center (waveOrigin) at a certain speed.
    * 
    * Options: 
    *      'wave-speed':  in pixels / second.  Can be Infinity for instantaneous wave.
    *                    For a slow speed, tile that are far from the wave origin will take a long
    *                    time before starting their animation.
    *      'wave-fixed-origin': if this is set, the origin of the wave will always be given by
    *                           this value.  Otherwise, the wave origin is where the user clicks
    *                           on the image.  Must be give as an array [x, y], where the units are pixels.
    */
   $.fn.shockwave.ConcentricWave = function(waveOrigin, options) {
      var settings = $.extend({
         'wave-speed': 700,  
         'wave-fixed-origin' : undefined
      }, options);
      this.origin = (settings['wave-fixed-origin'] != null) ? settings['wave-fixed-origin'] : waveOrigin;
      this.speed = settings['wave-speed'];
   }

   $.fn.shockwave.ConcentricWave.prototype.distanceFromWaveOrigin = function(strikePoint) {
      var dx = strikePoint[0] - this.origin[0];
      var dy = strikePoint[1] - this.origin[1]; 
      return Math.sqrt(dx * dx + dy * dy);
   }

   $.fn.shockwave.ConcentricWave.prototype.vector2DFromWaveOrigin = function(strikePoint) {
      var dx = strikePoint[0] - this.origin[0];
      var dy = strikePoint[1] - this.origin[1]; 
      return [dx, dy];
   }

   $.fn.shockwave.centerRelativeToParent = function($element) {
      var width = $element.width();
      var height = $element.height();
      var left = parseInt($element.css('left').replace(/px/, ''));
      var top = parseInt($element.css('top').replace(/px/, ''));
      return [left + 0.5 * width, top + 0.5 * height];
   }
   
   $.fn.shockwave.ConcentricWave.computeWaveOrigin = function(waveOriginType, width, height) {
      if (waveOriginType == 'random') {
         return [Math.floor(Math.random() * width), Math.floor(Math.random() * height)];
      }
      var copyWaveOrigin = waveOriginType.slice(0, waveOriginType.length); // just clone array
      if (copyWaveOrigin[0].search && copyWaveOrigin[0].search(/%/) != -1) {
         copyWaveOrigin[0] = copyWaveOrigin[0].replace(/%/, '') / 100 * (width - 1);
      }
      if (copyWaveOrigin[1].search && copyWaveOrigin[1].search(/%/) != -1) {
         copyWaveOrigin[1] = copyWaveOrigin[1].replace(/%/, '') / 100 * (height - 1);
      }
      return copyWaveOrigin;
   }


   ///////////////////////////////////////////////////////////////////////////
   /**
    * A wave that propagates away from a generating line, in both directions.
    * The line is defined by 1) any point on the line, and 2) the direction of
    * wave propagation, which is perpendicular to the generating line.
    */
   $.fn.shockwave.LinearWave = function(waveOrigin, options) {
      var settings = $.extend({
         'wave-direction' : [1, 1]
      }, options);
      var superr = new $.fn.shockwave.ConcentricWave(waveOrigin, options);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.waveDirection = settings['wave-direction'];
   }

   /**
    * The distance of strikePoint from the wave generating line is given by 
    * (strikePoint - any point on the line) . waveDirection / |waveDirection|.
    */
   $.fn.shockwave.LinearWave.prototype.distanceFromWaveOrigin = function(strikePoint) {
      var scalarProduct = this._scalarFromOrigin(strikePoint);
      var normalization = Math.sqrt(this.waveDirection[0] * this.waveDirection[0] + 
         this.waveDirection[1] * this.waveDirection[1]);
      return Math.abs(scalarProduct / normalization);
   }

   $.fn.shockwave.LinearWave.prototype.vector2DFromWaveOrigin = function(strikePoint) {
      var scalarProduct = this._scalarFromOrigin(strikePoint);
      var sign = (scalarProduct >= 0) ? 1 : -1;
      var normalization = Math.sqrt(this.waveDirection[0] * this.waveDirection[0] + 
         this.waveDirection[1] * this.waveDirection[1]);
      return [sign * this.waveDirection[0] / normalization, sign * this.waveDirection[1] / normalization];
   }

   $.fn.shockwave.LinearWave.prototype._scalarFromOrigin = function(strikePoint) {
      var strikePointFromOrigin = [strikePoint[0] - this.origin[0], strikePoint[1] - this.origin[1]];
      return strikePointFromOrigin[0] * this.waveDirection[0] + strikePointFromOrigin[1] * this.waveDirection[1];
   }

   //////////////////////////////////////////////////////////////////////////////
   /**
    * Cycles through an array of images, forward or back.
    * A cookie is stored each time a new image is requested such that if the web page is reloaded
    * it will start from the last shown image.
    * Note that the first time 'next()' is called, it returns the latest image shown (not the next one).
    * If there is no cookie stored, the first time 'next()' is called it will return the image
    * at index 0 (not index 1).
    */
   $.fn.shockwave.ImageDistributor = function(imageArray) {
      this.imageArray = imageArray;
      var cookieImageIndex = "shockwaveImageDistributorImageIndex";
      var storedImageIndex = $.cookie(cookieImageIndex);
      this.currentIndex = (storedImageIndex > 0 && storedImageIndex < (imageArray.length - 1)) ?
      (storedImageIndex - 1) : -1;
      
      this.next = function() {
         this.currentIndex++;
         this.currentIndex %= this.imageArray.length;
         $.cookie(cookieImageIndex, this.currentIndex);
         return this.imageArray[this.currentIndex];
      }
      
      this.previous = function() {
         this.currentIndex--;
         if (this.currentIndex == -1)
            this.currentIndex = this.imageArray.length - 1;
         return this.imageArray[this.currentIndex];
      }

      this.current =  function() {
         return this.imageArray[this.currentIndex];
      }
   }
   
   ////////////////////////////////////////////////////////////////////////////// 
   /**
    * Splits images into tiles.  
    * For example, nBoxesX = 4 and nBoxesY = 1 splits the image in 4 side-by-side (left-to-right) rectangles,
    * of approximately the same width.
    * 
    * If an image is not of the same dimension as viewportDimension (width and height), it is 
    * 1) resized so that if the image is proportionally wider that viewportDimension, the new height
    * corresponds to viewportDimension.height (width if proportionally taller),
    * 2) the extremities of the image which do not fit in viewportDimension are omitted.
    */
   $.fn.shockwave.ImageSplitter = function(viewportDimension, nBoxesX, nBoxesY) {
      this.viewportDimension = viewportDimension;
      this.boxSizesX = $.fn.shockwave.ImageSplitter._split1D(viewportDimension.width, nBoxesX);
      this.boxSizesY = $.fn.shockwave.ImageSplitter._split1D(viewportDimension.height, nBoxesY);
   }
   
   /*
    * Splits the image and return the tiles as an array of $('<div/>').
    */
   $.fn.shockwave.ImageSplitter.prototype.split = function(image) {
      var offset = {
         x: 0,
         y: 0
      };
      if ((image.width / image.height) == (this.viewportDimension.width / this.viewportDimension.height)) {
         var backgroundWidth = this.viewportDimension.width;
         var backgroundHeight = this.viewportDimension.height;
      } else if ((image.width / image.height) > (this.viewportDimension.width / this.viewportDimension.height)){ // too wide
         backgroundHeight = this.viewportDimension.height;
         backgroundWidth = image.width * (backgroundHeight / image.height);
         offset.x = (backgroundWidth - this.viewportDimension.width) / 2;
      } else { // too tall
         backgroundWidth = this.viewportDimension.width;
         backgroundHeight = image.height * (backgroundWidth / image.width);
         offset.y = (backgroundHeight - this.viewportDimension.height) / 2;
      }
      var arrayImageDivs = [];
      for (var ix = 0, currentX = 0; ix < this.boxSizesX.length; currentX += this.boxSizesX[ix], ix++) {
         for (var iy = 0, currentY = 0; iy < this.boxSizesY.length; currentY += this.boxSizesY[iy], iy++) {
            var $tile = $('<div/>');
            $tile.css({
               position: 'absolute',
               width : this.boxSizesX[ix], 
               height : this.boxSizesY[iy],
               left : currentX,
               top : currentY,
               'background-image' : 'url(' + image.src + ')',
               'background-size' : backgroundWidth + 'px ' + backgroundHeight + 'px',
               'background-position' : (-offset.x - currentX) + 'px ' + (-offset.y - currentY) + 'px'
            });
            arrayImageDivs.push($tile);
         }
      }
      return arrayImageDivs;
   }
      
   $.fn.shockwave.ImageSplitter._split1D = function(nPixels, nBoxes) {
      var basicBoxSize = Math.floor(nPixels / nBoxes);
      var remainingPixels = nPixels % nBoxes;
      var boxSizes = [];
        
      for (var iBox = 0; iBox < nBoxes; iBox++) {
         boxSizes[iBox] = basicBoxSize + ((iBox < remainingPixels) ? 1 : 0);
      }
      return boxSizes;
   }
      
   ////////////////////////////////////////////////////////////////////////////////////
   /**
    * Allows to go from one image to the next using Next/Previous buttons, as well as
    * a button to start a slideshow.
    * 
    *  $container: the element that displays the controller.
    *  slider: the slider on which the Controller is acting.
    *  options: 
    *           'autostart-slideshow': true or false.  Default to false.
    *           'slideshow-delay': the time between slides in milliseconds, including animation time.
    *           'controller-default-wave-origin' the wave origin when clicking on the next button. Default is [0, 0], the top left corner.
    *                   The format can be [pixelX, pixelY], or [x%, y%], or 'random'.
    *           'controller-allow-next-onclick-image': true or false, allows clicking on the image to call the next image.
    *                                         This option is ignored if 'allow-redirect-onclick' is true.
    *           'controller-display-buttons': true or false.
    *           'controller-use-icon-buttons': true or false.  If false, basic text buttons are used.  Also, 
    *                                          if the icon images are not found, the basic text buttons are used.
    *           'pause-slideshow-onmouseover': true or false.  By default it is set to true.
    *           'show-description-onmouseover': true (default) or false.  See also 'show-permanent-description'.
    *           'show-permanent-description': true or false (default).  If this is true, then 'show-description-onmouseover'
    *                                         is ignored.
    *           'show-description-onimage: true (default) or false.  Shows the image description at the bottom of
    *                                      the image.  See also 'description-output' which allows the description
    *                                      to be displayed outside the image.
    *           'description-output': a jQuery object where the description of each image will be displayed.
    *                                  The description of an image is specified from
    *                                  the custom 'description' property of the javascript Image object:
    *                                  someImage.description = 'blah blah'.  Note that it does not have to be text:
    *                                  when a new image is displayed, $descriptionOuput.html(imageDescription) is called.
    *                                  See also 'show-description-onimage'.
    *           'maximum-slideshow-cycles': the slideshow stops after this many cycles.  By default is Infinity.
    *           'allow-redirect-onclick': clicking on the image loads the page to the web page
    *                                     associated to the image.  If true, this option voids 'controller-allow-next-onclick-image'.
    *                                     The web page associated to each image is specified simply by adding a 'url'
    *                                     property to a javascript Image object: someImage.url = 'http://some_website.com'.
    *           'standard-control-buttons-area': if null (default), the default control buttons are not shown.
    *                                           For the standard buttons to be displayed, a jQuery container must be
    *                                           passed with this option and the buttons will be placed in that container.
    *                                           Other buttons
    *                                           can be added with 'next-slide-button' and previous-slide-button',  but
    *                                           'play-pause-slideshow-buttons' cannot be combined with this option.
    *                                           The directory icons/ and images within must be
    *                                           present, otherwise it reverts to text buttons.
    *           'next-slide-button': any jQuery object, including a list of objects.  When it is clicked the next slide will be displayed.
    *                                It can even be the shockwave container, provided 'allow-redirect-onclick' is not active.
    *           'previous-slide-button': any jQuery object, including a list of objects.  When it is clicked the previous slide will be displayed.
    *           'play-pause-slideshow-buttons': can be null (default) or a jQuery object with an inner element
    *                                           of class 'play' (the button to start the slideshow) and one
    *                                           of class 'pause' (the button to pause the slideshow).
    *                                           Only one of the two elements will be visible at anytime: 
    *                                           'play' when the slideshow is in a paused state and
    *                                           'pause' when the slideshow is active.
    *                                           It cannont be combined with a true value for 'use-standard-control-buttons'.
    */
   $.fn.shockwave.Controller = function($container, slider, options) {
      var settings = $.extend({
         'autostart-slideshow': false,
         'slideshow-delay': '4000',
         'controller-default-wave-origin': [0, 0],
         'controller-allow-next-onclick-image': true,
         'controller-display-buttons': true,
         'controller-use-icon-buttons': true,
         'pause-slideshow-onmouseover': true,
         'show-description-onmouseover': true,
         'show-permanent-description': false,
         'show-description-onimage': true,
         'maximum-slideshow-cycles': Infinity,
         'allow-redirect-onclick': true,
         'description-output': null,
         'standard-control-buttons-area': null,
         'next-slide-button': null,
         'previous-slide-button': null,
         'play-pause-slideshow-buttons': null
      }, options);
      this.$container = $container;
      this.slider = slider;
      this.slideshowDelay = settings['slideshow-delay'];
      this.fixedWaveOriginType = settings['controller-default-wave-origin'];
      this.slideshowTimer = null;
      this.isSlideshowActive = false;
      this.maximumSlideshowCycles = settings['maximum-slideshow-cycles'];
      this.allowRedirectOnClick = settings['allow-redirect-onclick'];
      if (this.allowRedirectOnClick)
         $container.css('cursor', 'pointer');
      this.$descriptionOutput = settings['description-output'];
      this.playPauseButtons = [];
      
      var _thisController = this;
      
      var firstImage = this.slider.setUpFirstImage();
      if (this.allowRedirectOnClick) {
         _thisController.bindContainerClickToURL(firstImage.url, firstImage.target);
      } else if (settings['controller-allow-next-onclick-image']) {
         $container.click(function(event) {
            var offset = $container.offset();
            var waveOrigin = [event.clientX - offset.left, event.clientY - offset.top];
            _thisController.nextSlide(waveOrigin);
         });
      }
            
      _thisController.registerHandlerNextButton(settings['next-slide-button']);
      _thisController.registerHandlerPreviousButton(settings['previous-slide-button']);
      _thisController.registerHandlerPlayPauseButtons(settings['play-pause-slideshow-buttons']);
      if (settings['standard-control-buttons-area']) {
         _thisController.makeIconButtons(function($buttons) {
            settings['standard-control-buttons-area'].html($('<div/>').append($buttons));
         });
      }
            
      if (settings['pause-slideshow-onmouseover']) {
         var isPausedFromMouseover = false;
         $container.mouseoverWithCheck(function() {
            if (_thisController.isSlideshowActive) {
               isPausedFromMouseover = true;
               _thisController.pauseSlideshow();
            }
         });
         $container.mouseleave(function() {
            if (isPausedFromMouseover) {
               _thisController.startSlideshow();            
               isPausedFromMouseover = false;
            }
         });
      }

      if (settings['show-description-onimage']&& !settings['show-permanent-description']) {
         _thisController.$descriptionAreaOnImage = $('<div/>');
         _thisController.$descriptionAreaOnImage.addClass('descriptionArea');
         _thisController.$descriptionAreaOnImage.css({
            'position': 'absolute',
            'bottom': '0px',
            'width': _thisController.$container.width() + 'px',
            'color': '#ffffff',
            'background-color': '#111111',
            'opacity': 0.7,
            'z-index': 1000,
            'text-align': 'left'
         });
         this.$container.append(_thisController.$descriptionAreaOnImage);
      }
      
      if (settings['show-description-onmouseover'] && !settings['show-permanent-description']) {
         if (_thisController.$descriptionAreaOnImage)
            _thisController.$descriptionAreaOnImage.css({
               display: 'none'
            });
         if (_thisController.$descriptionOutput) 
            _thisController.$descriptionOutput.css({
               display: 'none'
            });
         
         $container.mouseoverWithCheck(function(event) {
            if (_thisController.$descriptionAreaOnImage)
               _thisController.$descriptionAreaOnImage.slideDown();
            if (_thisController.$descriptionOutput) 
               _thisController.$descriptionOutput.slideDown();
         });
         $container.mouseleave(function() {
            if (_thisController.$descriptionAreaOnImage)
               _thisController.$descriptionAreaOnImage.slideUp();
            if (_thisController.$descriptionOutput) 
               _thisController.$descriptionOutput.slideUp();

         })
      }
      
      if (settings['show-permanent-description']) {
         _thisController.$permanentDescriptionContainer = $('<div/>');
         _thisController.$permanentDescription = $('<div/>');
         _thisController.$permanentDescription.addClass('descriptionArea');
         _thisController.$permanentDescriptionContainer.css({
            'position': 'absolute',
            'bottom': '0px',
            'width': _thisController.$container.width() + 'px',
            'color': '#ffffff',
            'background-color': '#111111',
            'opacity': 0.7,
            'z-index': 1000,
            'text-align': 'center',
            'display': 'block'
         });
         var $closeButton = $('<div/>').html('x');
         $closeButton.css({
            'position': 'absolute',
            'right': '3px',
            'top': '-2px',
            'font-size': '14px',
            'font-family': 'sans-serif',
            'color': '#ffffff'
         });
         $closeButton.click(function(event) {
            event.stopPropagation();
            _thisController.$permanentDescriptionContainer.slideUp();
         });
         _thisController.$permanentDescriptionContainer.append(_thisController.$permanentDescription);
         _thisController.$permanentDescriptionContainer.append($closeButton);
         _thisController.$container.append(_thisController.$permanentDescriptionContainer);
      }
      
      _thisController.$container.find('.descriptionArea').html(firstImage.description);
      if (_thisController.$descriptionOutput) 
         _thisController.$descriptionOutput.html(firstImage.description);
               
               
      if (settings['autostart-slideshow']) 
         // This might be called before the icon images are loaded; see the fix at the end of makeIconButtons().
         _thisController.startSlideshow();
   }

   /**
    * The normal jQuery mouseover function can be falsely triggered when an animated image leaks out of
    * a container and is touched by a moving mouse pointer.
    * This function corrects this behavior by first making an explicit check to see if the mouse
    * pointer is in the rectangle defined by the container.
    */
   $.fn.mouseoverWithCheck = function(callback) {
      var $thisContainer = this;
      $thisContainer.mouseover(function(event) {
         var containerOffset = $thisContainer.offset();
         if (event.pageY >= containerOffset.top && event.pageX >= containerOffset.left && 
            event.pageY <= (containerOffset.top + $thisContainer.height()) && 
            event.pageX <= (containerOffset.left + $thisContainer.width()))
            callback();
      });
   }

   $.fn.shockwave.Controller.prototype.makeTextButtons = function(buttonsHandlerFunction) {
      var _thisController = this;
      var $nextButton = $('<button/>').text('>');
      var $previousButton = $('<button/>').text('<');
      var $play= $('<button/>').width(100).text('Slideshow');
      var $pause = $('<button/>').width(100).text('Pause');

      _thisController.registerHandlerNextButton($nextButton);
      _thisController.registerHandlerPreviousButton($previousButton);

      $play.addClass('play');
      $pause.addClass('pause');
      var $playPauseSpan = $('<span/>').append($play, $pause);
      _thisController.registerHandlerPlayPauseButtons($playPauseSpan);

      var $buttonBar = $('<div/>').css({
         'text-align': 'center',
         'display': 'inline-block'
      });
      $buttonBar.append($previousButton, $playPauseSpan, $nextButton);
      buttonsHandlerFunction($buttonBar);
   }

   /**
    * If the icon images are missing, it reverts to makeTextButtons.
    * A handler function is used to place the buttons inside the page instead of just return 
    * the buttons because of the asynchroneous nature of reading image files.
    */
   $.fn.shockwave.Controller.prototype.makeIconButtons = function(buttonsHandlerFunction) {
      var _thisController = this;
      var icons = ['arrow_left.png', 'button_play.png', 'button_pause.png', 'arrow_right.png'];
      var $icons = $('<div/>');

      $.each(icons, function(index, icon) {
         var image = new Image();
         image.src = 'icons/' + icon;
         image.style.width = '70px'
         image.style.height = 'auto'
         $icons.append(image);
      });
         
      $icons.imagesLoaded(function($iconArray, $properImages, $brokenImages) {
         var hasInvalidIcon = false;
         $iconArray.each(function() {
            var image = new Image();
            image.src = this.src;
            if (image.width == 0)
               hasInvalidIcon = true;
         });
         if (hasInvalidIcon || ($brokenImages.length != 0)) {
            _thisController.makeTextButtons(buttonsHandlerFunction);
         } else {
            var $previous = $($iconArray[0]);
            var $next = $($iconArray[3]);
            _thisController.registerHandlerNextButton($next);
            _thisController.registerHandlerPreviousButton($previous);

            var $play = $($iconArray[1]).addClass('play');
            var $pause = $($iconArray[2]).addClass('pause');
            var $playPauseSpan = $('<span/>').append($play, $pause);
            _thisController.registerHandlerPlayPauseButtons($playPauseSpan);

            var $buttonBar = $('<div/>').css('display', 'inline');
            $buttonBar.append($previous, $playPauseSpan, $next);
            buttonsHandlerFunction($buttonBar);
            
            // Due to the asynchroneous nature of this function call, the images might be put 
            // on the page after the slideshow was activated.
            if (_thisController.isSlideshowActive) {
               _thisController.setPlayPauseButtonsInPlayState();
            }
         }
      });
   }
         
   $.fn.shockwave.Controller.prototype.registerHandlerPlayPauseButtons = function($playPauseButtons) {
      var _thisController = this;
      if ($playPauseButtons) {
         _thisController.playPauseButtons.push($playPauseButtons);
         var $play = $playPauseButtons.find('.play');
         var $pause = $playPauseButtons.find('.pause');
         $play.show();
         $pause.hide();

         $play.click(function() {
            _thisController.startSlideshow();   
         });
         $pause.click(function() {
            _thisController.pauseSlideshow();
         });
      }
   }

   $.fn.shockwave.Controller.prototype.registerHandlerNextButton = function (nextButton) {
      var _thisController = this;
      if (nextButton) {
         nextButton.click(function(event) {
            event.stopPropagation();
            _thisController.nextSlide(_thisController.fixedWaveOriginType);
         });
      }
   }
   
   $.fn.shockwave.Controller.prototype.registerHandlerPreviousButton = function (previousButton) {
      var _thisController = this;
      var backward = true;
      if (previousButton)
         previousButton.click(function(event) {
            event.stopPropagation();
            _thisController.nextSlide(_thisController.fixedWaveOriginType, backward);
         });
   }

   $.fn.shockwave.Controller.prototype.setPlayPauseButtonsInPauseState = function() {
      var _thisController = this;
      $.each(_thisController.playPauseButtons, function(index, $playPauseButtons) {
         $playPauseButtons.find('.play').show();
         $playPauseButtons.find('.pause').hide();
      });
   }
   
   $.fn.shockwave.Controller.prototype.setPlayPauseButtonsInPlayState = function() {
      var _thisController = this;
      $.each(_thisController.playPauseButtons, function(index, $playPauseButtons) {
         $playPauseButtons.find('.play').hide();
         $playPauseButtons.find('.pause').show();
      });
   }

   $.fn.shockwave.Controller.prototype.registerHandlerStartSlideshowButton = function (startSlideshowButton) {
      var _thisController = this;
      if (startSlideshowButton)
         startSlideshowButton.click(function(event) {
            event.stopPropagation();
            _thisController.startSlideshow();
         });
   }

   $.fn.shockwave.Controller.prototype.registerHandlerPauseSlideshowButton = function (pauseButton) {
      var _thisController = this;
      if (pauseButton)
         pauseButton.click(function(event) {
            event.stopPropagation();
            _thisController.pauseSlideshow();
         });
   }
   
   /**
    * Returns the new image, or "null" is the request for the next was refused since the
    * previous animation is still ongoing.
    */
   $.fn.shockwave.Controller.prototype.nextSlide = function(waveOrigin, backward) {
      var _thisController = this;
      var newImage = this.slider.changeImage(waveOrigin, backward);
      if (newImage != null) {  // Is null in the case where there is an ongoing tile animation.
         if (_thisController.allowRedirectOnClick) 
            _thisController.bindContainerClickToURL(newImage.url, newImage.target);
         _thisController.$container.find('.descriptionArea').html(newImage.description);
         if (_thisController.$descriptionOutput) 
            _thisController.$descriptionOutput.html(newImage.description);
         if (_thisController.$permanentDescriptionContainer)
            _thisController.$permanentDescriptionContainer.slideDown();
      }
      return newImage;
   }
   
   /** 
    * Navigates away to the new url when the container is clicked.  If the url is a non-value,
    * 'click' is unbound.
    */
   $.fn.shockwave.Controller.prototype.bindContainerClickToURL = function(url, target) {
      var _thisController = this;   
      _thisController.$container.unbind('click');
      if (url) {
         _thisController.$container.click(function(event) {
            event.stopPropagation();
            if (target == '_blank')
               window.open(url); // new window
            else 
               window.location = url; // same window
         });
      }
   }
 
   $.fn.shockwave.Controller.prototype.startSlideshow = function(doWaitBeforeChangingFirstSlide) {
      var _thisController = this;
      _thisController.isSlideshowActive = true;
      clearInterval(this.slideshowTimer); // in case the slideshow was already active.
      var counterImagesToCycles = _thisController.maximumSlideshowCycles * _thisController.slider.imageDistributor.imageArray.length;
      if (!doWaitBeforeChangingFirstSlide) {
         _thisController.nextSlide(_thisController.fixedWaveOriginType);
         counterImagesToCycles--;
      }
      _thisController.slideshowTimer = setInterval(function() {
         counterImagesToCycles--;
         if (counterImagesToCycles < 0)
            _thisController.pauseSlideshow();
         else {
            var newImage = _thisController.nextSlide(_thisController.fixedWaveOriginType);
            if (newImage == null) // There was already an ongoing animation, so nextSlide did nothing.
               counterImagesToCycles++;
         }
      }, _thisController.slideshowDelay);
      _thisController.setPlayPauseButtonsInPlayState();
   }
   
   $.fn.shockwave.Controller.prototype.pauseSlideshow = function() {
      var _thisController = this;
      _thisController.isSlideshowActive = false;
      clearInterval(_thisController.slideshowTimer);
      _thisController.setPlayPauseButtonsInPauseState();
   }

   /*
    * jQuery imagesLoaded plugin v2.0.1
    * http://github.com/desandro/imagesloaded
    * MIT License. by Paul Irish et al.
    */
   $.fn.imagesLoaded = function( callback ) {
      var $this = this,
      deferred = $.isFunction($.Deferred) ? $.Deferred() : 0,
      hasNotify = $.isFunction(deferred.notify),
      $images = $this.find('img').add( $this.filter('img') ),
      loaded = [],
      proper = [],
      broken = [];

      var BLANK = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

      function doneLoading() {
         var $proper = $(proper),
         $broken = $(broken);

         if ( deferred ) {
            if ( broken.length ) {
               deferred.reject( $images, $proper, $broken );
            } else {
               deferred.resolve( $images );
            }
         }

         if ( $.isFunction( callback ) ) {
            callback.call( $this, $images, $proper, $broken );
         }
      }

      function imgLoaded( img, isBroken ) {
         // don't proceed if BLANK image, or image is already loaded
         if ( img.src === BLANK || $.inArray( img, loaded ) !== -1 ) {
            return;
         }

         // store element in loaded images array
         loaded.push( img );

         // keep track of broken and properly loaded images
         if ( isBroken ) {
            broken.push( img );
         } else {
            proper.push( img );
         }

         // cache image and its state for future calls
         $.data( img, 'imagesLoaded', {
            isBroken: isBroken, 
            src: img.src
         } );

         // trigger deferred progress method if present
         if ( hasNotify ) {
            deferred.notifyWith( $(img), [ isBroken, $images, $(proper), $(broken) ] );
         }

         // call doneLoading and clean listeners if all images are loaded
         if ( $images.length === loaded.length ){
            setTimeout( doneLoading); 
            $images.unbind( '.imagesLoaded' );
         }
      }

      // if no images, trigger immediately
      if ( !$images.length ) {
         doneLoading();
      } else {
         $images.bind( 'load.imagesLoaded error.imagesLoaded', function( event ){
            // trigger imgLoaded
            imgLoaded( event.target, event.type === 'error' );
         }).each( function( i, el ) {
            var src = el.src;

            // find out if this image has been already checked for status
            // if it was, and src has not changed, call imgLoaded on it
            var cached = $.data( el, 'imagesLoaded' );
            if ( cached && cached.src === src ) {
               imgLoaded( el, cached.isBroken );
               return;
            }

            // if complete is true and browser supports natural sizes, try
            // to check for image status manually
            if ( el.complete && el.naturalWidth !== undefined ) {
               imgLoaded( el, el.naturalWidth === 0 || el.naturalHeight === 0 );
               return;
            }

            // cached images don't fire load sometimes, so we reset src, but only when
            // dealing with IE, or image is complete (loaded) and failed manual check
            // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
            if ( el.readyState || el.complete ) {
               el.src = BLANK;
               el.src = src;
            }
         });
      }
      return deferred ? deferred.promise( $this ) : $this;
   };
   
   /*!
    * jQuery Cookie Plugin
    * https://github.com/carhartl/jquery-cookie
    *
    * Copyright 2011, Klaus Hartl
    * Dual licensed under the MIT or GPL Version 2 licenses.
    * http://www.opensource.org/licenses/mit-license.php
    * http://www.opensource.org/licenses/GPL-2.0
    */
   $.cookie = function(key, value, options) {

      // key and at least value given, set cookie...
      if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
         options = $.extend({}, options);

         if (value === null || value === undefined) {
            options.expires = -1;
         }

         if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
         }

         value = String(value);

         return (document.cookie = [
            encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path    ? '; path=' + options.path : '',
            options.domain  ? '; domain=' + options.domain : '',
            options.secure  ? '; secure' : ''
            ].join(''));
      }

      // key and possibly options given, get cookie...
      options = value || {};
      var decode = options.raw ? function(s) {
         return s;
      } : decodeURIComponent;

      var pairs = document.cookie.split('; ');
      for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
         if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
      }
      return null;
   };
})(jQuery);


