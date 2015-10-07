(function($) {
	$.fn.toggler = function() 
	{
		var button = this;

		// INIT
		var container = $(this).data('toggle-container');
		if(!container) { container = $(this).closest('.toggle-container'); }

		var icon = $(this).data('toggle-icon');
		var fa_icon = $(this).data('toggle-fa-icon');
		var title = $(this).data('toggle-title');
		var close_icon = $(this).data('toggle-close');
		var close_fa_icon = $(this).data('toggle-fa-close');
		if(!close_icon) { close_icon = 'remove'; }

		if(icon) { $(this).html("<i class='glyphicon glyphicon-"+icon+"'></i>"); }
		if(fa_icon) { $(this).html("<i class='fa fa-"+fa_icon+"'></i>"); }
		if(title) { $(this).attr('title', title); }

		$(document).on('click', function(e) {
			if($(e.target).is(button)) { return; }
			var opened = $(container).hasClass('active');
			var click_inside = $(e.target).closest('.toggle-container').size();

			if(opened && !click_inside)
			{
				$(button).click(); // Close
			}
		});

		$(this).on('click',function(e) {
			e.stopPropagation();

			var fa_icon = $(this).data('toggle-fa-icon');
			var fa_close_icon = $(this).data('toggle-fa-close');
	
			var title = $(this).data('toggle-title');
	
			if($(container).hasClass("active"))
			{
				// XXX TODO slide in direction attached.
				// convert to translate()
				$(container).removeClass("active");
			} else {
				$(container).addClass("active");

				icon = close_icon;
				fa_icon = fa_close_icon;
				title = 'Close';
			}
			if(fa_icon) { $(this).html("<i class='fa fa-"+fa_icon+"'></i>"); }
			else if(icon) { $(this).html("<i class='glyphicon glyphicon-"+icon+"'></i>"); }
			if(title) { $(this).attr('title', title); }
		});
	};

	$(document).ready(function() {
		$('.toggler').toggler();
	});
})(jQuery);
