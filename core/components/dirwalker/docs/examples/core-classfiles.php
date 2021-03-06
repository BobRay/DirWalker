<?php
/**
 * Created by PhpStorm.
 * User: Bob Ray
 * Date: 1/25/14
 * Time: 10:45 PM
 */

/* This snippet uses DirWalker to produce an alphabetical list
   all the class files in the MODX core directory.
   The components, cache, and package directories are excluded
*/

/* If running outside of MODX, set the correct base path
   (full path to MODX root with trailing slash) */
if (!defined('MODX_CORE_PATH')) {
    define('BASE_PATH','C:/xampp/htdocs/addons/');
    require_once BASE_PATH . 'config.core.php';
    require_once MODX_CORE_PATH .  'config/' . MODX_CONFIG_KEY . '.inc.php';
}
require_once MODX_CORE_PATH . 'components/dirwalker/model/dirwalker/dirwalker.class.php';

$eol = php_sapi_name() == 'cli'? "\n" : '<br />';

$searchStart = MODX_CORE_PATH;

$dw = new DirWalker();
$dw->setIncludes('.class');
$dw->setExcludeDirs('components,cache,packages');
$dw->dirWalk($searchStart, true);
$files = $dw->getFiles();

ksort($files);

echo $eol . 'MODX CORE CLASS FILES' . $eol;
foreach ($files as $path => $file) {
    /* Remove first part of path */
    $path = str_replace(MODX_CORE_PATH, '', $path);
    echo $eol . $path;
}

echo $eol . $eol . count($files) . ' class files found';
