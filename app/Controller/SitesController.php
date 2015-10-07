<?php
App::uses('AppController', 'Controller');
/**
 * Sites Controller
 *
 * @property Site $Site
 * @property PaginatorComponent $Paginator
 */
class SitesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public $uses = array('Site','NewsPost','Event','Photo','Page','SiteDesign','SiteVisit','SitePageView','Blog.Post','BlogPageView','MarketingPageView');

	public $helpers = array('Chart.Chart');

/**
 * index method
 *
 * @return void
 */
 	/*
	public function index() {
		$this->Site->recursive = 0;
		$this->set('sites', $this->Paginator->paginate());
	}
	*/

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function manager_view($id = null) {
		if (!$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		$this->Site->id = $id;
		$hostname = $this->Site->field('hostname');
		$this->Multisite->redirect("/", $hostname);
	}

	function widget()
	{
		$this->set("sites", $this->Site->find('all'));
	}

	function view($hostname)
	{
		$this->set("site",$this->Site->first(array('hostname'=>$hostname)));
	}

	function  manager_index()
	{
		$this->set("sites", $this->Site->find('all'));
	}

	function manager_disable($id)
	{
		if(!empty($id))
		{
			$this->Site->id = $id;
			# Stop billing...
			if($error = $this->StripeBilling->cancelSubscription())
			{
				return $this->setError($error, array('action'=>'index'));
			}
	
			$this->Site->saveField("disabled", date('Y-m-d H:i:s'));
			$this->setSuccess("The website has been disabled. Any recurring billing has been cancelled.", array('action'=>'index'));
		}
	}

	function manager_add()
	{
		$this->setAction("manager_edit");
	}

	function manager_edit($id=null)
	{
		$this->Site->id = $id;
		$old_site = !empty($id) ? $this->Site->read(null,$id) : null;
		$owner_id = !empty($old_site['Site']['user_id'])  ? $old_site['Site']['user_id'] : null;
		$plan = !empty($old_site['Site']['plan'])  ? $old_site['Site']['plan'] : null;

		# Disable validation for Owner if empty...
		if(empty($this->request->data['Owner']['email'])) { unset($this->request->data['Owner']); }

		if(!empty($this->request->data))
		{
			if(!empty($this->request->data['Site']['plan']) && empty($plan)) { $this->request->data['Site']['upgraded'] = date('Y-m-d H:i:s'); }
			if($this->Site->saveAll($this->request->data))
			{
				# If site owner has changed...
				$new_owner_id = $this->Site->field("user_id");
				$site = $this->Site->read();
				if(!empty($new_owner_id))
				{
					$user = $this->User->read(null, $new_owner_id);
					$vars = array('current_site'=>$site, 'current_user'=>$user);
					$this->sendUserEmail($user['User']['email'], "Your new website for ".$site['Site']['title'], "sites/created", $vars, 'support@hopefulpress.com');
				}
				$this->setSuccess("Site saved",array('action'=>'index'));
			} else {
				$this->setError("Could not save site: ".$this->Site->errorString());
			}
		} 
		if(!empty($id))
		{
			$this->request->data = $this->Site->read(null,$id);
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function signup($plan=null) {
		$this->layout = 'www';
		$this->theme = 'www';
		$this->multisite = false;

		if ($this->request->is('post')) {
			$this->Site->create();
			#
			# Allow for email duplicates between sites.... since site_id doesn't seem to be ready yet during signup to filter.
			unset($this->Site->Owner->validate['email']['isUnique']);
			#
			if($visit_id = $this->Session->read("Marketing.visit_id"))
			{
				# Save tracking info into site; where signups come from
				$visit = $this->MarketingVisit->read(null, $visit_id);
				$this->request->data['Site']['marketing_visit_id'] = $visit_id; # This too helps.
				if(!empty($visit)) { 
					$keys = array('session_id','campaign_code','campaign_subcode');
					foreach($keys as $key)
					{
						$this->request->data['Site'][$key] = 
							$visit['MarketingVisit'][$key];
					}
				}
			}

			if ($this->Site->saveAll($this->request->data)) {
				# Saves user account as  well!
				$site = $this->Site->read();

				# Save site_id to user account
				$this->Site->Owner->saveField("site_id", $this->Site->id);

				# Log them in.
				$user = $this->Site->Owner->read();

				error_log("SITE=".print_r($site,true));
				error_log("USER=".print_r($user,true));
				error_log("USER_ID=".$this->Site->Owner->id);

				#$this->Auth->login(array('anon'=>1,'owner'=>1)); # So not tripping Auth requirements....

				# Email them with info
				$vars = array('current_site'=>$site, 'current_user'=>$user);
				$this->sendUserEmail($user['Owner']['email'], "Your new website for ".$site['Site']['title'], "sites/created", $vars, 'support@hopefulpress.com');

				# NOTIFY ME OF NEW SITE
				$this->sendManagerEmail("New website {$site['Site']['title']} ({$site['Site']['hostname']})", "sites/created", $vars, 'support@hopefulpress.com');

				#$this->Session->setFlash(__('The site has been created.'), 'default', array('class' => 'alert alert-success'));

				# Sign in user
				$this->Auth->login($user['Owner']);
				error_log("LOGGING IN as=".print_r($user,true));

				# Now go to site itself.
				#return $this->Multisite->redirect("/users/invite/$email/$invite", $site['Site']['hostname']);#sites/setup");
				return $this->Multisite->redirect("/", $site['Site']['hostname']);#sites/setup");

			} else {
				$this->setError('The site could not be created');
			}
		} else {
			$this->Tracker->track('Marketing'); # Page view tracked
		}

		Configure::load("Stripe.billing");
		$this->set("plans",Configure::read("Billing.plans"));
		$this->set("plan",$plan);
	}

	function setup()
	{
		if(!$this->site()) { $this->redirect(array('action'=>'signup')); }
		$this->redirect(array('action'=>'setup_design'));
	}

	function setup_design()
	{
		if(empty($this->request->query['preview']))
		{
			$this->layout = 'Core.core_wrapper';
		} else {
			$this->layout = 'preview';
		}
		if(!empty($this->request->data))
		{
			$this->SiteDesign->save($this->request->data);
			$this->redirect(array('action'=>'setup_home'));
		}
		$this->request->data = $this->SiteDesign->singleton();
	}
	function setup_home()
	{
		if(!empty($this->request->data))
		{
			# Save
			# Next
			$this->redirect(array('action'=>'setup_about'));
		}
	}
	function setup_about()
	{
		if(!empty($this->request->data))
		{
			# Save
			# Next
			$this->redirect(array('action'=>'setup_contact'));
		}
	}
	function setup_contact()
	{
		if(!empty($this->request->data))
		{
			# Save
			# Next
			$this->redirect(array('action'=>'setup_contact'));
		}
	}
	function setup_finish()
	{
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 	/*
	public function edit($id = null) {
		if (!empty($id) && !$this->Site->exists($id)) {
			throw new NotFoundException(__('Invalid site'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Site->saveAll($this->request->data)) {
				$this->setSuccess("The website has been created");

				# Trigger email to user.
				#$this->email($user, "HopefulPress Signup", "users/signup",array('site'=>$site));

				return $this->redirect(array('controller'=>'pages','action' => 'index'));
			} else {
				$this->Session->setFlash(__('The site could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Site.' . $this->Site->primaryKey => $id));
			$this->request->data = $this->Site->find('first', $options);
		}
		$users = $this->Site->Owner->find('list');
		$this->set(compact('users'));
	}
	*/

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 	/*
	public function delete($id = null) {
		$this->Site->id = $id;
		if (!$this->Site->exists()) {
			throw new NotFoundException(__('Invalid site'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Site->delete()) {
			$this->Session->setFlash(__('The site has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The site could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	*/


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Site->recursive = 0;
		$this->set('sites', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null)
	{
		$site_id = Configure::read("site_id");
		$this->set("site", $this->Site->read(null, $site_id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Site->create();
			if ($this->Site->save($this->request->data)) {
				$this->Session->setFlash(__('The site has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The site could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Site->Owner->find('list');
		$this->set(compact('users'));
	}

	public function admin_edit($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Site->save($this->request->data)) {
				$this->setFlash(__('The site info has been saved'),array('action'=>'view'));
			} else {
				$this->setFlash(__('The site could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Site->read(null, $this->site_id());
		}
	}

	# On a disabled site, all they should see is 'construction' and staff login - and in admin, just 'restore'
	function admin_restore()
	{
		$this->Site->id = $this->site_id();
		$stripe_id = $this->Site->field('stripe_id'); # Subscription.
		$plan = $this->Site->field('plan'); 
		if($plan == 'Trial') { $plan = null; }
		$created = $this->Site->field('created'); 
		$trial_days = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d", $created))) / (60*60*24);

		if((empty($plan) && $trial_days > Configure::read("trial_days")) || # Trial expired
			!empty($plan) && (empty($stripe_id) || !$this->Stripe->validCreditCard())) # Or credit card not valid. MUST ASK
		{
			$this->redirect("/admin/stripe/stripe_billing/setup");
		} else {
			# Otherwise, just restore subscription!
			if($error = $this->StripeBilling->saveSubscription())
			{
				$this->setError($error, "/admin/sites/view");
			}
		}

		$this->Site->saveField("disabled", null);
		$this->setSuccess("Your website has been reinstated.", "/admin/sites/view");
	}

	function admin_cancel()
	{
		if($this->request->is('post')) 
		{
			$this->Site->id = $this->site_id();
			# Stop billing...
			if($error = $this->StripeBilling->cancelSubscription())
			{
				return $this->setError($error, array('action'=>'view'));
			}
	
			$this->Site->saveField("disabled", date('Y-m-d H:i:s'));
			$this->Auth->logout();
			$this->setSuccess("Your website has been canceled.", "/");
		}
	}

}
