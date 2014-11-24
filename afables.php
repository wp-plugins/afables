<?php 
/*
 Plugin Name: Afables
 Description: Widget para wordpress de Afables
 Author: OhayoWeb
 Author URI: http://www.ohayoweb.com/
 Version: 1.3.1
*/


define('AFABLES_PLUGIN_VERSION','1.3.1');
define('AFABLES_USERAGENT','Afables Wordpress Plugin '.AFABLES_PLUGIN_VERSION);

/** campaña analytics **/
define('AFABLES_UTM_MEDIUM','widget');
define('AFABLES_UTM_CAMPAING','widgetWordpress');

define('AFABLES_DISPLAY_RATTING','png'); /* 'font-awesome' or 'png' values**/

define('AFABLES_MAX_RATTING',5);

$AFABLES_SUPPORTED_LANGUAGES = array('es','ca');

define('AFABLES_RSS',			'http://www.afables.com/rss/rss');
define('AFABLES_RSS_CHANNEL',	'http://www.afables.com/rss/rsschannel');

define('AFABLES_CACHE_FOLDER',plugin_dir_path( __FILE__ ).'cache');
define('AFABLES_CACHE_DURATION',21600); # 6 horas // 21600

/** Load languages **/
function afables_load_language() {
	load_plugin_textdomain( 'afables', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


/** Files **/
require_once('inc/afables-install.php');
require_once('inc/afables-widget.php');
//require_once('rss-robot/rss-robot.php');

register_activation_hook(__FILE__, 'afables_activate');

?>