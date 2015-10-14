<?
class PagePhotoHelper extends AppHelper
{
	function config($params=array()) # Pass as compact('parentClass','photoModel','modelClass')
	{
		# For view, make sure controllerVar[PhotoAlias] is set.
		# and just pass 'photoModel' if not PagePhoto.
		
		# IF WE DONT WANT PAGEPHOTO, BE EXPLICIT

		$defaults = array(
			'parentClass' => $this->Form->model(),
			'photoModel' => 'PagePhoto',
			'scaledWidth'=>300,
			'placeholder' => "/page_photos/images/add-a-picture.png", 
			'thing' => "picture",
			'btnSize' => "btn-xs",
			'onEditLoad' => "",
			'align'=>'right',
			'margin'=>'margin25',
			'class'=>''
		);
		$vars = array_merge($defaults,$params);

		
		# Support per-parent customization in app's config file; sharing PagePhoto  (no need for custom model)
		Configure::load("pagePhoto");
		$config = Configure::check("PagePhoto.{$vars['parentClass']}.{$vars['photoModel']}") ? Configure::read("PagePhoto.{$vars['parentClass']}.{$vars['photoModel']}") : Configure::read("PagePhoto.{$vars['parentClass']}");

		# ALWAYS get key name from model relation....
		if(!empty($parent))
		{
			ClassRegistry::init($parent);
			$parentObject = new $parent();
		}

		if(is_array($config))
		{
			$vars = array_merge($vars,$config); # Update.
		}

		if(!isset($vars['plugin'])) { $vars['plugin'] = ($vars['photoModel'] == 'PagePhoto' ? 'page_photos' : null); }

		if(empty($vars['photoAlias'])) { $vars['photoAlias'] = $vars['photoModel']; }

		if(empty($vars['controller'])) {
			$vars['controller'] = Inflector::pluralize(Inflector::underscore($vars['photoModel'])); # Implied from model class.
		}
		if(empty($vars['primaryKey']))
		{
			$vars['primaryKey'] = !empty($parentObject) && !empty($parentObject->belongsTo[$vars['photoAlias']]['foreignKey']) ?
				$parent->belongsTo[$vars['photoAlias']]['foreignKey'] : 
				Inflector::underscore($vars['photoAlias'])."_id"; # Logo => logo_id
		}

		$vars['ucThing'] = ucfirst($vars['thing']);
		
		# Separate params allows for passing from page?

		# We might be passing  other vars explicitly, which we should ALSO save...
		
		if(empty($vars['page_photo_id'])) { # Could be set after save.
			$vars['page_photo_id'] = $this->Form->fieldValue($vars['primaryKey']);
		}
		if(empty($vars['page_photo_id']))
		{
			$vars['page_photo_id'] = $this->Form->fieldValue("{$vars['photoAlias']}.id"); # In case parent doesn't exist yet...
		}
		
		if(empty($vars['height'])) {
			$vars['height'] = $this->Form->fieldValue("{$vars['photoAlias']}.height");
		}
		if(empty($vars['width'])) {
			$vars['width'] = $this->Form->fieldValue("{$vars['photoAlias']}.width");
		}
		
		if(empty($vars['model_id'])) # model_id is the parent's ID! We know how to replace
		{
			$vars['model_id'] = $this->Form->id(); 
		}

		if(empty($vars['align']))# Probably never used
		{
			$vars['align'] = $this->Form->fieldValue("{$vars['photoAlias']}.align"); 
		}
		
		# Do  we use $pagePhoto everywhere?
		if(empty($vars['view_hidden'])) {
			$vars['view_hidden'] = $this->Form->fieldValue("{$vars['photoAlias']}.view_hidden");
		}
		# Only as  thumbnail.

		#error_log("VARS=".print_r($vars,true));
		
		$vars['data'] = $this->Form->data();
		
		if($vars['parentClass'] == $vars['photoModel'])
		{
			echo "COULD NOT DETERMINE PAGE FOR PHOTO";
			return false;
		}
		
		return $vars;
	}
}
