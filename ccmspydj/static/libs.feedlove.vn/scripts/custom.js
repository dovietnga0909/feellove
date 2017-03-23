////////////////////////////////////////
//waitForImages jQuery Plugin 2015-02-25
////////////////////////////////////////
!function(a){var b="waitForImages";a.waitForImages={hasImageProperties:["backgroundImage","listStyleImage","borderImage","borderCornerImage","cursor"],hasImageAttributes:["srcset"]},a.expr[":"].uncached=function(b){if(!a(b).is('img[src][src!=""]'))return!1;var c=new Image;return c.src=b.src,!c.complete},a.fn.waitForImages=function(){var c,d,e,f=0,g=0,h=a.Deferred();if(a.isPlainObject(arguments[0])?(e=arguments[0].waitForAll,d=arguments[0].each,c=arguments[0].finished):1===arguments.length&&"boolean"===a.type(arguments[0])?e=arguments[0]:(c=arguments[0],d=arguments[1],e=arguments[2]),c=c||a.noop,d=d||a.noop,e=!!e,!a.isFunction(c)||!a.isFunction(d))throw new TypeError("An invalid callback was supplied.");return this.each(function(){var i=a(this),j=[],k=a.waitForImages.hasImageProperties||[],l=a.waitForImages.hasImageAttributes||[],m=/url\(\s*(['"]?)(.*?)\1\s*\)/g;e?i.find("*").addBack().each(function(){var b=a(this);b.is("img:uncached")&&j.push({src:b.attr("src"),element:b[0]}),a.each(k,function(a,c){var d,e=b.css(c);if(!e)return!0;for(;d=m.exec(e);)j.push({src:d[2],element:b[0]})}),a.each(l,function(c,d){var e,f=b.attr(d);return f?(e=f.split(","),void a.each(e,function(c,d){d=a.trim(d).split(" ")[0],j.push({src:d,element:b[0]})})):!0})}):i.find("img:uncached").each(function(){j.push({src:this.src,element:this})}),f=j.length,g=0,0===f&&(c.call(i[0]),h.resolveWith(i[0])),a.each(j,function(e,j){var k=new Image,l="load."+b+" error."+b;a(k).one(l,function m(b){var e=[g,f,"load"==b.type];return g++,d.apply(j.element,e),h.notifyWith(j.element,e),a(this).off(l,m),g==f?(c.call(i[0]),h.resolveWith(i[0]),!1):void 0}),k.src=j.src})}),h.promise()}}(jQuery);


///////////
//VAR SETUP
///////////
var theWindow = jQuery(window),
	theBody = jQuery('body'),
	contentContainer = jQuery('#contentContainer'),
	contentCover = jQuery('#contentCover'),
	headerImages = jQuery('#headerImages'),
	headerImage = headerImages.children(),
	footer = jQuery('#footerContainer'),
	audioPlayer = jQuery("#audioPlayer"),
	audioControl = jQuery('#audioControl'),
	audioSpan = jQuery('#audioControl span'),
	menuControl = jQuery('#menu-control'),
	sidebar = jQuery('#sidebar'),
	sidebarWidgets = jQuery('#sidebar-widgets'),
	loadingPage = jQuery('#loading-page'),
	fullSearch = jQuery('#full-search'),
	bigInput = jQuery('#big-input'),
	reviewinterval = '',
	headerinterval = '';


//////////////////
//REVIEWS INTERVAL -- runReviews
//////////////////
function runReviews(){
	if(jQuery('.review').length > 1){
		clearInterval(reviewinterval);
		reviewinterval = setInterval(function(){ reviewsSlideshow(); }, 8000);
	} else {
		clearInterval(reviewinterval);
	}
}


/////////////////////
//BACKGROUND INTERVAL -- runHeader
/////////////////////
function runHeader(){
	if(headerImage.length > 1 && !theBody.hasClass('stopSlideshow') ){
		clearInterval(headerinterval);
		headerinterval = setInterval(function(){ imageSlideshow(); }, 8000);
	} else {
		clearInterval(headerinterval);
	}
}
	
	
//////////////////////////////
//RUN SCRIPTS AFTER AJAX LOADS -- ajaxComplete
//////////////////////////////
jQuery(document).ajaxComplete(function() {
	bodyCheck();
	runReviews();
	runHeader(); 
});


//////////////////
//VIDEO BACKGROUND -- background
//////////////////
if (!headerImage.length >= 1 ) {
	headerImages.background({
		source: {
			poster: "images/bridge.jpg",
			mp4: 	"http://127.0.0.1:8000/static/libs.feedlove.vn/videos/video-1485616278.mp4"
		}
	});
}
	
	
///////////////////
//LOADING ANIMATION
///////////////////
var opts = {
  lines: 13, // The number of lines to draw
  length: 8, // The length of each line
  width: 3, // The line thickness
  radius: 15, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  direction: 1, // 1: clockwise, -1: counterclockwise
  color: '#fff', // #rgb or #rrggbb or array of colors
  speed: 1, // Rounds per second
  trail: 100, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: '50%', // Top position relative to parent
  left: '50%' // Left position relative to parent
};
var target = document.getElementById('loading-page');
var spinner = new Spinner(opts).spin(target);


//////////////////////////////////
//LOADING/SEARCH BACKGROUND UPDATE -- loadingBg
//////////////////////////////////
function loadingBg(){
	var activeBgUrl = jQuery('.activeBg').children('img').attr('src'),
		videoBgUrl = jQuery('.video-image-container').children('img').attr('src'),
		galleryBgUrl = jQuery('.activeImg').children('img').attr('src'),
		pageBgUrl = jQuery('.page-image-container').children('img').attr('src');	
	
	if 		(typeof pageBgUrl != 'undefined') 		loadingPage.add(fullSearch).css({backgroundImage:'url('+pageBgUrl+')'});
	else if (typeof galleryBgUrl != 'undefined') 	loadingPage.add(fullSearch).css({backgroundImage:'url('+galleryBgUrl+')'});
	else if (typeof videoBgUrl != 'undefined') 		loadingPage.add(fullSearch).css({backgroundImage:'url('+videoBgUrl+')'}); 
	else if (typeof activeBgUrl != 'undefined') 	loadingPage.add(fullSearch).css({backgroundImage:'url('+activeBgUrl+')'}); 
	else 											loadingPage.add(fullSearch).css({backgroundImage:'none'});
}


/////////////////
//KEY PRESS STUFF
/////////////////
jQuery(document).keyup(function(e) {
	//ESCAPE KEY
	if (e.keyCode == 27) {
		if ( theBody.hasClass('search-open') ) 			closeSearch();
		if ( theBody.hasClass('full-screen-video') ) 	closeVideo();
		if ( theBody.hasClass('open-sidebar') ) 		sideClose();
	}
});


/////////////////////////
//SIDEBAR WIDGET POSITION -- widgetPos
/////////////////////////
function widgetPos(){
	sidebarWidgets.css({ marginTop : "0px" });
	
	var sidebarH = sidebar.outerHeight(),
		widgetTop = sidebarWidgets.offset().top,
		widgetH = sidebarWidgets.outerHeight(),
		extraSpace = ( sidebarH - ( widgetTop + widgetH ) );
	
	if( extraSpace > 0 ) sidebarWidgets.css({ marginTop : extraSpace + "px" });
}

    
//////////////////
//BODY CLASS STUFF -- bodyCheck
//////////////////
function bodyCheck(){	

	//UPDATE BODY CLASS
	var newClass = jQuery('#page-info').attr('class');
	theBody.removeClass().addClass(newClass);
	
	//OS CHECK
	mobileCheck();
	osCheck();
	
	//PLAY/PAUSE VIDEO/SLIDESHOW BACKGROUND
	if( jQuery('.page-image-container').length > 0 || theBody.hasClass('page-template-page-videos') || theBody.hasClass('page-template-page-gallery') ) {
		//PAUSE BG SLIDESHOW
		theBody.addClass('stopSlideshow');
		
		//PAUSE BG VIDEO
		pauseBgVideo();
	} else {
		//PLAY BG VIDEO
		playBgVideo();
	}	
}


/////////////////////////////
//AJAX -- CATEGORY PAGINATION -- load-more click
/////////////////////////////
jQuery(document).on('click','#load-more',function(){  
 
	if (!jQuery('#load-more').hasClass(".loading")) {
		
		//VAR SETUP
	   	var thisLink = jQuery('#load-more a'),
	   		url = thisLink.attr('href');
		
		//SHOW LOADING TEXT
		thisLink.parent().addClass('loading');		
		
		//ADD DIV FOR NEW ITEMS
		jQuery('.scroll-this').append('<div id="newStuff"></div>');
		
		var newStuff = jQuery('#newStuff');
		
		//LOAD NEW ITEMS
		newStuff.hide().load(url + " .scroll-this > div",function(){
		
			//WAIT FOR IMAGES TO LOAD
			newStuff.waitForImages(function() {
			
				//REMOVE OLD LINK
				jQuery('.loading').remove();
				
				//UNWRAP
				jQuery('#newStuff > div').unwrap().hide().fadeIn(300);
				
				//RUN FUNCTIONs
				postsCenter();
				postOverflowCheck();
				letMyPeopleScroll();
				scrollOnce();
			});
		});
    }//END CHECK
    
    return false;
});


//////////////////////////////
//AJAX -- SEARCH RESULTS STUFF -- full-search submit
//////////////////////////////
jQuery(document).on('submit',"#full-search",function(){
   
   //AJAX CHECK
   if(theBody.hasClass('ajax-on')){
   
	   //VAR SETUP
	   	var searchVal = bigInput.val(),
	   		adjustedVal = encodeURIComponent(searchVal),
	   		url = siteUrl + '?s=' + adjustedVal;
	   		
	   	if(jQuery.trim(bigInput.val()).length > 0) {
	   	
	   		//LOSE FOCUS
	   		bigInput.blur();
			
			//SCROLL TO TOP
			jQuery("html,body").stop().animate({scrollTop:0},1500);
			
			//UPDATE LOADING BG
			loadingBg();
		
			//LOADING NOTICE
			loadingPage.stop(true,true).fadeIn(500,function(){
			
				//PAUSE BG VIDEO
				pauseBgVideo();
			
				//CLOSE SEARCH
				closeSearch();
			
				//UPDATE URL
				if(url!=window.location && history.pushState) window.history.pushState({path:url},'',url);
				
				//ADD/REMOVE THE AJAX CONTAINER
				jQuery("#ajax-content").remove();
				
				contentContainer.append('<div id="load-here"></div>');
			
				var loadHere = jQuery('#load-here');
			
				//POSITION THE AJAX CONTAINER
				loadHere.load(url + " #ajax-content",null, function() {
				
					//UPDATE PAGE TITLE						
					document.title = jQuery('#page-info').data('page-title');
				
					//PLAY BG VIDEO
					playBgVideo();
				
					//CHECK IF CONTENT EXISTS
					if(loadHere.html().length > 0){	
					
						//WAIT FOR IMAGES TO LOAD
						loadHere.waitForImages(function() {
			
			    			//REMOVE WRAPPER
				    		loadHere.replaceWith(loadHere.html());
				    		
				    		//RESET SCROLL POSITION
							jQuery('.scroll-wrapper').scrollLeft(0);
							jQuery('.scroll-this > div').scrollTop(0);
				    		
				    		//LOADING NOTICE
				    		loadingPage.stop().fadeOut(800,function(){
				    			loadingBg();
				    		});
				    	
				    		//REMOVE CURRENT MENU CLASS
				   			jQuery('.current-menu-item').removeClass('current-menu-item');
							
							//RUN FUNCTION
							pageCenter();
							postsCenter();
							galleryCheck();
							postOverflowCheck();
							letMyPeopleScroll();
							scrollOnce();
						});
						
					} else {
					
						//LOADING NOTICE
			    		loadingPage.stop().fadeOut(800);
			    		
			    		//404 ERROR
			    		alert('Nothing found.');
					
					}//END CONTENT CHECK
		   		});//END AJAX LOAD
			});//END LOADING FADE IN
			
			return false;
		
		} else {
			
			//EMPTY ALERT
			alert('Search field is empty.');
			
			return false;
		
		}//END SEARCH VALUE CHECK
	
	}//END AJAX ON CHECK
});


/////////////////////////////
//AJAX -- GENERAL CLICK STUFF -- a click
/////////////////////////////
jQuery(document).on('click',"a",function(){
   
   //VAR SETUP
   	var thisLink = jQuery(this),
   		thisParent = thisLink.parent().attr('id'),
   		url = thisLink.attr('href');
	
	//DETERMINE IF PROPER SITE LINK
	if(thisLink.attr('id') != 'link-rss' && url.indexOf(siteUrl) == 0 && thisParent != "load-more" && url.indexOf("#") == -1 && url.indexOf("wp-admin") == -1 && url.indexOf(".jpeg") == -1 && url.indexOf(".png") == -1 && url.indexOf(".gif") == -1 && url.indexOf(".jpg") == -1 && url.indexOf(".pdf") == -1 && url.indexOf("mailto") == -1 && theBody.hasClass('ajax-on')){
		
		//CLOSE SIDEBAR
		sideClose();
		
		//UPDATE LOADING BG
		loadingBg();
			
		//LOADING NOTICE
		loadingPage.stop(true,true).fadeIn(500,function(){
		
			//PAUSE BG VIDEO
			pauseBgVideo();
		
			//CLOSE SEARCH
			closeSearch();
		
			//UPDATE URL
			if(url!=window.location && history.pushState) window.history.pushState({path:url},'',url);
			
			//ADD/REMOVE THE AJAX CONTAINER
			jQuery("#ajax-content").remove();
			
			contentContainer.append('<div id="load-here"></div>');
		
			var loadHere = jQuery('#load-here');
		
			//POSITION THE AJAX CONTAINER
			loadHere.load(url + " #ajax-content",null, function() {
			
				//UPDATE PAGE TITLE						
				document.title = jQuery('#page-info').data('page-title');
			
				//PLAY BG VIDEO
				playBgVideo();
			
				//CHECK IF CONTENT EXISTS
				if(loadHere.html().length > 0){		
				
					//WAIT FOR IMAGES TO LOAD
					loadHere.waitForImages(function() {
	    	
		    			//REMOVE WRAPPER
			    		loadHere.replaceWith(loadHere.html());
			    		
			    		//RESET SCROLL POSITION
						jQuery('.scroll-wrapper').scrollLeft(0);
						jQuery('.scroll-this > div').scrollTop(0);
			    		
			    		//LOADING NOTICE
			    		loadingPage.stop().fadeOut(800,function(){
			    			loadingBg();
			    		});
			    		
			    		//UPDATE CURRENT MENU ITEM
			    		if(!thisLink.hasClass('pagenav')){
			    			//REMOVE CURRENT CLASS
			   				jQuery('.current-menu-item').removeClass('current-menu-item');
			   				
			   				//CHECK IF URL MATCHES MENU ITEM URL
							jQuery('#dropmenu a').each(function(){
								if(jQuery(this).attr('href') == url){
									jQuery(this).parent().addClass('current-menu-item');
								}				
							});
						}
						
						//RUN FUNCTION
						pageCenter();
						postsCenter();
						galleryCheck();
						postOverflowCheck();
						letMyPeopleScroll();
						scrollOnce();
					});
					
				} else {
				
					//LOADING NOTICE
		    		loadingPage.stop().fadeOut(800);
		    		
		    		//404 ERROR
		    		alert('Nothing found.');
		    		
				}//END CONTENT CHECK
	   		});//END AJAX LOAD
   		});//END LOADING FADE IN
		
		return false;
	}//END IF PROPER SITE LINK
});


/////////////////////
//BROWSER BUTTON AJAX
/////////////////////
theWindow.on("popstate", function(e) {
  	//VAR SETUP
   	var thisLink = location.href,
   		url = location.href;
	
	//DETERMINE IF PROPER SITE LINK
	if(url.indexOf(siteUrl) == 0 && url.indexOf("#") == -1 && url.indexOf("wp-admin") == -1 && url.indexOf(".jpeg") == -1 && url.indexOf(".png") == -1 && url.indexOf(".gif") == -1 && url.indexOf(".jpg") == -1 && url.indexOf(".pdf") == -1 && url.indexOf("mailto") == -1 && theBody.hasClass('ajax-on')){
		
		//CLOSE SIDEBAR
		sideClose();
		
		//UPDATE LOADING BG
		loadingBg();
			
		//LOADING NOTICE
		loadingPage.stop(true,true).fadeIn(500,function(){
		
			//PAUSE BG VIDEO
			pauseBgVideo();
		
			//CLOSE SEARCH
			closeSearch();
		
			//UPDATE URL
			if(url!=window.location && history.pushState) window.history.pushState({path:url},'',url);
			
			//ADD/REMOVE THE AJAX CONTAINER
			jQuery("#ajax-content").remove();
			
			contentContainer.append('<div id="load-here"></div>');
		
			var loadHere = jQuery('#load-here');
		
			//POSITION THE AJAX CONTAINER
			loadHere.load(url + " #ajax-content",null, function() {
			
				//UPDATE PAGE TITLE						
				document.title = jQuery('#page-info').data('page-title');
			
				//PLAY BG VIDEO
				playBgVideo();
			
				//CHECK IF CONTENT EXISTS
				if(loadHere.html().length > 0){		
				
					//WAIT FOR IMAGES TO LOAD
					loadHere.waitForImages(function() {
	    	
		    			//REMOVE WRAPPER
			    		loadHere.replaceWith(loadHere.html());
			    		
			    		//RESET SCROLL POSITION
						jQuery('.scroll-wrapper').scrollLeft(0);
						jQuery('.scroll-this > div').scrollTop(0);
			    		
			    		//LOADING NOTICE
			    		loadingPage.stop().fadeOut(800,function(){
			    			loadingBg();
			    		});
			    		
			    		
		    			//REMOVE CURRENT CLASS
		   				jQuery('.current-menu-item').removeClass('current-menu-item');
		   				
		   				//CHECK IF URL MATCHES MENU ITEM URL
						jQuery('#dropmenu a').each(function(){
							if(jQuery(this).attr('href') == url){
								jQuery(this).parent().addClass('current-menu-item');
							}				
						});
					
						//RUN FUNCTION
						pageCenter();
						postsCenter();
						galleryCheck();
						postOverflowCheck();
						letMyPeopleScroll();
						scrollOnce();
					});
					
				} else {
				
					//LOADING NOTICE
		    		loadingPage.stop().fadeOut(800);
		    		
		    		//404 ERROR
		    		alert('Nothing found.');
		    		
				}//END CONTENT CHECK
	   		});//END AJAX LOAD
   		});//END LOADING FADE IN
		
		return false;
	}//END IF PROPER SITE LINK
});


///////////////////
//FULL SEARCH STUFF -- closeSearch, link-search click, full-search click
///////////////////
//CLOSE FUNCTION
function closeSearch(){
	fullSearch.stop(true,true).fadeOut(350);
	bigInput.blur();
	theBody.removeClass('search-open');
}
//BUTTON CLICK
jQuery(document).on('click','#link-search',function(){
	if(theBody.hasClass('search-open')){
		closeSearch();
	} else {
		loadingBg();
		fullSearch.stop(true,true).fadeIn(350);
		bigInput.val('').focus();
		theBody.addClass('search-open');
		if( !theBody.hasClass('mobile-device') ) jQuery("html,body").stop().animate({scrollTop:0},1500);
	}
	
	return false;
});
//FORM BG CLICK
jQuery(document).on('click','#full-search',function(e){
	if (!jQuery(e.target).is("#big-input")) closeSearch();
});

/////////////////////////
//HORIZONTAL SCROLL STUFF -- click/hover, postOverflowCheck, scrollOnce, letMyPeopleScroll
/////////////////////////
//CLICK RIGHT
jQuery(document).on('click','#slide-right',function(){
	var scrollAmt = theWindow.width() * .37;
	clearInterval(rightScroll);
	jQuery('.scroll-wrapper').stop().animate({scrollLeft:'+='+scrollAmt+'px'},800);
});
//CLICK LEFT
jQuery(document).on('click','#slide-left',function(){
	var scrollAmt = theWindow.width() * .37;
	clearInterval(leftScroll);
	jQuery('.scroll-wrapper').stop().animate({scrollLeft:'-='+scrollAmt+'px'},800);
});
//MOUSE ENTER RIGHT
jQuery(document).on('mouseenter','#slide-right',function(){
	rightScroll = setInterval(function(){
		jQuery('.scroll-wrapper').stop().animate({scrollLeft:'+=150px'},350, 'linear');
	},350);
});
//MOUSE LEAVE RIGHT
jQuery(document).on('mouseleave','#slide-right',function(){
	jQuery('.scroll-wrapper').stop();
	clearInterval(rightScroll);
});
//MOUSE ENTER  LEFT
jQuery(document).on('mouseenter','#slide-left',function(){
	leftScroll = setInterval(function(){
		jQuery('.scroll-wrapper').stop().animate({scrollLeft:'-=150px'},350, 'linear');
	},350);
});
//MOUSE ENTER LEFT
jQuery(document).on('mouseleave','#slide-left',function(){
	jQuery('.scroll-wrapper').stop();
	clearInterval(leftScroll);
});
//OVERFLOW CHECK
function postOverflowCheck(){
	var posts = jQuery('.posts-container .post, .posts-container .page');
	
	//RESET
	jQuery('.scroll-notice').remove();
	posts.removeClass('overflowin');
	
	posts.each(function(){
		var thisPost = jQuery(this);
		if(thisPost.get(0).scrollHeight > (thisPost.height() + 3) && !thisPost.hasClass('scrolled')){
			thisPost.addClass('overflowin').append('<div class="scroll-notice">+</div>');
		}
	});
}
//SCROLL NOTICE CLICK
jQuery(document).on('click','.scroll-notice',function(){
	var thisNotice = jQuery(this),
		thisPost = thisNotice.parent(),
		thisPostH = thisPost.height() * .6;
	
	thisNotice.stop(true,true).fadeOut(500);
	thisPost.stop(true,true).animate({scrollTop:'+='+thisPostH+'px'},500);
	
});
//SCROLL NOTICE SCROLL
function scrollOnce(){
	jQuery(".posts-container .post, .posts-container .page").one('scroll', function() {
		var thisPost = jQuery(this),
			thisNotice = thisPost.children('.scroll-notice');
		
		thisPost.addClass('scrolled');
		thisNotice.stop().fadeOut(500);
		
	});
}
//LEFT/RIGHT SCROLL HIDE/SHOW
function letMyPeopleScroll(){
	if( jQuery('.scroll-this').width() > theWindow.width() ){
		jQuery('.slide-nav').stop(true,true).fadeIn(300);
	} else {
		jQuery('.slide-nav').stop(true,true).fadeOut(300);
	}
}
      

///////////////
//GALLERY STUFF -- galleryCheck
///////////////
function galleryCheck(){

	galleryImg = jQuery('.gallery-image');
		
	if(galleryImg.length > 0) {
	
		//VAR SETUP
		nextImg = jQuery('#nextImg');
		prevImg = jQuery('#prevImg');
		firstImg = galleryImg.first();
		lastImg = galleryImg.last();
		imageInfo = jQuery('#imgInfo');
		numberItems = galleryImg.length;
	
		//ASSIGN ACTIVE IMAGE
		firstImg.addClass('activeImg');
		
		//HIDE ALL OTHER IMAGES
		galleryImg.not('.activeImg').hide();
		
		var imgTitle = firstImg.data('imgtitle'),
			imgCaption = firstImg.data('caption'),
			itemNumber = firstImg.index() + 1;
			
		if(imgTitle.length > 0 && imgCaption.length > 0){
			imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2><p>"+imgCaption+"</p>").fadeIn(150);
		} else if(imgTitle.length > 0){
			imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2>");
		}
	}
}
//NEXT CONTROLS
jQuery(document).on('click','#nextImg',function(){
	var activeImg = jQuery('.activeImg'),
		nextLi = activeImg.next();
		
	if(nextLi.length > 0){
		activeImg.removeClass('activeImg');
		nextLi.addClass('activeImg').stop(true,true).fadeIn(800,function(){
			activeImg.hide();
		});
		
		var imgTitle = nextLi.data('imgtitle'),
			imgCaption = nextLi.data('caption'),
			itemNumber = nextLi.index() + 1;
		
	} else {
		activeImg.removeClass('activeImg');
		firstImg.addClass('activeImg').stop(true,true).fadeIn(800,function(){
			activeImg.hide();
		});
		
		var imgTitle = firstImg.data('imgtitle'),
			imgCaption = firstImg.data('caption'),
			itemNumber = firstImg.index() + 1;
	}	
	
	//CHANGE TITLE INFO	
	if(imgTitle.length > 0 && imgCaption.length > 0){
		imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2><p>"+imgCaption+"</p>");
	} else if(imgTitle.length > 0){
		imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2>");
	}	
});		
//PREV CONTROLS
jQuery(document).on('click','#prevImg',function(){
	var activeImg = jQuery('.activeImg'),
		prevLi = activeImg.prev();
		
	if(prevLi.length > 0){
		activeImg.removeClass('activeImg');
		prevLi.addClass('activeImg').stop(true,true).fadeIn(800,function(){
			activeImg.hide();
		});
		
		var imgTitle = prevLi.data('imgtitle'),
			imgCaption = prevLi.data('caption'),
			itemNumber = prevLi.index() + 1;
			
	} else {
		activeImg.removeClass('activeImg');
		lastImg.addClass('activeImg').stop(true,true).fadeIn(800,function(){
			activeImg.hide();
		});
		
		var imgTitle = lastImg.data('imgtitle'),
			imgCaption = lastImg.data('caption'),
			itemNumber = lastImg.index() + 1;
	}
	
	//CHANGE TITLE INFO	
	if(imgTitle.length > 0 && imgCaption.length > 0){
		imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2><p>"+imgCaption+"</p>");
	} else if(imgTitle.length > 0){
		imageInfo.html("<small>" + itemNumber + " / " + numberItems + "</small><h2>" + imgTitle + "</h2>");
	}
});

	
///////////////
//SIDEBAR STUFF -- sideOpen, sideClose
///////////////
var sidebarSpeed = 350,
	sidebarWidth = 300;
//OPEN FUNCTION
function sideOpen(){
	theBody.addClass('open-sidebar');
	contentCover.stop().fadeIn(sidebarSpeed);
	contentContainer.stop().animate({right:sidebarWidth + 'px'},sidebarSpeed);
}
//CLOSE FUNCTION
function sideClose(){
	menuControl.stop().fadeIn(sidebarSpeed);
	contentCover.stop().fadeOut(sidebarSpeed);
	contentContainer.stop().animate({right:0},sidebarSpeed,function(){
		theBody.removeClass('open-sidebar');
	});
	jQuery("html,body").stop().animate({scrollTop:0},1500);
}
//HAMBURGER CLICK
jQuery(document).on('click','#menu-control',function(){
	if( theBody.hasClass('open-sidebar') ) sideClose();
	else sideOpen();
});	
//CLOSE CLICKs
jQuery(document).on('click','#contentCover',function(){
	sideClose();
	return false;
});

	
/////////////
//AUDIO STUFF -- playAudio, pauseAudio, playingBar, pauseBar, hoverBar, click/hover, pauseIfPlaying
/////////////
//PLAY
function playAudio(){
	audioPlayer[0].play();
	audioPlayer.animate({volume: .35}, 1000);
	audioControl.removeClass('paused').addClass('playing');
	audioSpan.each(function(i) {
  		playingBar(jQuery(this));
	});
}
//PAUSE
function pauseAudio(){
	audioPlayer.animate({volume: 0}, 1000, function() {
		audioPlayer[0].pause();
		audioControl.removeClass('playing').addClass('paused');
	});
	pauseBar();
}
//CLICK
audioControl.click(function() {
	if(audioControl.hasClass('playing')) pauseAudio();
	else playAudio();
});
//HOVER
audioControl.hover(function(){
	if(audioControl.hasClass('paused')){
		audioSpan.each(function(i) {
	  		hoverBar(jQuery(this));
		});
	}
},function(){
	if(audioControl.hasClass('paused')) pauseBar();
});
//LOOP
if(audioPlayer.length > 0){
	audioPlayer[0].loop = true;
}
//PLAYING FUNCTION
function playingBar(bar) {
  var height = Math.random() * 20 + 3;
  var timing = height * 10;
  
  bar.animate({
      height: height
  }, timing, function() {
      playingBar(jQuery(this));
  });
}
//PAUSE FUNCTION
function pauseBar() {
	audioSpan.stop(true,false).animate({height:'8px'},500);
}
//HOVER FUNCTION
function hoverBar(bar) {
  var height = Math.random() * 20 + 3;
  var timing = height * 10;
  
  bar.stop(true,true).animate({
      height: height
  }, timing);
}
//PAUSE IF PLAYING
function pauseIfPlaying(){
	if(audioControl.hasClass('playing')){ 
		pauseAudio(); 
		audioControl.addClass('holding'); 
	}
}


////////////////////////
//BACKGROUND VIDEO STUFF -- pauseBgVideo, playBgVideo
////////////////////////
function pauseBgVideo(){
	if(headerImages.hasClass('wallpaper')) headerImages.wallpaper("stop");
}
function playBgVideo(){
	if(headerImages.hasClass('wallpaper')) headerImages.wallpaper("play");
}


//////////////////////
//CATEGORY VIDEO STUFF -- post-video click
//////////////////////
jQuery(document).on('click','.post-video',function(){
	var thisVideo = jQuery(this),
		videoType = thisVideo.data('vidtype'),
		videoId = thisVideo.data('vidid'),
		videoContainer = jQuery('.videoContainer');
		
	jQuery("html,body").stop().animate({scrollTop:0},1500);
		
	//LOAD PLAYER
	if(videoType == 'self-video') {
	
		videoContainer.append('<video class="postVideo selfVid" width="320" height="240" controls autoplay><source src="' + videoId + '" type="video/mp4">Your browser does not support the video tag.</video> ');
	
	} else if(videoType == 'youtube-video') {
	
		videoContainer.append('<iframe class="postVideo youTubeVid" src="https://www.youtube.com/embed/' + videoId + '?rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
	
	} else if(videoType == 'vimeo-video') {
		
		videoContainer.append('<iframe class="postVideo vimeoVid" src="//player.vimeo.com/video/' + videoId + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" width="500" height="209" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		
	}
 	
	//FADE IN VIDEO
	videoContainer.stop(true,true).fadeIn(300,function(){
		//PAUSE BG VIDEO
		pauseBgVideo();
	});
	
	//PAUSE AUDIO IF APPLICABLE
	pauseIfPlaying();
	
	//ADD CLASS
	theBody.addClass('full-screen-video');
	
	//CLOSE SELF-HOSTED VIDEO WHEN FINISHED
	if(videoType == 'self-video') {
		var videoTag = document.getElementsByTagName('video')[0];
		videoTag.onended = function(e) {
		  closeVideo();
		};
	}
	return false;
});


//////////////////
//VIDEO PAGE STUFF -- videoLink click, closeVideo
//////////////////
jQuery(document).on('click','.videoLink',function(){
	var linkId = jQuery(this).attr('id'),
		videoContainer = jQuery('.videoContainer'),
		videoId = videoContainer.data('vidid');
		
	jQuery("html,body").stop().animate({scrollTop:0},1500);
		
	//LOAD PLAYER
	if(videoContainer.hasClass('self-video')) {
	
		videoContainer.append('<video class="postVideo selfVid" width="320" height="240" controls autoplay><source src="' + videoId + '" type="video/mp4">Your browser does not support the video tag.</video> ');
	
	} else if(videoContainer.hasClass('youtube-video')) {
	
		videoContainer.append('<iframe class="postVideo youTubeVid" src="https://www.youtube.com/embed/' + videoId + '?rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
	
	} else if(videoContainer.hasClass('vimeo-video')) {
		
		videoContainer.append('<iframe class="postVideo vimeoVid" src="//player.vimeo.com/video/' + videoId + '?autoplay=1&color=ffffff&title=0&byline=0&portrait=0&badge=0" width="500" height="209" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		
	}
 	
	//FADE IN VIDEO
	videoContainer.stop(true,true).fadeIn(300,function(){
		//PAUSE BG VIDEO
		pauseBgVideo();
	});
	
	//PAUSE AUDIO IF APPLICABLE
	pauseIfPlaying();
	
	//ADD CLASS
	theBody.addClass('full-screen-video');
	
	//CLOSE SELF-HOSTED VIDEO WHEN FINISHED
	if(videoContainer.hasClass('self-video')) {
		var videoTag = document.getElementsByTagName('video')[0];
		videoTag.onended = function(e) {
		  closeVideo();
		};
	}
		
	return false;
});
//CLOSE VIDEO FUNCTION
function closeVideo(){

	var videoContainer = jQuery('.videoContainer');
	
	//PLAY BG VIDEO
	playBgVideo();
	
	//FADE OUT VIDEO
	videoContainer.stop(true,true).fadeOut(300,function(){
		jQuery('.postVideo').remove();
	});
	
	//PLAY AUDIO IF APPLICABLE
	if(audioControl.hasClass('holding')){ 
		playAudio(); 
		audioControl.removeClass('holding'); 
	}
	
	theBody.removeClass('full-screen-video');
}
//CLOSE VIDEO CLICK
jQuery(document).on('click','.closeVideo',function(){
	closeVideo();
});


/////////////////
//CHECK IF MOBILE
/////////////////
function mobileCheck(){
	var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
	
	if(mobile) theBody.addClass('mobile-device');
}
mobileCheck();


//////////
//OS CHECK
//////////
function osCheck(){
	if (navigator.appVersion.indexOf("Win")!=-1) theBody.addClass("windowsOs");
	else if (navigator.appVersion.indexOf("Mac")!=-1) theBody.addClass("macOs");
	else if (navigator.appVersion.indexOf("X11")!=-1) theBody.addClass("unixOs");
	else if (navigator.appVersion.indexOf("Linux")!=-1) theBody.addClass("linuxOs");
}
osCheck();


/////////////////////////////		
//CONTAINER MARGIN FOR FOOTER -- footerHeight
/////////////////////////////	
/*function footerHeight(){
	var footerHeight = footer.outerHeight();

	if(!theBody.hasClass('mobile-device')) theBody.css({paddingBottom:footerHeight+"px"});
}*/


//////////////////////////
//IMAGE SLIDESHOW FUNCTION -- imageSlideshow
//////////////////////////
function imageSlideshow(){
	var firstBg = headerImage.first();
		activeBg = jQuery('.activeBg');
	
	if(!activeBg.length > 0){
		firstBg.addClass('activeBg');
		var activeBg = firstBg;
	}

	var nextBg = activeBg.next();
	
	if(nextBg.length > 0){
		activeBg.removeClass('activeBg');
		nextBg.addClass('activeBg').stop(true,true).fadeIn(3000,function(){
			activeBg.hide();
		});
	} else {
		activeBg.removeClass('activeBg');
		firstBg.addClass('activeBg').stop(true,true).fadeIn(3000,function(){
			activeBg.hide();
		});
	}
};

////////////////////////////
//REVIEWS SLIDESHOW FUNCTION -- reviewsSlideshow
////////////////////////////
function reviewsSlideshow(){
	var firstReview = jQuery('.review').first();
		activeReview = jQuery('.activeReview');
	
	if(!activeReview.length > 0){
		firstReview.addClass('activeReview');
		var activeReview = firstReview;
	}

	var nextReview = activeReview.next();
	
	if(nextReview.length > 0){
		activeReview.removeClass('activeReview').stop(true,true).fadeOut(1500,function(){
			nextReview.addClass('activeReview').stop(true,true).fadeIn(1500);
		});
	} else {
		activeReview.removeClass('activeReview').stop(true,true).fadeOut(1500,function(){
			firstReview.addClass('activeReview').stop(true,true).fadeIn(1500);
		});
	}
};


///////////////////////////
//CONTAINER HEIGHT FUNCTION -- containerHeight
///////////////////////////
function containerHeight(){

	var winHeight = jQuery(window).height() - 170;

	contentContainer.css({height: winHeight +'px'});
}
containerHeight();


////////////////////////
//PAGE CONTENT FUNCTIONS -- pageCenter
////////////////////////
function pageCenter(){
	var pageContent = jQuery('#pageContent'); 
	
	if(pageContent.length > 0){
		var pageTop = '-' + (pageContent.outerHeight() / 2) + 'px',
			pageHeight = pageContent[0].scrollHeight;
	
		pageContent.css({marginTop:pageTop});
				
		if(pageHeight > 420){
			pageContent.addClass('with-scrollbar');
		} else {
			pageContent.removeClass('with-scrollbar');
		}
	}
}
pageCenter();


/////////////////
//POSTS FUNCTIONS -- postsCenter
/////////////////
function postsCenter(){
	var postContent = jQuery('.posts-container');
	
	if(postContent.length > 0){
		var postTop = '-' + (postContent.outerHeight() / 2) + 'px';
	
		postContent.css({marginTop:postTop});			
	}
}
postsCenter();


//////////////
//WINDOW STUFF
//////////////
theWindow.resize(function(){

	//RUN FUNCTIONs
	containerHeight();
	footerHeight();
	pageCenter();
	postsCenter();
	postOverflowCheck();
	letMyPeopleScroll();
	widgetPos();
		
}).load(function(){

	//RESET SCROLL POSITION
	jQuery('.scroll-wrapper').scrollLeft(0);
	jQuery('.scroll-this > div').scrollTop(0);
	
	//LOADING STUFF
	loadingPage.stop(true,true).fadeOut(800,function(){
		loadingBg();
	});

	//RUN FUNCTION
	footerHeight();
	pageCenter();
	postsCenter();
	galleryCheck();
	postOverflowCheck();
	scrollOnce();
	letMyPeopleScroll();
	widgetPos();
	bodyCheck();
	runHeader(); 
	runReviews();
	
	//PLAY AUDIO
	if(!jQuery('body').hasClass('mobile-device')) playAudio();
		
	//PLAY VIDEO
	//jQuery('.videoLink').click();
		
});