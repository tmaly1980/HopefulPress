<?php
App::uses('BlogAppController', 'Blog.Controller');
class PostsController extends BlogAppController {
	var $uses = array('Blog.Post','Blog.Tag','Blog.PostsTag','Blog.Topic');

	var $helpers = array('HpComments.HpComments','Facebook.Facebook');

/* Actions */
	function topic_posts() # Topics hard-coded....
	{
		Configure::load("Blog.NavTopics");
		$topics = Configure::read("Blog.NavTopics");

		$topicPosts = array();

		foreach($topics as $tid=>$title)
		{
			$posts = $this->Post->find('urllist', array('conditions'=>array('nav_topic'=>$tid,'draft'=>0),'order'=>'Post.id ASC'));
			$topicPosts[$tid] = $posts;
		}
		return $topicPosts;
	}

	function topic($tid)
	{
		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate('Post',array('topic'=>$tid,'draft'=>0)));
		Configure::load("Blog.NavTopics");
		$topics = Configure::read("Blog.NavTopics");
		$this->set("topic", $topics[$tid]);
	}

	function recent()
	{
		$this->Post->recursive = 0;
		$this->paginate = array('limit'=>25);
		$this->set('posts', $this->paginate('Post',array('draft'=>0)));
	}

	public function index() {
		if($this->RequestHandler->isRss())
		{
			$posts = $this->Post->find('all',  array('limit'=>20,'conditions'=>array('draft'=>0)));
			return $this->set('posts', $posts);
		}
		$this->Tracker->track('Blog');
		/*
	 	if (!$this->Post->count(array('draft'=>0))) { 
			$this->redirect("/"); # Failsafe.
			#return $this->noContent(); 
		}
		*/

		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate('Post',array('draft'=>0)));
	}

	public function tags($tag) {
		$this->Tracker->track('Blog');
	 	if (!$this->Post->count()) { return $this->noContent(); }

		$this->Post->recursive = 2;
		$this->set('posts', $this->paginate('PostsTag',array('Tag.name'=>$tag,'Post.draft'=>0)));

		$this->set("tag", $tag);
	}

	public function related($postid) {
		$this->Tracker->track('Blog');
	 	if (!$this->Post->count()) { return $this->noContent(); }

		$tagids = $this->Post->PostsTag->fields("tag_id", array('post_id'=>$postid));

		$postids = $this->PostsTag->fields("post_id", array("tag_id"=>$tagids,"post_id NOT"=>$postid));
		$posts = $this->Post->find('all', array('conditions'=>array('Post.id'=>$postids)));

		# Now sort by most similar post first (ie more tags that shared)
		usort($posts, function($a,$b) use ($tagids) { 
			$a_tagids = Set::extract("/PostsTag.tag_id", $a);
			$b_tagids = Set::extract("/PostsTag.tag_id", $b);

			$a_tagcount = array_intersect($tagids, $a_tagids);
			$b_tagcount = array_intersect($tagids, $b_tagids);

			if($a_tagcount == $b_tagcount) { return 0; }
			return ($a_tagcount > $b_tagcount) ? -1 : 1;
		});

		$this->set('posts', $posts);
	}

	public function view($id = null) {
		$this->Tracker->track('Blog');
	 	if (!$this->Post->count($id)) { return $this->invalid(); }
		$this->set('post', $this->Post->read(null, $id));
	}

/* 	public function edit($id = null) {
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery posts add record even if nothing sent.
			if ($this->Post->save($this->request->data)) {
				$this->setFlash(__('The blog post has been saved'),array('action'=>'index'));
			} else {
				$this->setFlash(__('The blog post could not be saved. Please, try again.'));
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->Post->read(null, $id);
		}
	}

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->check($id);
		if ($this->Post->delete()) {
			$this->setFlash(__('Blog post deleted'), array('action'=>'index'));
		}
		$this->Session->setFlash(__('Blog post was not deleted'),array('action'=>'index'));
	}
*/ 
/* Actions */

	public function manager_index() {
	 	#if (!$this->Post->count()) { return $this->noContent(); }

		$this->Post->recursive = 0;
		$this->set('posts', $this->paginate());
	}

	public function manager_view($id = null) {
	 	if (!$this->Post->count($id)) { return $this->invalid(); }
		$this->set('post', $this->Post->read(null, $id));
	}

	public function manager_edit($id = null) {
		if (!empty($this->request->data)) { # $this->request->is('post') || $this->request->is('put')) { # This bad code lets jquery posts add record even if nothing sent.
			# fix tags before save....
			# I may need to grab the tag's primary id or create the tag before (or after) saving the post - since I'm adding on the fly.
			# bwfore 

			if ($this->Post->save($this->request->data)) {
				$this->setSuccess(__('The blog post has been saved'),array('action'=>'index'));
			} else {
				$this->setFlash(__('The blog post could not be saved. Please, try again.'));
			}
		} else if($id) { # Combine add and edit.
			$this->check($id);
			$this->request->data = $this->Post->read(null, $id);
		}
		$this->set("taglist", $this->Post->Tag->fields("name"));
		Configure::load("Blog.NavTopics");
		$this->set("navTopics", Configure::read("Blog.NavTopics"));

		$this->Topic->recursive = 1; # Topic and parent Posts
		$topics = $this->Topic->find('all'); # Get full records, not just dropdown
		$posts_by_topic = array();
		foreach($topics as &$topic)
		{
			$tid = $topic['Topic']['id'];
			$postlist = $this->Post->treelist(array('blog_topic_id'=>$tid), 1, '---');
			$topic['postlist'] = $postlist;
		}

		$this->set("topics", $topics);

	}

	public function manager_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->check($id);
		if ($this->Post->delete()) {
			$this->setFlash(__('Blog post deleted'), array('action'=>'index'));
		}
		$this->Session->setFlash(__('Blog post was not deleted'),array('action'=>'index'));
	}
}
