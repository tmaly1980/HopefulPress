<? $this->assign("page_title", "Intake Surveys"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("add", "")) { ?>
	<?= $this->Html->add("Add Intake Survey", array("action"=>"add"),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="intakeSurveys index">

	<table class='table'>
	<tr>
		<th>Organization</th>
		<th>Name</th>
		<th>Status</th>
		<th>Submitted</th>
	</tr>
	<? foreach($intakeSurveys as $survey) { ?>
	<tr>
		<td>
			<?= $this->Html->link($survey['IntakeSurvey']['organization'], array('action'=>'view',$survey['IntakeSurvey']['id'])); ?>
		</td>
		<td>
			<?= $survey['IntakeSurvey']['first_name'] ?>
			<?= $survey['IntakeSurvey']['last_name'] ?>
		</td>
		<td>
			<?= $survey['IntakeSurvey']['status'] ?>
		</td>
		<td>
			<?= $this->Time->timeago($survey['IntakeSurvey']['created']); ?>
		</td>
	</tr>
	<? } ?>
	</table>

</div>
