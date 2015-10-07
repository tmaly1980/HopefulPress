<?= $this->Html->script('Core.slidebars/slidebars'); ?>
<?= $this->Html->css('Core.../js/slidebars/slidebars'); ?>
<script>
(function($) { 
$(document).ready(function() {
	$.slidebars({
		siteClose: false

	});

	checkSize();

	$(window).resize(checkSize);
});

function checkSize()
{
	$('body').append("<div class='col-sm-3'></div>"); // sample item.
	if($(".col-sm-3").css("float") != "none") // Not a tiny screen (smartphone)
	{
		$('.sb-toggle-left').click();
		//$.slidebars.open('left');
	}
}
})(jQuery);
</script>
