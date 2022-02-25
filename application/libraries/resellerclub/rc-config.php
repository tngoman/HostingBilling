<?php

/**
 * Resellerclub Configuration
 */

/** GET and POST methods **/
define('METHOD_GET', 0);
define('METHOD_POST', 1);


define('ENV', (config_item('demo_mode') == 'TRUE') ? 'development' : 'production');

if ('development' === ENV) {
  define('RESELLER_ID', config_item('resellerclub_resellerid'));
  define('RESELLER_API_KEY', config_item('resellerclub_apikey'));
  define('RESELLER_DOMAIN', 'test.httpapi.com');
}
elseif ('production' === ENV) {
  define('RESELLER_ID', config_item('resellerclub_resellerid'));
  define('RESELLER_API_KEY', config_item('resellerclub_apikey'));
  define('RESELLER_DOMAIN', 'httpapi.com');
}
