<?
App::uses("RescueAppController", "Rescue.Controller");
class AdoptablesController extends AppController
{
	var $uses  = array('Adoptable','Rescue.AdoptionStory','Rescue.Adoption');

	var  $helpers = array('Stripe.Stripe');

	function  user_search()
	{
		$this->setAction("user_index");
	}

	function user_index($status = null)
	{
		$cond = $having = array();
		$fields = array('*');
		if(!empty($this->request->data['Adoptable']))
		{
			$models = array('Adoptable','Owner','Adoption');

			foreach($models as $m)
			{
				foreach($this->request->data[$m]  as $k=>$v)
				{
					if(!empty($v))
					{
						//$fieldType  = $this->$m->fieldType($k);
						//error_log("FT($m.$k)=$fieldType");
						//if(in_array($fieldType, array('string','text')))
						//{
						if(!empty($this->$m->virtualFields[$k]))
						{
							$def = $this->$m->virtualFields[$k];
							$cond[] = "$def REGEXP '$v'"; # screw virtual  field mess.
						} else { # Real field
							$cond["$m.$k REGEXP"] = $v;
						}
						//} else {
						//	$cond["$m.$k"] = $v;
						//}
					}
				}
			}
		}
		#$this->paginate = array(
		#	'conditions' => $cond,
		#	'fields'=>$fields,
		#	'group'=>array(!empty($having) ? "Adoptable.id HAVING ".join(" AND ", $having) : null)
		#);
		$this->set("adoptables", $this->paginate("Adoptable", $cond));
	}

	function widget($type='block')
	{
		$this->set("adoptables", $this->Adoptable->findAll(
			array("Adoptable.status != 'Adopted'")
		));
		$this->set("type", $type);
	}

	function beforeRender()
	{
		Configure::load("Rescue.breeds");
		$breeds = Configure::read("Breeds");
		$this->set("breeds", $breeds);
		$species = array_combine(array_keys($breeds), array_keys($breeds));
		$this->set("species",$species);

		return parent::beforeRender();
	}


	function index()
	{
		$this->set("adoptables", $this->Adoptable->findAll(
			array("Adoptable.status != 'Adopted'")
		));
		$this->set("adoptionStories", $this->paginate('AdoptionStory'));

	}

	function view($id=null)
	{
		if(empty($id) && !empty($this->request->params['id'])) { $id =$this->request->params['id']; } 
		if(empty($id) || !($adoptable = $this->Adoptable->read(null,$id))) { return $this->setError("No such adoptable",array('action'=>'index','rescue'=>$this->rescue)); }

		$this->set("adoptable", $adoptable);
	}

	function user_view($id = null)
	{
		$this->view($id);
	}

	function user_add_story($adoptable_id) { return $this->setAction("user_edit_story",$adoptable_id); }

	function user_edit_story($adoptable_id,$id=null)
	{
		$this->set("adoptable_id",$adoptable_id);
		$this->set("adoptable_name", !empty($adoptable_id)? $this->Adoptable->field("name", array('id'=>$adoptable_id)) : null);

		if(!empty($this->request->data))
		{
			if($this->AdoptionStory->saveAll($this->request->data))
			{
				return $this->setSuccess("Success story saved",array('action'=>'edit',$adoptable_id,'#'=>"adoption_story"));
			} else  {
				$this->setError("Could not save success story.  ".$this->AdoptionStory->errorString());
			}
		} else if (!empty($id)) {
			$this->request->data = $this->AdoptionStory->read(null,$id);
		} 
	}

