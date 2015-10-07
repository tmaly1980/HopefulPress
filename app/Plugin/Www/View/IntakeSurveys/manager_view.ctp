<? $id = !empty($intakeSurvey["IntakeSurvey"]["id"]) ? $intakeSurvey["IntakeSurvey"]["id"] : ""; ?>
<? $this->assign("page_title", "Intake Survey"); ?>
<? $this->assign("container_class", ""); ?>
<? $this->assign("layout_main_class", ""); ?>
<? $this->start("title_controls"); ?>
<? if($this->Site->can("edit", $intakeSurvey)) { ?>
	<?= $this->Html->edit("Edit Intake Survey", array("action"=>"edit",$id),array("class"=>"btn btn-success")); ?>
<? } ?>
<? $this->end(); ?>
<div class="intakeSurveys view">
			<table cellpadding="0" cellspacing="0" class="table table-striped">
				<tbody>
				<tr>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['created']); ?>
			(<?= $this->Time->timeago($intakeSurvey['IntakeSurvey']['created']); ?>)
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('First Name'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['first_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Last Name'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['last_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Email'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Organization'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['organization']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Existing Website'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['existing_website']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Existing Website Provider'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['existing_website_provider']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Existing Website Costs'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['existing_website_costs']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Existing Website Details'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['existing_website_details']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Domain'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['domain']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Already Own Domain'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['already_own_domain']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Species'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['species']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Other Species'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['other_species']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Breeds'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['breeds']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Basic Pages'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['basic_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Homepage Content'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['homepage_content']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Adoption Pages'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['adoption_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Other Adoption Pages'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['other_adoption_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Sample Adoption Form File'); ?></th>
		<td>
			<?php echo $this->Html->link($intakeSurvey['SampleAdoptionFormFile']['name'], array('manager'=>false,'controller' => 'intake_survey_files', 'action' => 'download', $intakeSurvey['SampleAdoptionFormFile']['id'],$intakeSurvey['SampleAdoptionFormFile']['name'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Foster Pages'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['foster_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Foster Page Details'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['foster_page_details']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Sample Foster Form File'); ?></th>
		<td>
			<?php echo $this->Html->link($intakeSurvey['SampleFosterFormFile']['name'], array('manager'=>false,'controller' => 'intake_survey_files', 'action' => 'download', $intakeSurvey['SampleFosterFormFile']['id'],$intakeSurvey['SampleFosterFormFile']['name'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Volunteer Pages'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['volunteer_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Sample Volunteer Form File'); ?></th>
		<td>
			<?php echo $this->Html->link($intakeSurvey['SampleVolunteerFormFile']['name'], array('manager'=>false,'controller' => 'intake_survey_files', 'action' => 'download', $intakeSurvey['SampleVolunteerFormFile']['id'],$intakeSurvey['SampleVolunteerFormFile']['name'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Volunteer Page Details'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['volunteer_page_details']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Educational Pages'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['educational_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Other Pages'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['other_pages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Donation Features'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['donation_features']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Current Donation System'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['current_donation_system']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Donation Details'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['donation_details']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Facebook Page'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['facebook_page']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Twitter Page'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['twitter_page']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Other Social Media Links'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['other_social_media_links']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Want Mailing List'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['want_mailing_list']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Types of Email Messages'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['types_of_email_messages']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Example Mailing List Content'); ?></th>
		<td>
			<?php echo nl2br($intakeSurvey['IntakeSurvey']['example_mailing_list_content']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Current Mailing List Provider'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['current_mailing_list_provider']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Current Total Subscribers'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['current_total_subscribers']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Logo File'); ?></th>
		<td>
			<?php echo $this->Html->link($intakeSurvey['LogoFile']['name'], array('manager'=>false,'controller' => 'intake_survey_files', 'action' => 'download', $intakeSurvey['LogoFile']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Design Name'); ?></th>
		<td>
			<?php echo h($intakeSurvey['IntakeSurvey']['design_name']); ?>
			<br/>
			<? $img= $this->Html->image("/rescue/images/designs/thumbs/".$intakeSurvey['IntakeSurvey']['design_name'].".jpg"); ?>
			<?= $this->Html->link($img, "/rescue/images/designs/".$intakeSurvey['IntakeSurvey']['design_name'].".jpg",array('class'=>'lightbox')); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Other Design Url'); ?></th>
		<td>
			<?php echo $this->Text->autoLink($intakeSurvey['IntakeSurvey']['other_design_url']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Desired Colors'); ?></th>
		<td>
			<div class='swatch' style='background-color: <?= !empty($intakeSurvey['IntakeSurvey']['color1']) ? $intakeSurvey['IntakeSurvey']['color1']:"none"; ?>'><?= $intakeSurvey['IntakeSurvey']['color1']?></div>
			<div class='swatch' style='background-color: <?= !empty($intakeSurvey['IntakeSurvey']['color2']) ? $intakeSurvey['IntakeSurvey']['color2']:"none"; ?>'><?= $intakeSurvey['IntakeSurvey']['color2']?></div>
			<div class='swatch' style='background-color: <?= !empty($intakeSurvey['IntakeSurvey']['color3']) ? $intakeSurvey['IntakeSurvey']['color3']:"none"; ?>'><?= $intakeSurvey['IntakeSurvey']['color3']?></div>
			&nbsp;
		</td>
</tr>
				</tbody>
			</table>
</div>

<style>
.swatch
{
	display: block;
	float: left;
	padding: 25px;
	width: 100px;
	height: 100px;
	border: solid black  2px;
	margin: 10px;
	color: white;
}
</style>
