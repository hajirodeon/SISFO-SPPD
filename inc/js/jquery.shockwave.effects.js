(function() {

   //////////////////////////////////////////////////////////////////////////////////////
   /**
 * The tile makes a full rotation to come back to its original position.  At the mid-trajectory,
 * the image is changed for the new image.
 * 
 * Options:
 *      'tiles-in-x': see the super-class.
 *      'rotation-duration'
 *      'transform-origin': standard CSS3 option. The pivot point for the rotation.
 *      'fixed-rotation-axis': if set to null, the rotation axis is perpendicular to the incoming wave.
 *      'opacity-low': the opacity reaches this low value at mid-trajectory.
 */
   $.fn.shockwave.FlipEdgeSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'tiles-in-x': 1,
         'tiles-in-y': 1,
         'rotation-duration': '700', // in ms
         'transform-origin': '0% 100% 0px', 
         'transition-timing-function': 'linear',
         'fixed-rotation-axis': [1, 0, 0],
         'opacity-low': 0.5
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.FlipSlider;
      
      this.durationMS = settings['rotation-duration'];
      this.transformOrigin = settings['transform-origin'];
      this.easingFunction = settings['transition-timing-function'];
      this.fixedRotationAxis = settings['fixed-rotation-axis'];
      this.opacityLow = settings['opacity-low'];
   }
   
   $.fn.shockwave.FlipEdgeSlider.usesCSSTransforms3D = true;
   
   $.fn.shockwave.FlipEdgeSlider.prototype.setUpTileAnimation = function($tile, $nextTile, backward) {
      var tileCenter = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(tileCenter);
      if (this.fixedRotationAxis)
         var rotationAxis = this.fixedRotationAxis;
      else {
         var vector = this.wave.vector2DFromWaveOrigin(tileCenter);
         rotationAxis = [-vector[1], vector[0], 0]; // perpedicular to vector 
      }
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms
      var $parent = $tile.parent();
      
      var midAngle = 180;
      var midAngleNext = -180;
      var finalAngle = 0;
      if (backward) {
         midAngle = -180;
         midAngleNext = 180;
      }

      var _thisSlider = this;
      
      $.fn.shockwave.cssAutomaticPrefix($tile, 'backface-visibility', 'visible');
      $.fn.shockwave.cssAutomaticPrefix($nextTile, 'backface-visibility', 'visible');
      $tile.queue(this.queueName, function(next) {
         setTimeout(next, delay);
      });

      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.animate({
            opacity: _thisSlider.opacityLow
         }, _thisSlider.durationMS / 2);
         var cssString = 'rotate3d(' + rotationAxis.concat(midAngle).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + _thisSlider.durationMS / 2 + 'ms');
         setTimeout(next, _thisSlider.durationMS / 2);
      });
      
      $tile.queue(_thisSlider.queueName, function(next) {
         $nextTile.dequeue(_thisSlider.queueName);
         $tile.remove();
      });

      $nextTile.queue(_thisSlider.queueName, function(next) {
         $parent.append($nextTile);
         var cssString = 'rotate3d(' + rotationAxis.concat(midAngleNext).toString() + 'deg)';
         $nextTile.css('opacity', _thisSlider.opacityLow);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + 0 + 'ms');
         setTimeout(next);
      });

      // The original tile disappears and the new tile is now animated.
      $nextTile.queue(_thisSlider.queueName, function(next) {
         $nextTile.animate({
            opacity: 1
         }, _thisSlider.durationMS / 2);
         var cssString = 'rotate3d(' + rotationAxis.concat(finalAngle).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + _thisSlider.durationMS / 2 + 'ms');
         setTimeout(next, _thisSlider.durationMS / 2);
      });
      
      $nextTile.queue(_thisSlider.queueName, function(next) {
         _thisSlider.decrementNActiveAnimations();
      });
      
      this.tilesToAnimate.push($tile); // no need to add $nextTile since it is started in the $tile queue.
      this.incrementNActiveAnimations();
   }
   
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
 * The old tile fade away to reveal the tiles of the new image.
 * 
 * Options:
 *    -'duration': the time it takes for a tile to fade, in milliseconds.  The default is 2000.
 */
   $.fn.shockwave.FadeSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'duration': 2000
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.FadeSlider;
      this.durationMS = settings['duration'];
   }

   $.fn.shockwave.FadeSlider.usesCSSTransforms3D = false;

   $.fn.shockwave.FadeSlider.prototype.setUpTileAnimation = function($tile, $nextTile) {
      var _thisSlider = this;
      $nextTile.css('z-index', '-1'); // make sure $nextTile is underneath $tile
      $tile.css('z-index', '1');
      
      // $nextTile just stays fixed in the background.
      $nextTile.queue(this.queueName, function(next) {
         $tile.parent().append($nextTile);
      });

      var center = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(center);
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms

      $tile.queue(_thisSlider.queueName, function(next) {
         setTimeout(next, delay);
      });
       
      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.css('opacity', 0);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transition', 'opacity ' + _thisSlider.durationMS + 'ms');
         setTimeout(next, _thisSlider.durationMS);
      });
   
      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.remove();
         _thisSlider.decrementNActiveAnimations();
      });
      
      this.tilesToAnimate.push($nextTile);
      this.tilesToAnimate.push($tile);   
      this.incrementNActiveAnimations(); // Only decrement on $tile, not $nextTile too.
   }
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
    * Tiles rotate in 3D with the original image on the front face and the new image on the back
    * face of the tile.
    * By default, the rotation axis of each tile is perpendicular to the incoming wave.
    * 
    * Options:
    *       'rotation-duration': time of animation, in ms.
    *       'transform-origin': standard CSS3 option: the pivot point for the rotation.
    *       'transition-timing-function': a standard CSS transition option.
    *       'fixed-rotation-axis': by default the tiles rotate on an axis perpendicular
    *                              to the wave.  This forces all tiles to rotate around the same
    *                              axis.  Specified as a three-dimensional vector: [x, y, 0].
    *                              The z-coordinate must be null.
    */
   $.fn.shockwave.FlipSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'rotation-duration': '700', // in ms
         'transform-origin': '50% 50% 0px', 
         'transition-timing-function': 'linear',
         'fixed-rotation-axis': undefined
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.FlipSlider;
      
      this.durationMS = settings['rotation-duration'];
      this.transformOrigin = settings['transform-origin'];
      this.easingFunction = settings['transition-timing-function'];
      this.fixedRotationAxis = settings['fixed-rotation-axis'];
   }
   
   $.fn.shockwave.FlipSlider.usesCSSTransforms3D = true;
   
   $.fn.shockwave.FlipSlider.prototype.setUpTileAnimation = function($tile, $nextTile, backward) {
      var tileCenter = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(tileCenter);
      if (this.fixedRotationAxis) {
         var rotationAxis = this.fixedRotationAxis;
      } else {
         var vector = this.wave.vector2DFromWaveOrigin(tileCenter);
         rotationAxis = [-vector[1], vector[0], 0]; // perpedicular to vector 
      }
         
      var switchingAngle = _switchingAngle($tile, $tile.parent(), rotationAxis);
      var switchingAngleNext = 180 + switchingAngle;
      var finalAngle = 360;
      if (backward) {
         switchingAngle = -180 + switchingAngle;
         switchingAngleNext = -180 + switchingAngle;
         finalAngle = -360;
      }
   
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms
      var duration1 = this.durationMS * Math.abs(switchingAngle / 180.0);
      var duration2 = this.durationMS - duration1;
              
      var _thisSlider = this;
              
      $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', _thisSlider.easingFunction);
      $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
              
      $tile.queue(this.queueName, function(next) {
         setTimeout(next, delay);
      });
               
      $tile.queue(this.queueName, function(next) {
         var cssString = 'rotate3d(' + rotationAxis.concat(switchingAngle).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + duration1 + 'ms');
         setTimeout(next, duration1);
      });
               
      $tile.queue(this.queueName, function(next) {
         $tile.replaceWith($nextTile);
         var cssString = 'rotate3d(' + rotationAxis.concat(switchingAngleNext).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + 0 + 'ms');
         $nextTile.dequeue(_thisSlider.queueName); // Starts the queue for the next image div.
      // next(); // Don't call next() since this image div won't even exist.
      });

      // The original tile disappeared and the new tile is now animated.
      $nextTile.queue(this.queueName, function(next) {
         // This has to be called on a 0-time setTimeout.
         setTimeout(function() {
            var cssString = 'rotate3d(' + rotationAxis.concat(finalAngle).toString() + 'deg)';
            $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
            $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + duration2 + 'ms');
         });
         setTimeout(next, duration2);
      });
      
           
      $nextTile.queue(this.queueName, function(next) {
         // This has to be called on a 0-time setTimeout.
         setTimeout(function() {
            var cssString = 'rotate3d(' + rotationAxis.concat(0).toString() + 'deg)';
            $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
            $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + 0 + 'ms');
         });
         _thisSlider.decrementNActiveAnimations();
      });
       

      // Do not also push $nextTile on animation queue since it is activated by the $tile queue (see above).
      this.tilesToAnimate.push($tile); 
      this.incrementNActiveAnimations(); // Only decrement on the $nextTile animations, so no need for +=2.
   }
   
   /**
    * Finds the angle at which $div rotated along rotationAxis will appear exactly perpendicular
    * from the perspective of the view defined in the $container.
    * Ignores the z-component of rotationAxis.
    */
   function _switchingAngle($div, $container, rotationAxis) {
      var containerPerspective = _containerPerspective($container);
      var objectCenter = $.fn.shockwave.centerRelativeToParent($div);
      var center = [objectCenter[0] - containerPerspective[0], objectCenter[1] - containerPerspective[1]];

      var shortestVectorToRotationAxis = {};
      if (Math.abs(rotationAxis[0] / rotationAxis[1]) < 0.001) { // rotate around Y
         shortestVectorToRotationAxis = [center[0], 0]; 
      } else if (Math.abs(rotationAxis[1] / rotationAxis[0]) < 0.001) { // rotate around X
         shortestVectorToRotationAxis = [0, center[1]];
      } else {
         // y = slope * x + b       equation of the rotation axis passing through the center of rotating object.
         var slope = rotationAxis[1] / rotationAxis[0];
         var b = center[1] - slope * center[0];
         // y = (-1 / slope) * x     equation of the vector starting at (0, 0) and reaching the line defined above perpendicularly.
         // The intersection of those 2 lines gives the shortestVectorToRotationAxis.
         var tempX = -b / (slope + 1.0 / slope);
         shortestVectorToRotationAxis = [tempX, -tempX / slope];
      }

      var distanceToAxisSquared = shortestVectorToRotationAxis.reduce(function(sum, coord) {
         return sum + coord * coord;
      }, 0);
      var distanceToAxis = Math.sqrt(distanceToAxisSquared);
      var angleRad = Math.atan(containerPerspective[2] / distanceToAxis);
      var angleDeg = angleRad * 360 / (Math.PI * 2);
      var crossProductZ = shortestVectorToRotationAxis[0] * rotationAxis[1] - 
      shortestVectorToRotationAxis[1] * rotationAxis[0];
      var directedAngleDeg = (crossProductZ < 0) ? (180 - angleDeg) : angleDeg;
      return directedAngleDeg;
   }
   
   /**
    * Returns the camera position (perspective) of the container.
    */
   function _containerPerspective($container) {
      var dz = undefined;
      var perpOrig = undefined;
      ['', '-webkit-', '-moz-', '-ms-', '-o-'].forEach(function(prefix) {
         var temp = $container.css(prefix + 'perspective');
         if (!!temp)
            dz = temp;
      });
      if (!dz)
         dz = '1000px';
      dz = dz.replace(/px/, '');
      ['', '-webkit-', '-moz-', '-ms-', '-o-'].forEach(function(prefix) {
         var temp = $container.css(prefix + 'perspective-origin');
         if (!!temp)
            perpOrig = temp;
      });
      if (!perpOrig)
         perpOrig = '50% 50%';  // default value
      var perspectiveOrigin = [];
      if (perpOrig.match(/%/)) {
         var tempArray = perpOrig.split(/%/);
         // TODO odd, before $...width() was a string with pixels.... $container.width().replace(/px/, '')
         perspectiveOrigin[0] = tempArray[0] * parseInt($container.width()) / 100.0;
         perspectiveOrigin[1] = tempArray[1] * parseInt($container.height()) / 100.0;
      } else {
         var tempArray2 = perpOrig.split(' ');
         perspectiveOrigin[0] = parseInt(tempArray2[0].replace(/px/, ''));
         perspectiveOrigin[1] = parseInt(tempArray2[1].replace(/px/, ''));
      }
      return [perspectiveOrigin[0], perspectiveOrigin[1], dz];
   }
   
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
 * The tile makes a full rotation to come back to its original position.  At the mid-trajectory,
 * the image is changed for the new image.
 * 
 * Options:
 *      'tiles-in-x': see the super-class.
 *      'rotation-duration'
 *      'transform-origin': standard CSS3 option. The pivot point for the rotation.
 *      'fixed-rotation-axis': if set to null, the rotation axis is perpendicular to the incoming wave.
 *      'opacity-low': the opacity reaches this low value at mid-trajectory.
 */
   $.fn.shockwave.FlipEdgeSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'tiles-in-x': 1,
         'tiles-in-y': 1,
         'rotation-duration': '700', // in ms
         'transform-origin': '0% 100% 0px', 
         'transition-timing-function': 'linear',
         'fixed-rotation-axis': [1, 0, 0],
         'opacity-low': 0.5
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.FlipSlider;
      
      this.durationMS = settings['rotation-duration'];
      this.transformOrigin = settings['transform-origin'];
      this.easingFunction = settings['transition-timing-function'];
      this.fixedRotationAxis = settings['fixed-rotation-axis'];
      this.opacityLow = settings['opacity-low'];
   }
   
   $.fn.shockwave.FlipEdgeSlider.usesCSSTransforms3D = true;
   
   $.fn.shockwave.FlipEdgeSlider.prototype.setUpTileAnimation = function($tile, $nextTile, backward) {
      var tileCenter = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(tileCenter);
      if (this.fixedRotationAxis)
         var rotationAxis = this.fixedRotationAxis;
      else {
         var vector = this.wave.vector2DFromWaveOrigin(tileCenter);
         rotationAxis = [-vector[1], vector[0], 0]; // perpedicular to vector 
      }
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms
      var $parent = $tile.parent();
      
      var midAngle = 180;
      var midAngleNext = -180;
      var finalAngle = 0;
      if (backward) {
         midAngle = -180;
         midAngleNext = 180;
      }

      var _thisSlider = this;
      
      $.fn.shockwave.cssAutomaticPrefix($tile, 'backface-visibility', 'visible');
      $.fn.shockwave.cssAutomaticPrefix($nextTile, 'backface-visibility', 'visible');
      $tile.queue(this.queueName, function(next) {
         setTimeout(next, delay);
      });

      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.animate({
            opacity: _thisSlider.opacityLow
         }, _thisSlider.durationMS / 2);
         var cssString = 'rotate3d(' + rotationAxis.concat(midAngle).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + _thisSlider.durationMS / 2 + 'ms');
         setTimeout(next, _thisSlider.durationMS / 2);
      });
      
      $tile.queue(_thisSlider.queueName, function(next) {
         $nextTile.dequeue(_thisSlider.queueName);
         $tile.remove();
      });

      $nextTile.queue(_thisSlider.queueName, function(next) {
         $parent.append($nextTile);
         var cssString = 'rotate3d(' + rotationAxis.concat(midAngleNext).toString() + 'deg)';
         $nextTile.css('opacity', _thisSlider.opacityLow);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + 0 + 'ms');
         setTimeout(next);
      });

      // The original tile disappears and the new tile is now animated.
      $nextTile.queue(_thisSlider.queueName, function(next) {
         $nextTile.animate({
            opacity: 1
         }, _thisSlider.durationMS / 2);
         var cssString = 'rotate3d(' + rotationAxis.concat(finalAngle).toString() + 'deg)';
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform-origin', _thisSlider.transformOrigin);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + _thisSlider.durationMS / 2 + 'ms');
         setTimeout(next, _thisSlider.durationMS / 2);
      });
      
      $nextTile.queue(_thisSlider.queueName, function(next) {
         _thisSlider.decrementNActiveAnimations();
      });
      
      this.tilesToAnimate.push($tile); // no need to add $nextTile since it is started in the $tile queue.
      this.incrementNActiveAnimations();
   }
   
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
 * The tile gets unhooked except for one pivot point and dangles around that point 
 * until it lets go.  There is some randomness on the timing of the fall.
 * 
 * Options:
 *     'pivot': the point that stays fixed, as [x, y] in pixels
 *     'random-time-to-unhook': the maximum random time before the tile falls.
 *     'free-fall-time': remove the tile after it has fallen for this much time, in milliseconds.
 *     'friction': slows the oscillation.
 */
   $.fn.shockwave.UnhookedSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'pivot' : [0, 0],
         'random-time-to-unhook': 2000, // in ms
         'free-fall-time': 500, // ms
         'friction': 0.5
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.UnhookedSlider;
      
      this.pivot = settings['pivot'];
      this.randomToUnhookMS =  settings['random-time-to-unhook'];
      this.freeFallTime = settings['free-fall-time'];
      this.friction = settings['friction'];
   }

   $.fn.shockwave.UnhookedSlider.usesCSSTransforms3D = false;

   $.fn.shockwave.UnhookedSlider.prototype.setUpTileAnimation = function($tile, $nextTile) {
      $nextTile.css('z-index', '-1'); // make sure $nextTile is underneath $tile
      $tile.css('z-index', '1');
      
      // $nextTile just stays fixed in the background.
      $nextTile.queue(this.queueName, function(next) {
         $tile.parent().append($nextTile);
      });

      var center = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(center);

      var width = $tile.width();
      var height = $tile.height();
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms
      var swingTime = Math.random() * this.randomToUnhookMS;

      $tile.queue(this.queueName, function(next) {
         setTimeout(next, delay);
      });
       
      var pivot = this.pivot;

      // Defined relative to top-left corner.
      var centerOfMass = [width / 2, height / 2];

      // theta is the angle of the line between the pivot and the center of mass relative to x-axis
      var pivotToCM = [centerOfMass[0] - pivot[0], centerOfMass[1] - pivot[1]];

      var thetaStart = Math.atan(pivotToCM[1] / pivotToCM[0]);
      if (thetaStart >=0 && pivotToCM[1] < 0)
         thetaStart += Math.PI;
      else if (thetaStart < 0 && pivotToCM[0] < 0)
         thetaStart += Math.PI;

      var omegaStart = 0;
      
      var gravity = 9.8 * 3000; // in pixels / s / s  
      var rho = 1.0; //  areal density of the tile.
      var mass = rho * width * height;
      // distance from pivot to center of mass:
      var radius = Math.sqrt(pivotToCM[0] * pivotToCM[0] + pivotToCM[1] * pivotToCM[1]);
      var momentOfInertia = _rectangleMomentOfInertia(rho, width, height, pivot);

      var dtMS = 15; //  The simulation time interval, in ms.
      var dt = dtMS / 1000;
      var dtBetweenAnimationsMS = 50; // The animation time interval.
      var theta = thetaStart;
      var omega = omegaStart; // d theta / dt
      var alpha; // d^2 theta / dt / dt
      var dtSinceLastAnimationMS = 0;
      var _thisSlider = this;
      for (var t_ms = 0; t_ms <= swingTime; t_ms += dtMS) {
         // Torque = m gravity R cos(theta)
         var torque = mass * gravity * Math.cos(theta) * radius;
         // Torque = momentOfInertia * alpha
         alpha = torque / momentOfInertia;
         // friction always in direction opposing omega
         domega = dt * alpha - this.friction * dt * omega;
         // below approximate since alpha varies over dt.
         dtheta = dt * omega + 0.5 * dt * dt * alpha;
         
         omega += domega;
         theta += dtheta;
         
         dtSinceLastAnimationMS += dtMS;
         if (dtSinceLastAnimationMS >= dtBetweenAnimationsMS) {
            var duration = dtSinceLastAnimationMS;
            dtSinceLastAnimationMS = 0;
            (function() { // hack to make a 'local' copy of theta to pass to function.
               var thetaLocal = theta;
               var deltaTheta = thetaLocal - thetaStart;
               var xx = -pivotToCM[0];
               var yy = -pivotToCM[1];
               var cos = Math.cos(deltaTheta);
               var sin = Math.sin(deltaTheta);
               var xTrans = xx - xx * cos + yy * sin;
               var yTrans = yy - yy * cos - xx * sin;
               
               var durationLocal = duration;

               $tile.queue(_thisSlider.queueName, function(next) {
                  var cssString = 'translate(' + (xTrans) + 'px, ' + (yTrans) + 'px)' + ' rotate(' + (deltaTheta) + 'rad)';
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transform-origin', '50% 50%');
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', 'linear');
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
                  $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + durationLocal + 'ms');
                  setTimeout(next, durationLocal);
               });
            }());
         }
      }
      
      // Careful, the transform-origin must be the same between the 2 sets of animation,
      // otherwise, some image flashes, which is in the completely wrong place.
      var velocityCM = [-omega * radius * Math.sin(theta), omega * radius * Math.cos(theta)];

      var momentOfInertiaCM = _rectangleMomentOfInertia(rho, width, height, centerOfMass);
      // Conservation of angular momentum
      var omegaCM = momentOfInertia * omega / momentOfInertiaCM;
      // centerOfMass still defined relative to original top-left corner.
      centerOfMass[0] = pivot[0] + radius * Math.cos(theta);
      centerOfMass[1] = pivot[1] + radius * Math.sin(theta);
      var thetaCM = theta - thetaStart;
      var finalTime = swingTime + this.freeFallTime; // in ms
      dtSinceLastAnimation = 0;
      for (t_ms = swingTime + dtMS; t_ms < finalTime; t_ms += dtMS) {
         thetaCM += dt * omegaCM;
         centerOfMass[0] += velocityCM[0] * dt;
         centerOfMass[1] += (velocityCM[1] * dt + 0.5 * gravity * dt * dt) ;
         velocityCM[1] += gravity * dt - this.friction * dt * velocityCM[1];

         dtSinceLastAnimationMS += dtMS;
         if (dtSinceLastAnimationMS >= dtBetweenAnimationsMS) {
            duration = dtSinceLastAnimationMS;
            dtSinceLastAnimationMS = 0;
            (function() { // hack to make a 'local' copy of theta to pass to function.
               var thetaLocal = thetaCM;
               var durationLocal = duration;
               var xLocal = centerOfMass[0] - width / 2;
               var yLocal = centerOfMass[1] - height / 2;

               $tile.queue(_thisSlider.queueName, function(next) {
                  var cssString = 'translate(' + (xLocal) + 'px, ' + (yLocal) + 'px)' + ' rotate(' + (thetaLocal) + 'rad)';
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transform-origin', '50% 50%');
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', 'linear');
                  $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
                  $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + durationLocal + 'ms');
                  setTimeout(next, durationLocal);
               });
            }());
         }
      }
      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.remove();
         _thisSlider.decrementNActiveAnimations();
      });
      
      this.tilesToAnimate.push($nextTile);
      this.tilesToAnimate.push($tile);   
      this.incrementNActiveAnimations(); // Only decrement on $tile being done.
   }

   // Moment of inertia: rho * h * w / 3 * ( (w - p.x)^3 + p.x^3 + (h - p.y)^3 + p.y^3 )
   function _rectangleMomentOfInertia(rho, width, height, pivot) {
      return rho * height * width / 3.0 * 
      [width - pivot[0], pivot[0], height - pivot[1], pivot[1]].reduce(function(cumul, value) {
         return cumul + value * value * value;
      }, 0);
   }
   
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
    * Rotating cube, with the old image on one face and the new one on the incoming face.
    * 
    * Options: 
    *    'rotation-axis':  'x': vertical movement, 'y': horizontal movement. 
    *    'rotation-duration'
    *    'transition-timing-function': standard CSS option.
    */
   $.fn.shockwave.CubeSlider = function($container, imageArray, options) {
      var settings = $.extend({
         'rotation-axis': 'y',     // 'x': vertical movement, 'y': horizontal movement 
         'rotation-duration': 2000,  // in ms
         'transition-timing-function': 'ease'
      }, options);
      var superr = new $.fn.shockwave.Slider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.CubeSlider;

      this.rotationAxis = settings['rotation-axis'];
      this.durationMS = settings['rotation-duration'];
      this.easingFunction = settings['transition-timing-function'];
   }
   
   $.fn.shockwave.CubeSlider.usesCSSTransforms3D = true;

   $.fn.shockwave.CubeSlider.prototype.setUpTileAnimation = function($tile, $nextTile, backward) {
      var width = $tile.width();
      var height = $tile.height();
      var depth = (this.rotationAxis == 'x') ? height : width;
      // cubeCenter in the coordinate system where the center of the original tile is the origin.
      var cubeCenter = [0, 0, -depth / 2];
      var angle = Math.PI / 2; // in radians
      if (backward) {
         angle = -Math.PI / 2;
      }
      this.setUpTileAnimation_($tile, $nextTile, cubeCenter, angle);
   }
   
   $.fn.shockwave.CubeSlider.prototype.setUpTileAnimation_ = function($tile, $nextTile, cubeCenter, angle) {
      var center = $.fn.shockwave.centerRelativeToParent($tile);
      var distanceToWaveOrigin = this.wave.distanceFromWaveOrigin(center);
      if (this.rotationAxis == 'x')
         var rotationVector = [1, 0, 0];
      else if (this.rotationAxis == 'y')
         rotationVector = [0, 1, 0];
      else
         throw new Error('The rotation axis should be x or y');
      
      var delay = distanceToWaveOrigin / this.wave.speed * 1000; // in ms

      // Below is necessary.  There is some odd bug and a surface that is occluded, but has its hidden face
      // facing up will appear in front of the object that is hiding it.
      $.fn.shockwave.cssAutomaticPrefix($tile, 'backface-visibility', 'hidden');
      $.fn.shockwave.cssAutomaticPrefix($nextTile, 'backface-visibility', 'hidden');

      $tile.queue(this.queueName, function(next) {
         setTimeout(next, delay);
      });
               
      // Somehow, the line below is necessary.  Otherwise, the first slide (and only the first slide),
      // follows a trajectory that is a bit off.
      $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', 
         'rotate3d(' + rotationVector.concat(0) + 'rad) ' + 'translate3d(' + [0, 0, 0] + ')');
               
      var _thisSlider = this;
      $tile.queue(this.queueName, function(next) {
         var cssString = $.fn.shockwave._cssRotateAround(cubeCenter, rotationVector, angle);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($tile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($tile, 'transition', 'transform ' + _thisSlider.durationMS + 'ms');
         setTimeout(next, _thisSlider.durationMS);
      });
      
      $tile.queue(_thisSlider.queueName, function(next) {
         $tile.remove();
         _thisSlider.decrementNActiveAnimations();
      });
  
      // Set up the initial position sideways.
      $nextTile.queue(_thisSlider.queueName, function(next) {
         var $container = $tile.parent();
         $container.append($nextTile);
         var cssString = $.fn.shockwave._cssRotateAround(cubeCenter, rotationVector, -angle);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + 0 + 'ms');
         $nextTile.css('visibility', 'hidden');
         setTimeout(next, delay);
      });

      // Just go to the position of the original tile. 
      $nextTile.queue(_thisSlider.queueName, function(next) {
         $nextTile.css('visibility', 'visible');
         var cssString = 'rotate3d(' + rotationVector.concat(0) + 'rad) ' + 'translate3d(' + [0, 0, 0] + ')';
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transition-timing-function', _thisSlider.easingFunction);
         $.fn.shockwave.cssAutomaticPrefix($nextTile, 'transform', cssString);
         $.fn.shockwave.cssAutomaticPrefixAndValue($nextTile, 'transition', 'transform ' + _thisSlider.durationMS + 'ms');
         setTimeout(next, _thisSlider.durationMS);
      });
      
      $nextTile.queue(_thisSlider.queueName, function(next) {
         _thisSlider.decrementNActiveAnimations();
      });
      
      this.tilesToAnimate.push($tile); 
      this.tilesToAnimate.push($nextTile);
      this.incrementNActiveAnimations();
      this.incrementNActiveAnimations(); // Must be called twice since decrement is called twice.
   }
   
   /**
 * Gives a css string consisting of a rotation (at the origin) followed by a translation, 
 * which is equivalent to a rotation relative to the point rotationCenter.
 */
   $.fn.shockwave._cssRotateAround = function(rotationCenter, rotationVector, angle) {
      var rotatedCubeCenter = $.fn.shockwave._rotateVector(rotationCenter, rotationVector, angle);
      var translation = []; // translation to make rotation relative to cube center instead of to the origin.
      rotationCenter.forEach(function(value, coord) {
         translation[coord] = (rotationCenter[coord] - rotatedCubeCenter[coord]);
      });
      var translationInRotatedCoords = $.fn.shockwave._rotateVector(translation, rotationVector, -angle);
      translationInRotatedCoords.forEach(function(value, index) {
         translationInRotatedCoords[index] += 'px';
      });
      var cssString = 'rotate3d(' + rotationVector.concat(angle) + 'rad) ' + 
      'translate3d(' + translationInRotatedCoords + ')';
      return cssString;
   }
   
   /**
 * Rotates vector v around rotAxis by angle.  rotAxis does not have to be normalized.
 * angle is in radians.
 */
   $.fn.shockwave._rotateVector = function(v, rotAxis, angle) {
      // Use Rodrigues' formula: v_rot = v cos + (e x v) sin + e (e.v)(1 - cos)
      // Normalize the rotation axis as 'e'
      var eLength = rotAxis.reduce(function(cumul, value) {
         return cumul + value * value;
      }, 0);
      var e = rotAxis.map(function(x) {
         return x / eLength;
      });
      // Scalar product
      var ev = e.reduce(function(cumul, value, coord) {
         return cumul + e[coord] * v[coord];
      });
      // Cross product
      var exv = [];
      v.forEach(function(value, coord) {
         var i1 = (coord + 1) % 3;
         var i2 = (coord + 2) % 3;
         exv[coord] = e[i1] * v[i2] - e[i2] * v[i1];
      });
      
      var cos = Math.cos(angle);
      var sin = Math.sin(angle);
      
      var vRotated = [];
      v.forEach(function(value, coord) {
         vRotated[coord] = v[coord] * cos + exv[coord] * sin + e[coord] * ev * (1 - cos);
      });
      return vRotated;
   }
   
   //////////////////////////////////////////////////////////////////////////////////////
   /**
    * Very similar to CubeSlider, but with triangular prism.  It can be made to look like
    * a rotating billboard.
    * 
    * Options: 
    *    see the options of CubeSlider
    */
   $.fn.shockwave.BillboardSlider = function($container, imageArray, options) {
      var settings = options;
      var superr = new $.fn.shockwave.CubeSlider($container, imageArray, settings);
      for (var property in superr) {
         if (this[property] === undefined)
            this[property] = superr[property];
      }
      this.constructor = $.fn.shockwave.BillboardSlider;
   }
   
   $.fn.shockwave.BillboardSlider.usesCSSTransforms3D = true;

   $.fn.shockwave.BillboardSlider.prototype.setUpTileAnimation_ = 
   $.fn.shockwave.CubeSlider.prototype.setUpTileAnimation_;
   
   $.fn.shockwave.BillboardSlider.prototype.setUpTileAnimation = function($tile, $nextTile, backward) {
      
      var width = $tile.width();
      var height = $tile.height();
      var depth = ((this.rotationAxis == 'x') ? height : width) / 2 * Math.tan(Math.PI / 6);
      // cubeCenter in the coordinate system where the center of the original tile is the origin.
      var cubeCenter = [0, 0, -depth];
      var angle = 2 * Math.PI / 3; // in radians
      if (backward) {
         angle = -2 * Math.PI / 3;
      }
      this.setUpTileAnimation_($tile, $nextTile, cubeCenter, angle);
   }
})();
   

