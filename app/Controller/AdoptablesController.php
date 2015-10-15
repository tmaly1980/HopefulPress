<?
class AdoptablesController extends AppController
{
	var $uses  = array('Adoptable','Adopter');

	var  $helpers = array('Stripe.Stripe');

	function user_import()
	{
		return $this->_import();
	}

	function user_import_template()
	{
		return $this->_import_template();
	}

	function user_search()
	{
		$this->setAction("user_index");
	}

	function user_index($status = null)
	{
		$cond = $having = array();
		$fields = array('*');
		if(!empty($this->request->data))
		{
			foreach($this->request->data['Adoptable'] as $k=>$v)
			{
				if(empty($v)) { continue; }
				if(!empty($this->Adoptable->virtualFields[$k]))
				{
					$def = $this->Adoptable->virtualFields[$k];
					$cond[] = "$def REGEXP '$v'"; # screw virtual  field mess.
				} else { # REAL
					$cond["Adoptable.$k REGEXP"] = $v;
				}
			}
			foreach($this->request->data['Owner'] as $k=>$v)
			{
				if(empty($v)) { continue; }
				if(!empty($this->Adoptable->Owner->virtualFields[$k]))
				{
					$def = $this->Adoptable->Owner->virtualFields[$k];
					$cond[] = "$def REGEXP '$v'"; # screw virtual  field mess.
				} else { # REAL
					$cond["Owner.$k REGEXP"] = $v;
				}
			}
		}
		error_log("COND=".print_r($cond,true));
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

	function successes_widget()
	{
		$this->set("successes", $this->paginate("Adoptable",
			array("Adoptable.status = 'Adopted'","Adoptable.success_story != ''")
		));
	}

	function beforeRender()
	{
		Configure::load("breeds");
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

		$this->paginate = array(
			'order' => 'Adoptable.story_date DESC, Adoptable.created DESC'
		);
		$this->set("adoptionStories", $this->paginate('Adoptable',array("Adoptable.status = 'Adopted'","Adoptable.success_story != ''")));
	}

	function stories()
	{
		$this->set("adoptionStories", $this->Adoptable->find('all',array('conditions'=>array("Adoptable.status = 'Adopted'","Adoptable.success_story != ''"))));
	}

	function search_bar()
	{
		parent::search_bar();

		$this->Adoptable->autoid = false;
		$this->set("adoptableCount", $this->Adoptable->count(array("status"=>'Available')));
	}

	function view($id=null)
	{
		if(empty($id) && !empty($this->request->params['id'])) { $id =$this->request->params['id']; } 
		if(empty($id) && !empty($this->request->named['id'])) { $id =$this->request->named['id']; }  # In case no exact route
		if(empty($id) || !($adoptable = $this->Adoptable->read(null,$id))) { return $this->setError("No such adoptable $id",array('action'=>'index','rescue'=>$this->rescuename)); }

		$this->set("adoptable", $adoptable);
	}

	function user_view($id = null)
	{
		$this->view($id);
	}

	function user_edit($id=null)
	{
		$this->require_rescue(); 

		if(empty($id) && !empty($this->request->params['id'])) { $id =$this->request->params['id']; } 

		if(!empty($this->request->data))
		{
			# Remove Owner fields if blank...
			if(empty($this->request->data['Owner']['id']) && empty($this->request->data['Owner']['first_name']) && empty($this->request->data['Owner']['last_name']))
			{
				unset($this->request->data['Owner']); # Nothing.  Don't add a blank record.
			}

			# Don't let them add more active adoptables than their plan allows.
			$adoptableCount = $this->Adoptable->count(array('status'=>'Available'));
			$plan = $this->rescue("plan");
			if(empty($plan)) { $plan = 'free'; }
			$maxAdoptables = array(
				'free'=>10,
				'basic'=>25,
				'dedicated'=>25,
				'unlimited'=>null
			);
			$new = (!empty($this->request->data) && empty($id) && empty($this->request->data['Adoptable']['id'])); # NEW...
			$available = (!empty($this->request->data['Adoptable']['status']) && $this->request->data['Adoptable']['status'] == 'Available');

			if($new && $available && !empty($maxAdoptables[$plan]) && $adoptableCount >= $maxAdoptables[$plan])
			{
				$this->setError("Sorry, you've reached the maxmimum number of active adoptable listings for your rescue's account. Either remove older listings or upgrade your rescue account plan via the 'Rescue Details' page.");
			}

			if($this->Adoptable->saveAll($this->request->data))
			{
				# HOW DO WE GET ID???
				$newid = $this->Adoptable->id;
				error_log("SAVEDALL,ID=$newid");
				#return $this->setSuccess("Adoptable information saved", array('user'=>null,'action'=>'view',$id));
				$msg = '';
				$goto = array('rescuer'=>false,'action'=>'view','id'=>$newid,'rescue'=>$this->rescuename); 
				if($this->request->data['Adoptable']['status'] == 'Adopted')
				{
					$msg .= " The adopted animal has been saved to the <a href='".Router::url(array('user'=>1,'action'=>'index','rescue'=>$this->rescuename))."'>Adoption Database</a>";
					return $this->setSuccess($msg, $goto);#array('action'=>'search','rescue'=>$this->rescuename)); 
				} else if($this->request->data['Adoptable']['status'] == 'Available') { 
					$msg .= empty($id) ? "Adoptable listing shared. " : "Adoptable listing saved. ";
					#$msg .= " <a class='underline' href='".Router::url(array('rescuer'=>false,'action'=>'view','id'=>$newid,'rescue'=>$this->rescue))."'>View adoptable listing</a>&nbsp; ";
					$msg .= "<a href='".Router::url(array('action'=>'add','rescue'=>$this->rescuename))."' class='controls btn btn-warning'><span class='glyphicon glyphicon-plus'></span> Add another adoptable</a>";

					return $this->setSuccess($msg, $goto);
				}
				
			} else {
				$this->setError("Cannot save adoptable information:".$this->Adoptable->errorString());
			}
		} else if (!empty($id)) {
			$this->Adoptable->recursive = 2;
			$this->request->data = $this->Adoptable->read(null,$id);
		}

		$this->set("genders", $this->Adoptable->dropdown("genders"));
		$this->set("ageGroups", $this->Adoptable->dropdown("age_groups"));
		$this->set("adultSizes", $this->Adoptable->dropdown("adult_sizes"));
		$this->set("statuses", $this->Adoptable->dropdown("statuses"));
		$this->set("childFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("catFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("dogFriendlies", $this->Adoptable->dropdown("yesno"));
		$this->set("neuteredSpayeds", $ns=$this->Adoptable->dropdown("yesno"));
		$this->set("adoptionStatuses", $this->Adoptable->Owner->dropdown("statuses"));
	}

	function adopt($id=null)
	{
		if(empty($id) && !empty($this->request->params['id']))
		{
			$id=$this->request->params['id'];
		}
		if(empty($id))
		{
			return $this->setError("Invalid adoption link",array('action'=>'index'));
		}
		$this->redirect(array('controller'=>'adopters','action'=>'add','adoptable_id'=>$id));

		/*
		$this->set("adoptable", $adoptable = $this->Adoptable->read(null,$id));
		if(!empty($this->request->data))
		{
			# Just send email, formatted...
			$this->sendAdoptionEmail($adoptable, $this->request->data);

			$this->setSuccess("Thanks for your interest in adopting {$adoptable['Adoptable']['name']}. We will contact you at our earliest convenience.", array('action'=>'view',$id));
		}
		# XXX TODO Load custom form fields...
		*/
	}

	function foster($id)
	{
		$this->set("adoptable", $this->Adoptable->read(null,$id));
	}

	function sponsor($id=null)
	{
		if(empty($id) && !empty($this->request->params['id']))
		{
			$id=$this->request->params['id'];
		}
		if(empty($id))
		{
			return $this->setError("Invalid sponsor link",array('action'=>'index'));
		}
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

	function user_select_adopters($id,$adopter_id=null) # Given a link...
	{
		if(!empty($adopter_id))
		{
			$this->request->data = array(
				'Adoptable'=>array(
					'id'=>$id,
					'adopter_id'=>$adopter_id,
					'status'=>'Adopted',
					'date_adopted'=>date("Y-m-d"),
				),
				'Owner'=>array(
					'id'=>$adopter_id,
					'adoptable_id'=>$id,
					'status'=>'Approved',
				),
			);

			if($this->Adoptable->saveAll($this->request->data))
			{
				$this->setSuccess("The adoptable's owner has been chosen and the adoptable's status is now set to 'Adopted'", array('action'=>'edit',$id,'#'=>'owner'));
			} else {
				$this->setError("Could not save adoptable owner: ".$this->Adoptable->errorString(), array('action'=>'edit',$id,'#'=>'owner'));
			}
		}

		# Two lists: adopters who chose this adoptable and OTHERS

		$this->set('explicitAdopters', $this->Adopter->find('all',array('conditions'=>array('Adopter.adoptable_id'=>$id))));
		$this->set('otherAdopters', $this->Adopter->find('all',array('conditions'=> array(
			'Adopter.adoptable_id !='=>$id,
			'Adopter.status'=>array('Received','Pending','Deferred')
		))));

		$this->set('adoptable_id',$id);
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
