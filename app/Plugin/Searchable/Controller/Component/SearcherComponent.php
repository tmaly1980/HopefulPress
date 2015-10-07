<?
class SearcherComponent extends Component
{
	var $controller;

	function startup(Controller $controller)
	{
		$this->controller = $controller;
		parent::startup();
	}
	
	# Implement generic Searcher controller
	# way to specify table(s) to query
	# Results should be rendered as html (json), with proper links to records.
	function search($raw = false)
	{
		$controller = $this->controller;

		if(!empty($this->request->params['query']))
		{
			$terms = $this->request->params['query'];
			$results = $controller->{$controller->modelClass}->search($terms);

			if($raw) { return $results; }

			$controller->set("results", $results);
			return $controller->Json->render("results");
		} else {
			$controller->Json->error("No search terms provided");
		}

		if(!$raw) { 
			return $controller->Json->render();
		}
	}
}
