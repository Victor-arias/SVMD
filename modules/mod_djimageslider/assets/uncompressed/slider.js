/**
 * @version 2.0.2.stable
 * @package DJ-ImageSlider
 * @subpackage DJ-ImageSlider Component
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

(function($){

var DocumentLoaded = false;

window.addEvent('load',function(){DocumentLoaded = true});

this.DJImageSlider = new Class({

    initialize: function(settings, options){

        var slider_size = 0;
        var loaded_images = 0;
        var max_slides = 0;
        var current_slide = 0;
        var slider = 'slider' + settings.id;
        var autoplay = options.auto;
        var stop = 0;
        var show_nav = 0;
		var is_fading = false;
        
        $('djslider' + settings.id).fade('hide');
        
        var slides = $('slider'+ settings.id).getChildren('li');
        
        if (Browser.ie8) { // only for IE8
			var visibles = new Array();
			for (var i = 0; i < settings.visible_slides; i++) {
				visibles[i] = slides[i];
				visibles[i].fade('hide');
			}
		}
			
        slides.each(function(){
            slider_size += settings.slide_size;
            loaded_images++;
        });
        
        max_slides = loaded_images - settings.visible_slides;
		
        $(slider).setStyle('position', 'relative');
		
        var slideImages;
		if (settings.slider_type == 2) { // fade
			slides.setStyle('position', 'absolute');
			slides.setStyle('top', 0);
			slides.setStyle('left', 0);
			$(slider).setStyle('width', settings.slide_size);
			slides.setStyle('opacity',0);
			slides.setStyle('visibility','hidden');
			slides[0].setStyle('opacity',1);
			slides[0].setStyle('visibility','visible');
			slides.set('tween',{property: 'opacity', duration: options.duration});
						
		} else if (settings.slider_type == 1) { // vertical
            $(slider).setStyle('top', 0);
            $(slider).setStyle('height', slider_size);
            slideImages = new Fx.Tween(slider, {
				property: 'top', 
                duration: options.duration,
                transition: options.transition,
                link: 'cancel'
            });
        }
        else { // horizontal
            $(slider).setStyle('left', 0);
            $(slider).setStyle('width', slider_size);
            slideImages = new Fx.Tween(slider, {
				property: 'left', 
                duration: options.duration,
                transition: options.transition,
                link: 'cancel'
            });
        }
        
		// navigation effects
		if (settings.show_buttons==1) {
			var play = new Fx.Tween('play' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
			var pause = new Fx.Tween('pause' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
		}
		if (settings.show_arrows==1) {
			var nextFx = new Fx.Tween('next' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
			var prevFx = new Fx.Tween('prev' + settings.id, {
				property: 'opacity', 
				duration: 200,
				link: 'cancel'
			}).set('opacity',0);
		}
		
        if(settings.show_arrows) {
	        $('next' + settings.id).addEvent('click', function(){
	            if (settings.show_buttons==1) hideNavigation();
	            nextSlide();
	        });        
	        $('prev' + settings.id).addEvent('click', function(){
	            if (settings.show_buttons==1) hideNavigation();
	            prevSlide();
	        });
        }
        if(settings.show_buttons) {
        	$('play' + settings.id).addEvent('click', function(){
	            changeNavigation();
	            autoplay = 1;
	        });        
	        $('pause' + settings.id).addEvent('click', function(){
	            changeNavigation();
	            autoplay = 0;
	        });
        }
        
		$('djslider-loader' + settings.id).addEvents({
            'mouseenter': function(){
                if (settings.show_buttons==1) showNavigation();
				if (settings.show_arrows==1) {
					nextFx.start(1);
					prevFx.start(1);
				}
				stop = 1;
            },
            'mouseleave': function(){
                if (settings.show_buttons==1) hideNavigation();
				if (settings.show_arrows==1) {
					nextFx.start(0);
					prevFx.start(0);
				}
				stop = 0;
            },
            'swipe': function(event){
				if(event.direction == 'left') {
					nextSlide();
				} else if(event.direction == 'right') {
					prevSlide();
				}
			}
        });
        $('djslider-loader' + settings.id).store('swipe:cancelVertical', true);

		if($('cust-navigation' + settings.id)) {
			var buttons = $('cust-navigation' + settings.id).getElements('.load-button');
			buttons.each(function(el,index){
				el.addEvent('click',function(e){
					if (!is_fading && !el.hasClass('load-button-active')) {
						loadSlide(index);
					}
				});
			});
		}
		
		function responsive(){
			
			var parent = $('djslider-loader' + settings.id).getParent();
			var parentWidth = parent.getSize().x;
			parentWidth -= parent.getStyle('padding-left').toInt();
			parentWidth -= parent.getStyle('padding-right').toInt();
			parentWidth -= parent.getStyle('border-left').toInt();
			parentWidth -= parent.getStyle('border-right').toInt();
			
			var maxWidth = $('djslider' + settings.id).getStyle('max-width').toInt();
			var size = $('djslider' + settings.id).getSize();
			var newSliderWidth = size.x;
			
			if(newSliderWidth > parentWidth) {
				newSliderWidth = parentWidth;
			} else if(newSliderWidth <= parentWidth && newSliderWidth < maxWidth){
				newSliderWidth = (parentWidth > maxWidth ? maxWidth : parentWidth);
			}
			
        	var ratio = size.x / size.y;
			var newSliderHeight = newSliderWidth / ratio;
			
			$('djslider' + settings.id).setStyle('width', newSliderWidth);
			$('djslider' + settings.id).setStyle('height', newSliderHeight);
			
        	if (settings.slider_type == 2) { // fade
        		
				$(slider).setStyle('width', newSliderWidth);
				slides.setStyle('width', newSliderWidth);
				slides.setStyle('height', newSliderHeight);
				
			} else if (settings.slider_type == 1) { // vertical
				
				var space = slides[0].getStyle('padding-bottom').toInt();
				settings.slide_size = (newSliderHeight + space) / settings.visible_slides;
				slider_size = loaded_images * settings.slide_size + loaded_images;
		        $(slider).setStyle('height', slider_size);
		        
		        slides.setStyle('width', newSliderWidth);
				slides.setStyle('height', settings.slide_size - space);
				slideImages.set(-settings.slide_size * current_slide);
		        
		    } else { // horizontal
		    	
		    	var space = slides[0].getStyle('padding-right').toInt();
		    	settings.slide_size = (newSliderWidth + space) / settings.visible_slides;
		    	slider_size = loaded_images * settings.slide_size + loaded_images;
		        $(slider).setStyle('width', slider_size);
		        
		        slides.setStyle('width', settings.slide_size - space);
				slides.setStyle('height', newSliderHeight);
				slideImages.set(-settings.slide_size * current_slide);
		    }
		    
		    if(settings.show_buttons || settings.show_arrows) {
				
				// get some vertical space for navigation				
	        	button_pos = $('navigation' + settings.id).getPosition('djslider' + settings.id).y;	        	
				if(button_pos < 0) {					
					$('djslider-loader' + settings.id).setStyle('padding-top', -button_pos);
					$('djslider-loader' + settings.id).setStyle('padding-bottom', 0);										
				} else {
					buttons_height = 0;
					if(settings.show_arrows) {
						buttons_height = $('next' + settings.id).getSize().y;
						buttons_height = Math.max(buttons_height,$('prev' + settings.id).getSize().y);
					}
					if(settings.show_buttons) {
						buttons_height = Math.max(buttons_height,$('play' + settings.id).getSize().y);
						buttons_height = Math.max(buttons_height,$('pause' + settings.id).getSize().y);
					}				
					padding = button_pos + buttons_height - newSliderHeight;
					if(padding > 0) {
						
						$('djslider-loader' + settings.id).setStyle('padding-top', 0);
						$('djslider-loader' + settings.id).setStyle('padding-bottom', padding);
						
					} else {
						$('djslider-loader' + settings.id).setStyle('padding-top', 0);
						$('djslider-loader' + settings.id).setStyle('padding-bottom', 0);
					}
				}
	        	
	        	// put navigation inside the slider if it's wider than window 
	        	buttons_margin = $('navigation' + settings.id).getStyle('margin-left').toInt() + $('navigation' + settings.id).getStyle('margin-right').toInt();
				if(buttons_margin < 0 && window.getSize().x < $('navigation' + settings.id).getSize().x - buttons_margin) {
					
					$('navigation' + settings.id).setStyle('margin-left',0);
					$('navigation' + settings.id).setStyle('margin-right',0);
				}				
			}
		}
		
		function updateActiveButton(active){
			if($('cust-navigation' + settings.id)) buttons.each(function(button,index){
				button.removeClass('load-button-active');
				if(index==active) button.addClass('load-button-active');
			});			
		}
		
		function nextSlide(){
			if (current_slide < max_slides) loadSlide(current_slide + 1);
			else loadSlide(0);
        }
        
        function prevSlide(){
			if (current_slide > 0) loadSlide(current_slide - 1);
			else loadSlide(max_slides);
        }
        	
		function loadSlide(index) {
			if(current_slide == index) return;
			
			if (settings.slider_type == 2) {
				if(is_fading) return;
				is_fading = true;
				prev_slide = current_slide;
				current_slide = index;
				makeFade(prev_slide);				
			} else {
				current_slide = index;
				slideImages.start(-settings.slide_size * current_slide);
			}
			updateActiveButton(current_slide);
		}
			
		function makeFade(prev_slide){
			slides[current_slide].setStyle('visibility','visible');
			slides[current_slide].get('tween').start(1);
			slides[prev_slide].get('tween').start(0).chain(function(){
				slides[prev_slide].setStyle('visibility','hidden');
				is_fading = false;
			});
		}
		
        function hideNavigation(){
            if (!autoplay) {
                play.start(stop, 0).chain(function(){
                    if (!show_nav) 
                        $('play' + settings.id).setStyle('display', 'none');
                });
            }
            else {
                pause.start(stop, 0).chain(function(){
                    if (!show_nav) 
                        $('pause' + settings.id).setStyle('display', 'none');
                });
            }
            show_nav = 0;
        }
        
        function showNavigation(){
            if (!autoplay) {
                $('play' + settings.id).setStyle('display', 'block');
                play.start(stop, 1);
            }
            else {
                $('pause' + settings.id).setStyle('display', 'block');
                pause.start(stop, 1);
            }
            show_nav = 1;
        }
        function changeNavigation(){
            if (autoplay) {
                $('pause' + settings.id).setStyle('display', 'none');
                if (settings.show_buttons==1) pause.set('opacity',0);
                $('play' + settings.id).setStyle('display', 'block');
                if (settings.show_buttons==1) play.set('opacity',1);
            }
            else {
                $('play' + settings.id).setStyle('display', 'none');
                if (settings.show_buttons==1) play.set('opacity',0);
                $('pause' + settings.id).setStyle('display', 'block');
                if (settings.show_buttons==1) pause.set('opacity',1);
            }
        }
        
        function slidePlay(){
            setTimeout(function(){
                if (autoplay && !stop) 
                    nextSlide();
                slidePlay();
            }, options.delay);
        }
		
		function sliderLoaded(){
			// hide loader and show slider
			$('djslider-loader' + settings.id).setStyle('background','none');
			
			$('djslider' + settings.id).fade('in');
			
			if (Browser.ie8) { // only for IE8
				visibles.each(function(el){
					if(el) el.fade('in');
				});
			}
			
			responsive();
			
			if(settings.show_buttons) {
				
				play_width = $('play' + settings.id).getSize().x;
				$('play' + settings.id).setStyle('margin-left',-play_width/2);
				pause_width = $('play' + settings.id).getSize().x;
				$('pause' + settings.id).setStyle('margin-left',-pause_width/2);
				
				if(autoplay) {
					$('play' + settings.id).setStyle('display','none');
				} else {
					$('pause' + settings.id).setStyle('display','none');
				}
			}
			
			// start autoplay
			slidePlay();
		}
		
		if(settings.preload) sliderLoaded.delay(settings.preload);
		//else if (DocumentLoaded) sliderLoaded();
		else window.addEvent('load', sliderLoaded);
		
		window.addEvent('resize', responsive);
        
    }
    
});

})(document.id);