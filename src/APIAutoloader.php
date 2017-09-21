<?php

namespace OntraportAPI;

/**
 * Class APIAutoloader
 *
 * @author ONTRAPORT
 *
 * @package OntraportAPI
 */
class APIAutoloader
{
    public static function loader($className)
    {
        // Ensure correct path
        if (strpos($className, "\\"))
        {
            $className = array_pop(explode("\\", $className));
        }

        $file = dirname(__FILE__) . "/" . $className . '.php';

        if (file_exists($file))
        {
            require_once($file);
        }
        else if (strpos($file, "Exception"))
        {
            require_once(dirname(__FILE__) . '/Exceptions/OntraportAPIException.php');
        }
    }
}

spl_autoload_register('\OntraportAPI\APIAutoloader::loader');