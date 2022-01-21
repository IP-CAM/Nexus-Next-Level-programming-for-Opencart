<?php
/*
 * Created on Tue September 17 2021 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 */

namespace Aweb\Nexus;

use Exception;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require_once(DIR_SYSTEM . 'library/aweb/nexus/vendor/autoload.php');

class Nexus
{
    private static $registry;
    private static $booted;

    // single instance only
    private function __construct() {}

    public static function init($registry)
    {
        if(self::$booted) {
            return null; //already booted
        }

        self::$registry = $registry;

        self::$booted = true;

        self::urlPush();
    }

    /**
     * Retrieves the OpenCart registry
     *
     * @return object
     */
    public static function getRegistry($type = null)
    {
        $registry = self::$registry;

        if(is_null($type)) {
            return $registry;
        }

        $data = $registry->get($type);

        if(is_null($data)) {
            throw new Exception("There is not registry instance for {$type}");
        }

        return $data;
    }

    /**
     * keep a request url history in order to provide back() facility on request
     *
     * @return void
     */
    protected static function urlPush()
    {
        $current = html_entity_decode(Request::server('HTTP_REFERER'));
        $session = Nexus::getRegistry('session');
        if (empty($session->data['_last_url']) || !is_array($session->data['_last_url'])) {
            $data = [];
        } else {
            $data = $session->data['_last_url'];
        }

        array_unshift($data, $current);
        if (count($data) > 2) {
            array_pop($data);
        }

        $session->data['_last_url'] = $data;
    }
}