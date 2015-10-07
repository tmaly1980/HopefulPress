<?
App::uses("ExceptionRenderer", "Error");
App::uses("CoreErrorController", "Core.Controller");

class CoreExceptionRenderer extends ExceptionRenderer
{
	protected function _getController($exception)
	{
                if (!$request = Router::getRequest(true)) {
                        $request = new CakeRequest();
                }
                $response = new CakeResponse();

                if (method_exists($exception, 'responseHeader')) {
                        $response->header($exception->responseHeader());
                }

		if (class_exists('AppController')) {
			try {
				$controller = new CoreErrorController($request, $response); # MODIFIED HERE
				$controller->startupProcess();
			} catch (Exception $e) { # Could be missing controller/component (causing loop)
				if (!empty($controller) && $controller->Components->enabled('RequestHandler')) {
					$controller->RequestHandler->startup($controller);
					$controller->layout = 'raw'; # Simpler (themeless)
				}
			}
		}
		if (empty($controller)) {
			$controller = new Controller($request, $response);
			$controller->layout = 'raw'; # Simpler (themeless)
		}
		$controller->set("exception", $exception);

		$controller->viewPath = 'Errors';

		return $controller;
	}
}
