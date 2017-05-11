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
            $className = explode("\\", $className);
            $className = $className[1];
        }

        $file = dirname(__FILE__) . "/" . $className . '.php';

        if (file_exists($file))
        {
            require_once($file);
            return;
        }
    }
}

spl_autoload_register('\OntraportAPI\APIAutoloader::loader');