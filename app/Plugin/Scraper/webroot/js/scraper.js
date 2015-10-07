(function($) {
	$.scraper = function(url, callback)
	{
		var params = { link_url: url }; // must be qstring param
		$.get("/scraper/scraper", params, callback);
	};

	/*
	$.fn.scraper = function(url, field) // if called on an item, then set to value of title or whatever other field, if found.
	{
		if(!field) { field = 'title'; }

		$(this).waitable(); // add waiter. (dont duplicate!)

		var id = $(this).attr('id');
		var waiter = $("#"+id+"_waiter"); // only if there already.

		$(waiter).visible();

		$.scraper(url, function(data) {
			$(waiter).invisible();
			if($(this).is("input, textarea"))
			{
				$(this).val(data[field]);
			} else {
				$(this).html(data[field]);
			}
		});
	};
	*/
})(jQuery);
