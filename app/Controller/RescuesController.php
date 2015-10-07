<?
class RescuesController extends AppController
{
	var $uses = array('Rescue');

	var $globalActions = array('index','search','rescuer_add','rescuer_edit'); # What doesn't require the rescue to be specified.

	function beforeFilter()
	{
		if(empty($this->request->params['rescue']) && !in_array($this->request->params['action'],$this->globalActions))
		{
			$this->setError("Rescue not found",array('prefix'=>null,'action'=>'search'));
		}

		return parent::beforeFilter();
	}

	function index()
	{
		# Dashboard/intro page
	}

	function rescuer_edit() # Signup/edit
	{
		# Prompt for Rescue record.
		if(!empty($this->request->data))
		{
			if($this->Rescue->save($this->request->data))
			{
				$rescue = $this->Rescue->read();
				return $this->setSuccess(
					(!empty($this->request->params['rescue']) ? 
						"Rescue listing updated!":"Rescue listing created!")
					,array('rescuer'=>false,'action'=>'view','rescue'=>$rescue['Rescue']['hostname']));
			} else {
				return $this->setError("Could not create rescue listing: ".$this->Rescue->errorString());
			}
		}
		if(!empty($this->request->params['rescue'])) # Updating.
		{
			$this->request->data = $this->Rescue->findByHostname($this->request->params['rescue']);
		}
	}

	function view($hostname=null) # "home" page
	{
		if(!empty($this->request->params['rescue']))
		{
			$hostname = $this->request->params['rescue'];
		}
		if(empty($hostname) || !($rescue = $this->Rescue->findByHostname($hostname)))
		{
			$this->setError("Rescue not found",array('action'=>'index'));
		}
		# Maybe get other important records...

		$this->set("rescue", $rescue);
	}

	function about()
	{
	}

	function contact()
	{
	}

	function search()
	{
		# Possible criteria to filter.

		$this->set("rescues", $this->Rescue->find('all'));
	}

	function widget() # Local, etc. sidebar
	{
		$this->set("rescues", $this->Rescue->find('all'));
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

}
