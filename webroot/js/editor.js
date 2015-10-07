(function($) { 
	$(document).ready(function() {
		//$('.summernote').summernote();
		/*
		var form = $("<form enctype='multipart/form-data' id='EditorUploadForm' action='/page_photos/page_photos/upload' style='width: 0px; height: 0px; overflow: hidden;' method='post json'></form>");
		var fileinput = $("<input type='file' onChange='$(this).closest(\"form\").submit();'/>");
		var hidden = $("<input type='hidden' name='data[in_editor]' value='1'/>");
		form.append(fileinput);
		form.append(hidden);

		$('.editor').after(form);
		*/
		$('.editor').tinymce({
			menubar: false,
			//theme: 'advanced',
			relative_urls: false,
			toolbar: "formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | table | link hpimage media",

			plugins: "link image autolink hpimage autoresize table media",
			content_css: "/core/css/fonts.css",
			link_title: false,
			target_list: false,
			image_description: false,
			external_plugins: {

			}
			/*,
			file_browser_callback: function(field, url, type, win) {
				if(type == 'image') { 
					$('#EditorUploadForm input').click();
				}
			}
			*/
		});
	});
})(jQuery);
