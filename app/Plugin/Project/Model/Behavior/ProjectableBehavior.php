<?

# For filtering etc based on project
# if the project is disabled, hide the content too.
# OR if we found a project_id context, only show content for that project.

class ProjectableBehavior extends ModelBehavior
{
	# If we found a project id from url, save it to the record!
	function beforeSave(Model $model, $options = array())
	{
		if(!$model->hasField("project_id")) { return true; }
		if(Configure::read("projectable_disabled")) { return true; } # Disabled, ie for nav.

		if($pid = Configure::read("project_id") && !isset($model->data[$model->alias]['project_id']))
		{
			$model->data[$model->alias]['project_id'] = $pid;
		}
		return true;
	}

	function beforeFind(Model $model, $query)
	{ # Must return query to continue, else will halt.
		
		if(!$model->hasField("project_id")) { return $query; }
		if(Configure::read("projectable_disabled")) { return $query; } # Disabled, ie for nav.

		# Get list of disabled projects.
		$model->setBelongsTo("Project",array('className'=>'Project.Project')); # If needed.


		if(isset($query['conditions']["{$model->alias}.project_id"]) || isset($query['conditions']["project_id"]))
		{
			return $query; # Leave alone, explicit.
		}

		$project_id = Configure::read("project_id");
		# Restrict to project we're in...
		if(!empty($project_id)) { 
			$query['conditions']["{$model->alias}.project_id"] = $project_id;
		} else {

			# Updates (news, events, photos, videos, audio) SHOULD show in global index/homepage
			#if(empty($model->project_only))
			#{
				#$disabled_pids = $model->Project->fields("id", array('published IS NULL'));
				#if(!empty($disabled_pids))
				#{
				#	$query['conditions']["{$model->alias}.project_id NOT"] = $disabled_pids;
				#}
			#} else {
			# Links and Downloads should NOT show per-project in the global index page.
				$query['conditions']["{$model->alias}.project_id"] = null;#$disabled_pids;

			#}

		}

		return $query;
	}
}
