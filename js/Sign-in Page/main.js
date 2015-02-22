var navBarChanger = function() {

		// grab the initial top offset of the navigation 
		if($('.masthead').offset()) {

			var sticky_navigation_offset_top = 0;
			var sticky_navigation = function(){
				var scroll_top = $(window).scrollTop(); 
				
				if (scroll_top > sticky_navigation_offset_top) { 
					$('.masthead').css({ 'position': 'fixed', 'top': 0 });
					$('.masthead-brand').hide();
					$('.masthead-nav').css({'background-color': 'rgba(0, 0, 0, 0.8)', 'width': '100%'});
					$('.innerHeader').css({'padding': 0});
				} else {
					$('.masthead').css({ 'position': 'fixed', 'top': 0 });
					$('.masthead-brand').show();
					$('.masthead-nav').css({'background-color': '', 'width': ''});
					$('.innerHeader').css({'padding': 30});
				}   
			};
			
			sticky_navigation();
			
			$(window).scroll(function() {
				 sticky_navigation();
			});
		}	
};

$( document ).ready(function() {
	navBarChanger();
});