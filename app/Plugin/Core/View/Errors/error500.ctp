<!-- Handles system errors when in production mode -->
<!-- Call from app/View/Errors/error500 -->
<h2>Website Error</h2>
<p class='alert alert-danger'>
	<strong><?= $message ?></string>
	<br/>
	<br/>
	The website has experienced an unexpected error. 
	<br/>
	<br/>
	The support team has been notified and will resolve the issue as soon as possible.
</p>
<a id='details' href='#'><span class='glyphicon glyphicon-asterisk'></span></a>
<div id='stack_trace' style='display: none;'>
	<h3><?= $error->getMessage(); ?></h3>
	<br/>
	<?= $this->element('exception_stack_trace'); ?>
</div>
<script>
$('#details').click(function() { $('#stack_trace').toggle(); });
</script>
