<?
class NewsletterAppController extends AppController
{
	function beforeFilter()
	{
		parent::beforeFilter();

		Configure::load("Newsletter.mailchimp");
	}
}
