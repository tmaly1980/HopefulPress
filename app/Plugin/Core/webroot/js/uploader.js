(function($) {
	$.fn.uploader = function(url, opts) {
		// either appends to a list of items/photos/etc or loads into a modal, depending on target passed
		var id = $(this).prop('id');
		if(!id)
		{
			id = 'file'+parseInt(Math.random()*10000);
			$(this).prop('id',id);
		}

		var target = $('#'+opts.target); // THEY need to leave out the '#'

		$('#'+id).after("<div class='error_msg' id='"+id+"_msg'></div>"+
			'<div class="clear"></div>'+
			'<div style="display: none;" id="'+id+'_loading" class="loading"><img src="/images/loading.gif" style="vertical-align: middle;"/> Uploading...</div>'
			);


		$('#'+id).fileupload({
			dataType: 'json',
			url: url,
			autoUpload: true,
			recalculateProgress: false,
			formData: function(form)
			{
				// Create placeholder on form. So next item can properly count ix, if all at once.
				var uid = parseInt(Math.random()*100000)
				var data = form.serializeArray();
				if(opts.append)
				{
					for(var k in opts.append)
					{
						data.push({ name: k, value: opts.append[k]});
					}
				}
				var ix = $('#'+opts.target).find('> *').size() - 1; // Add index for proper form display
				$('#'+opts.target).append("<div id='upload-placeholder-"+ix+"' class='item'></div>");
				data.push({ name: "data[ix]", value: ix });
				return data;
			},
			add: function(e, data)
			{
				// XXX TODO to ensure the first pic gets the first item (regardless of upload size)
				// we have to give a number to it that is known at done() time...
				data.submit();
			},
			send: function() { 
				$('#'+id+'_msg').html('');
				$('#'+id+'_loading').show();
				if(console.log) { console.log("SENDING"); }
				return true; 
			},
			fail: function (e,data)
			{
				$('#'+ id +'_loading').hide();
				$('#'+id+'_msg').html('Upload failed: Invalid response');
				if(console.log) { console.log(data); }
			},
			done: function(e, data) {
				$('#'+id +'_loading').hide();
				if(console.log)
				{
					//console.log("DONE!");
					//console.log(data);
				}
				if(!data.result) { 
					$('#'+id+'_msg').html('Upload failed: Invalid response...');
					return; 
				}
				if(data.result.error)
				{
					$('#'+id+'_msg').html(data.result.error);
				}
				if (data.result.content) { 
					// here we determine how we respond.
					//
					if(opts.target == 'modal')
					{
						//console.log("MODAL!!!");
						//console.log(data.result.content);
						// load modal.
						$('#modal').html(data.result.content);
						//console.log("DONE HTML");
						//console.log(target.html());

						$.modalopen();
						$('#modal').trigger('dialogresize'); // center.
						// title should be set via results...
						
					} else { // assume append to list.
						var ix = data.result.ix;
						console.log("IX="+ix);
						var placeholder = $('#'+opts.target).find('div#upload-placeholder-'+ix).first();
						if(placeholder.size())
						{
							console.log("UPLOAD PLACEHOLDER FOUND");
							placeholder.replaceWith(data.result.content);
						} else {
							console.log("UPLOAD placeholder NOT NOT NOT FOUND =(");
							$('#'+opts.target).append(data.result.content);
						}

						// Now fade in new item
						var item = $('#'+opts.target+' > *:last');
						item.hide();

						//console.log(item);

						console.log("HIDING BECAUSE TRYING TO DO FADE_IN");

						if(item.find('img').size()) // photo content. wait until image loaded to show/fade in
						{
							console.log("TRYING TO TRIGGER FADE_IN AFTER IMG LOAD");
							//item.find('img').bind('load', function() { // may be a difference between bind('load', and load()
							item.find('img').bind('load', function() {
								console.log("LOADED, FADING IN....");
								item.fadeIn('fast'); // Fade in AFTER img loaded.
								//$(window).scrollTo(item, 200, { offset: -200 });
							});
						} else {
							console.log("JUST FADE_IN NROMAL");
							item.fadeIn('fast'); // Fade in AFTER img loaded.
							//$(window).scrollTo(item, 200, { offset: -200 });

						}
						$('#'+opts.target+' .nodata').hide(); // Hide "no items" msg

						$('#'+opts.target).trigger('updated');
					}
					

					// Make the last item visible on screen.

				}
			}
		});

	};
})(jQuery);
