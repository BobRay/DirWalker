<?php
/**
 * Created by PhpStorm.
 * User: Bob Ray
 * Date: 1/25/14
 * Time: 10:45 PM
 */

/* This snippet uses DirWalker to produce an alphabetical list
   all the class-based processors in the MODX processors directory.
*/

/* If running outside of MODX, set the correct base path
   (full path to MODX root with trailing slash) */
if (!defined('MODX_CORE_PATH')) {
    define('BASE_PATH', 'C:/xampp/htdocs/addons/');
    require_once BASE_PATH . 'config.core.php';
    require_once MODX_CORE_PATH . MODX_CONFIG_KEY . '/' . MODX_CONFIG_KEY . '.inc.php';
}
require_once MODX_CORE_PATH . 'components/dirwalker/model/dirwalker/dirwalker.class.php';

$searchStart = MODX_PROCESSORS_PATH;

$eol = php_sapi_name() == 'cli'? "\n": '<br />';

$dw = new DirWalker();
/* widget has the string 'get' in it */
$dw->setExcludes('widget');

/* Use a regex to identify get* classes */
$dw->setIncludes('#get.*class\.php#', true);

/* Call dirWalk recursively */
$dw->dirWalk($searchStart, true);
$files = $dw->getFiles();

ksort($files);
foreach ($files as $path => $file) {
    /* Remove first part of path */
    $path = str_replace(MODX_PROCESSORS_PATH, '', $path);
    echo $eol . $path;
}

echo $eol . $eol . count($files) . ' found';
