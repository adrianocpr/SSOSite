$(document).ready(function(){
	
	var swiper = new Swiper(".big-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        keyboard: {
			enabled: true,
        },
        pagination: {
			el: ".big-slider-pagination",
			clickable: true,
        },
        navigation: {
			nextEl: ".big-slider-next",
			prevEl: ".big-slider-prev",
        },
    });
	
	$('.widget-top').MVisionToggleClass({
		classButton: 'widget-top__switch-button',
		toggleClassButton: 'active',
		dataButtonAttr: 'data-open-tab',
		classBox: 'widget-top__box',
		toggleClassBox: 'active',
		dataBoxAttr: 'data-name-tab',
		defaultElement: true,
		defaultIndexElement: 0,
		ancoreLinks: false,
	});
	
	$('.rating').MVisionToggleClass({
		classButton: 'rating__tabs-item',
		toggleClassButton: 'active',
		dataButtonAttr: 'data-open-tab',
		classBox: 'rating__table-bg',
		toggleClassBox: 'active',
		dataBoxAttr: 'data-name-tab',
		defaultElement: true,
		defaultIndexElement: 0,
		ancoreLinks: true,
	});
	
	$('.promo__player-btn').click(function(event){
		event.preventDefault();
		let video_player = '<iframe width="100%" height="100%" src="'+$('.video-bg__box').attr('data-youtube-url')+'?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		$(".video-bg__box").append(video_player);
		$('.video-bg').addClass("active");
		
	});
	
	$('.video-bg__cancel').click(function(event){
		if($('.video-bg').hasClass('active')){
			$('.video-bg').removeClass("active");
		
			setTimeout(function(){
				$(".video-bg__box").html("");
			}, 300);
		}
	});
	
	$(".macguide__title").click(function(){
		$(this).parent().find(".macguide__content").slideToggle();
		$(this).find(".macguide__content").removeAttr('style');
	});
	
	$(".macguide__content-open-screenshot").click(function(){
		var screen_id = $(this).attr('data-drop-box');
		$(".macguide__content-screenshot[data-drop-box-name = " + screen_id + "]").slideToggle();
	});
	
});


