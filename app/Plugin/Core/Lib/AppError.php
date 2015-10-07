<?
App::uses("ErrorHandler", "Error");
class AppError extends ErrorHandler
{
	# We need to overwrite how fatal errors get treated in production mode.... enough details so debug emails can be useful.
        public static function handleFatalError($code, $description, $file, $line) {
                $logMessage = 'Fatal Error (' . $code . '): ' . $description . ' in [' . $file . ', line ' . $line . ']';
                CakeLog::write(LOG_ERR, $logMessage);

                $exceptionHandler = Configure::read('Exception.handler');
                if (!is_callable($exceptionHandler)) {
                        return false;
                }

                if (ob_get_level()) {
                        ob_end_clean();
                }

                # THIS SEEMS TO BE WHERE IT LOSES INFORMATION....

                if (Configure::read('debug')) {
                        call_user_func($exceptionHandler, new FatalErrorException($description, 500, $file, $line));
                } else {
                        call_user_func($exceptionHandler, new InternalErrorException($description, 500, $file, $line));
                        #call_user_func($exceptionHandler, new InternalErrorException()); # USELESS
                }
                return false;
        }

}
