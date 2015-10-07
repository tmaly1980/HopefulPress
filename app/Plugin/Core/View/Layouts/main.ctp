<!-- start Core.main -->
    <?= $this->fetch("prelayout_header"); ?>
    <div id="page_wrapper" class="container-fluid <?= $this->fetch("container_class"); ?> <?= $this->fetch("page_class"); # Alt ?>">
    <?= $this->fetch("layout_header"); ?>
      <div class="<?= $this->fetch("pre_main_class"); ?>">
        <div id='main_content' class="main <?= $this->fetch("layout_main_class"); ?>">
    		<?= $this->fetch("before_content"); ?>

		<div class='row'>
		<div id='titled_content' class='<?= ($outer_rightcol = trim($this->fetch("outer_rightcol"))) ? "col-md-8":"col-md-12"; ?> <?= $this->fetch("title_container_class"); ?>'>
		<div class='row'>
			<?= $this->fetch("pre_title"); ?>
			<?= $this->element("Core.page_title"); ?>
			<?= $this->fetch("post_title"); ?>

			<div class='row'>
			<? $rcw = $this->fetch("rightcol_width",4); ?>
			<div class='<?= ($rightcol = trim($this->fetch("rightcol"))) ? "col-md-".(12-$rcw) : "col-md-12" ?>'>
				<?= $this->Session->flash(); ?>
				<?= $this->fetch("pre_content"); ?>
				<div id='content'>
					<?php echo $this->fetch('content'); ?>
				</div>
				<div class='clear'></div>
				<?= $this->fetch("post_content"); ?>
			</div>
			<? if($rightcol) { ?>
			<div class='col-md-<?=$rcw?>'>
				<?= $rightcol ?>
			</div>
			<? } ?>
			</div>
		</div>
			<div  class='clear'></div>
		</div>
		<? if($outer_rightcol) { ?>
			<div class='col-md-4'>
				<?= $outer_rightcol ?>
			</div>
		<? } ?>
		</div>
		<?php echo $this->fetch('after_content'); ?>
        </div>
	<div class='clear'></div>
      </div>
    </div>
    <div class='clear'></div>
    <div id='footer'>
	<?php echo $this->fetch('layout_footer'); ?>
    </div>

	<? if($sqlDump = $this->element('sql_dump')) { ?>
	<div class='whitebg'>
		<?= $sqlDump; ?>
	</div>
	<? } ?>

<!-- end main -->
