<?php 
/**
 * Error
 */
namespace MrSimonBennett\Server;
	define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | 
        E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

define('ENV', 'dev');

//Custom error handling vars
define('DISPLAY_ERRORS', FALSE);
define('ERROR_REPORTING', E_ALL | E_STRICT);
define('LOG_ERRORS', TRUE);
class Error
{
	public function shut(){

		if(!is_null($e = error_get_last()))
		{
			//print_r($e);
		}
		else
		{
			print_r('Closed Well');
		}

	}

	public function handler( $errno, $errstr, $errfile, $errline ) {

    switch ($errno){

        case E_ERROR: // 1 //
            $typestr = 'E_ERROR'; break;
        case E_WARNING: // 2 //
            $typestr = 'E_WARNING'; break;
        case E_PARSE: // 4 //
            $typestr = 'E_PARSE'; break;
        case E_NOTICE: // 8 //
            $typestr = 'E_NOTICE'; break;
        case E_CORE_ERROR: // 16 //
            $typestr = 'E_CORE_ERROR'; break;
        case E_CORE_WARNING: // 32 //
            $typestr = 'E_CORE_WARNING'; break;
        case E_COMPILE_ERROR: // 64 //
            $typestr = 'E_COMPILE_ERROR'; break;
        case E_CORE_WARNING: // 128 //
            $typestr = 'E_COMPILE_WARNING'; break;
        case E_USER_ERROR: // 256 //
            $typestr = 'E_USER_ERROR'; break;
        case E_USER_WARNING: // 512 //
            $typestr = 'E_USER_WARNING'; break;
        case E_USER_NOTICE: // 1024 //
            $typestr = 'E_USER_NOTICE'; break;
        case E_STRICT: // 2048 //
            $typestr = 'E_STRICT'; break;
        case E_RECOVERABLE_ERROR: // 4096 //
            $typestr = 'E_RECOVERABLE_ERROR'; break;
        case E_DEPRECATED: // 8192 //
            $typestr = 'E_DEPRECATED'; break;
        case E_USER_DEPRECATED: // 16384 //
            $typestr = 'E_USER_DEPRECATED'; break;

    }

    $message = '<b>'.$typestr.': </b>'.$errstr.' in <b>'.$errfile.'</b> on line <b>'.$errline.'</b><br/>';

    

    if(!($errno & ERROR_REPORTING))
        return;

    if(DISPLAY_ERRORS)
        printf('%s', $message);

 

}
}