		<div id="page_title_row">
			<? if($title_controls = $this->fetch("title_controls")) { ?>
			<div class='right'>
				<?= $title_controls; ?>
			</div>
			<? } ?>
			<? if(($left_title_controls = $this->fetch("left_title_controls")) || ($left_title_controls = $this->fetch("title_controls_left")) || ($left_title_controls = $this->fetch("title_controls_before"))) { ?>
			<div class='left marginright10'>
				<?= $left_title_controls; ?>
			</div>
			<? } ?>
			<? if($title = $this->fetch("page_title")) { ?>
          			<h2 id='page_title' class="page-header">
					<?= $this->fetch("pre_title_header"); ?>
					<?= $title ?>
					<?= $this->fetch("post_title_header"); ?>
				</h2>
			<? } ?>
		</div>

		<div class='clear'></div>
		<? if($subtitle_nav = $this->fetch("subtitle_nav")) { ?>
		<div  id="page_subtitle_nav_row">
			<?= $subtitle_nav ?>
		</div>
		<? } ?>
