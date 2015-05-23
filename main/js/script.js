/***************** Waypoints ******************/

jQuery(document).ready(function(){

	jQuery('.wp1').waypoint(function() {
		jQuery('.wp1').addClass('animated fadeInLeft');
	}, {
		offset: '75%'
	});
	jQuery('.wp2').waypoint(function() {
		jQuery('.wp2').addClass('animated fadeInUp');
	}, {
		offset: '75%'
	});
	jQuery('.wp3').waypoint(function() {
		jQuery('.wp3').addClass('animated fadeInDown');
	}, {
		offset: '55%'
	});
	jQuery('.wp4').waypoint(function() {
		jQuery('.wp4').addClass('animated fadeInDown');
	}, {
		offset: '75%'
	});
	jQuery('.wp5').waypoint(function() {
		jQuery('.wp5').addClass('animated fadeInUp');
	}, {
		offset: '75%'
	});
	jQuery('.wp6').waypoint(function() {
		jQuery('.wp6').addClass('animated fadeInDown');
	}, {
		offset: '75%'
	});

});

/***************** Slide-In Nav ******************/

jQuery(window).load(function() {

	jQuery('.nav_slide_button').click(function() {
		jQuery('.pull').slideToggle();
	});

});

/***************** Smooth Scrolling ******************/

jQuery(function() {

	jQuery('a[href*=#waypoint_]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {

			var target = jQuery(this.hash);
			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				jQuery('html,body').animate({
					scrollTop: target.offset().top
				}, 2000);
				return false;
			}
		}
	});

});