	function rescuer_edit($id=null)
	{
		if(empty($id) && !empty($this->request->params['id'])) { $id =$this->request->params['id']; } 

		if(!empty($this->request->data))
		{
			# Remove Owner fields if blank...
			if(empty($this->request->data['Owner']['id']) && empty($this->request->data['Owner']['first_name']) && empty($this->request->data['Owner']['last_name']))
			{
				unset($this->request->data['Owner']); # Nothing.  Don't add a blank record.
			}

			if($this->Adoptable->saveAll($this->request->data))
			{
				$newid = $this->Adoptable->id;
				#return $this->setSuccess("Adoptable information saved", array('user'=>null,'action'=>'view',$id));
				$msg = '';
				if($this->request->data['Adoptable']['status'] == 'Adopted')
				{
					$msg .= " The adopted animal has been saved to the Adoption Database";
					return $this->setSuccess($msg, array('action'=>'search','rescue'=>$this->rescue)); 
				} else if($this->request->data['Adoptable']['status'] == 'Available') { 
					$msg .= empty($id) ? "Adoptable listing shared. " : "Adoptable listing saved. ";
					#$msg .= " <a class='underline' href='".Router::url(array('rescuer'=>false,'action'=>'view','id'=>$newid,'rescue'=>$this->rescue))."'>View adoptable listing</a>&nbsp; ";
					$msg .= "<a href='".Router::url(array('action'=>'add','rescue'=>$this->rescue))."' class='btn btn-sm btn-warning'><span class='glyphicon glyphicon-plus'></span> Add another adoptable</a>";

					return $this->setSuccess($msg, array('action'=>'view','id'=>$newid,'rescue'=>$this->rescue)); 
				}
				
			} else {
				$this->setError("Cannot save adoptable information:".$this->Adoptable->errorString());
			}
		} else if (!empty($id)) {
			$this->Adoptable->recursive = 2;
			$this->request->data = $this->Adoptable->read(null,$id);
		}

		$this->set("genders", $this->Adoptable->dropdown("genders"));
		$this->set("adultSizes", $this->Adoptable->dropdown("adult_sizes"));
		$this->set("statuses", $this->Adoptable->dropdown("statuses"));
		$this->set("childFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("catFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("dogFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("neuteredSpayeds", $ns=$this->Adoptable->dropdown("yesno"));
		$this->set("adoptionStatuses", $this->Adoptable->Owner->dropdown("statuses"));
	}

	function adopt($id)
	{
		$this->set("adoptable", $adoptable = $this->Adoptable->read(null,$id));
		if(!empty($this->request->data))
		{
			# Just send email, formatted...
			$this->sendAdoptionEmail($adoptable, $this->request->data);

			$this->setSuccess("Thanks for your interest in adopting {$adoptable['Adoptable']['name']}. We will contact you at our earliest convenience.", array('action'=>'view',$id));
		}
		# XXX TODO Load custom form fields...
	}

	function foster($id)
	{
		$this->set("adoptable", $this->Adoptable->read(null,$id));
	}

	function sponsor($id)
	{
		$this->set("adoptable", $this->Adoptable->read(null,$id));
	}

	function user_delete($id=null)
	{
		if($this->Adoptable->delete($id))
		{
			$this->setSuccess("Adoptable page has been removed",array('user'=>null,'action'=>'index'));
		}  else {
			$this->setError("Could not remove adoptable page",array('action'=>'view',$id));
		}
	}

	function user_success_story($adoptable_id,$id=null) #  Add/edit
	{
		if(!empty($this->request->data))
		{
			if($this->AdoptionStory->save($this->request->data))
			{
				return $this->setSuccess("Success story saved",array('action'=>'edit','id'=>$adoptable_id,'rescue'=>$this->rescue));
			} else  {
				$this->setError("Could not save success story.  ".$this->AdoptionStory->errorString());
			}
		} else if (!empty($id)) {
			$this->request->data = $this->AdoptionStory->read(null,$id);
		} 
		$this->request->data['AdoptionStory']['adoptable_id'] = $adoptable_id;
	}

	function success_story($id) # View as separate page,for end-user
	{
		$this->Adoptable->recursive = 2;
		$this->set("adoptable", $this->Adoptable->read(null, $id));
	}

	######################################
	# FOR ADDING ON THE FLY TO SUCCESS STORY
	/* WILL NEED TO TWEAK ALTERNATIVE.....ie add adoptable first, then success story...
	function  user_select($id=null)
	{
		$items = $this->Adoptable->adopted_list();
		if(empty($items)) { $this->setAction("user_add"); } # redirect will mess up whole page.
		$this->set($this->thingVars(), $items);
		$this->set("selected", $id);
	}

	function user_add()
	{
		# If not AJAX (success story), go to full edit page...
		if(empty($this->request->params['requested'])) { $this->redirect(array('action'=>'edit')); }

		#####################

		$items = $this->Adoptable->adopted_list();
		$this->set($this->thingVars(), $items);

	}
	*/
	######################################
}
