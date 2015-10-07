(function($) {
	$(document).ready(function() {
		$('.dropdown.hover').hover(function() {
			$(this).find("> .dropdown-menu").show();
		}, function() {
			$(this).find("> .dropdown-menu").hide();
		});

		$('.dropdown.toggle > a').click(function() {
			var menu = $(this).parent().find("> .dropdown-menu");
			if(menu.is(":visible"))
			{
				menu.hide();
			} else {
				$('.dropdown-menu').hide();
				menu.show();
			}
		});
		$(document).on('click', function(e) { 
			if(!$(e.target).closest('.dropdown.toggle').size() && $('.dropdown.toggle .dropdown-menu:visible').size()) // clicked outside of open dropdown menu 
			{ // need to ensure that not inside dropdown, so initial show() works
				$('.dropdown.toggle .dropdown-menu:visible').hide();
				
			}
		});

	});
})(jQuery);
