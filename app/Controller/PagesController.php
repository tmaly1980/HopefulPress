<?php
App::uses('AppController', 'Controller');
/**
 * Pages Controller
 *
 * @property Page $Page
 * @property PaginatorComponent $Paginator
 */
class PagesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'Sortable.Sortable'=>array('categoryField'=>'parent_id'),
		#'Publishable.Publishable' # Adds controller functions AND model filtering
	);

	var $uses = array('Page','Rescue.PageOverview');

	/* public $templates = array( # Pages (singletons) that have view/edit templates, ie view_about, edit_about
		'about'=>'About Page',
		'contact'=>'Contact Page',
		'home'=>'Home Page',
	); */

	function autosave() { $this->Publishable->autosave(); }
	function delete_draft($id) { return $this->Publishable->delete_draft($id); } # Handles redirect back to edit of original...

	function delete($id=null)
	{
		$this->check($id);
		if($this->Page->delete())
		{
			$this->setSuccess("Page deleted");
		} else {
			return $this->setError("Could not delete page. ".$this->Page->errorString());
		}
		$this->redirect(array('action'=>'index'));
	}


	public function index() {
		$this->set("overview", $this->PageOverview->singleton());

		if($pid = $this->pid()) { $this->redirect(array('controller'=>'projects','action'=>'view',$pid)); }

		$this->{$this->modelClass}->recursive = 1;
		#$this->set('pages', $this->paginate()); 
		#$this->set('pages', $this->Publishable->paginate()); # Hide drafts directly from index, 
		#$this->set('pages', $this->{$this->modelClass}->findIndex()); # Hide drafts directly from index, 

		# FULL HIERARCHY SAVED TO EACH' 'children', INFINITE RECURSION!

		$tree = $this->{$this->modelClass}->tree();
		$topics = Set::extract("/{$this->modelClass}[parent_id=]/..", $tree);
		$other_pages = Set::extract("/{$this->modelClass}[parent_id=0]/..", $tree);

		$this->set("topics", $topics);
		$this->set("other_pages", $other_pages);

		if(empty($topics) && empty($other_pages)) { 
			# XXX ONLY IF LOGGED IN...
			#$this->redirect(array('action'=>'add')); 
		}
	}

	public function view($id = null) {
		$this->Tracker->track();
		/*
		if(!empty($id) && !empty($this->templates[$id]))
		{
			$name = $this->templates[$id];
			$this->view = "templates/$id"; # Inline view/edit
			$this->modelClass = Inflector::classify(Inflector::underscore($name)); # About Page => AboutPage
			$id = null; # Not needed
		} else */ if (empty($id) || !$this->model()->exists($id)) {
			#$this->model()->sqlDump();

			return $this->notFound();
		}
		# idurl should work
		$this->set('page', $page = $this->read($id)); # XXX ??? publishable should hide unpublished/draft if not logged in (above in exists() )
		$this->set($this->thingVar(), $page); # Might be needed for pagePhoto

	}

	# The problem with encompassing home/contact/about like this is the model/table is different!!!!
	# So we can use a custom model!

	public function edit($id = null) {
		$modelClass = null;
		# Could be template driven
		/* if(!empty($id) && !empty($this->templates[$id]))
		{
			$template = $this->templates[$id];
			$this->view = "templates/$id"; # Inline view/edit
			$modelClass = Inflector::classify(Inflector::underscore($template));
			$this->loadModel($modelClass); # About Page => AboutPage
		} else */ if (!empty($id) && !$this->model()->exists($id)) { # OK if singletons don't exist yet, will add.
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			# Process special parent_id values. (inside Page model file)

			if ($this->model()->save($this->request->data)) {
				$this->setSuccess('The page has been saved.',array('action'=>'index'));
				# This above seems to fix inherited volunteer/etc pages

				#$this->setSuccess('The page has been saved.',"/pages"); # FIX FOR RESCUE EDUCATION PAGES... # array('action'=>'index'));
			} else {
				$this->setError('The page could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = !empty($modelClass) ? $this->{$modelClass}->read() : 
				$this->read($id);  # Pick draft version if available
				#$this->Publishable->read($id);  # Pick draft version if available
				# If a template, it's a singleton
		}
		$this->set("users", $this->User->find('list'));

		if($this->model()->hasField("parent_id"))
		{
			$this->set("topics", $topics = $this->model()->find('list',array('conditions'=>array('parent_id'=>null))));
			$this->set("other_pages", $other_pages = $this->model()->find('list',array('conditions'=>array('parent_id'=>0))));
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
}
