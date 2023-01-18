<?php
/**
 * Created by PhpStorm.
 * User: Bob Ray
 * Date: 1/25/14
 * Time: 10:45 PM
 */

/* This snippet will use DirWalker to produce an alphabetical list
   all MODX Events found inside the .php files in the core directory.

   The cache, packages, setup, and components directories are
   excluded.

   The class is extended to override the processFiles() method
   and look inside each file as it is found
*/

/* If running outside of MODX, set the correct base path
   (full path to MODX root with trailing slash) */
if (!defined('MODX_CORE_PATH')) {
    define('BASE_PATH', 'C:/xampp/htdocs/addons/');
    require_once BASE_PATH . 'config.core.php';
    require_once MODX_CORE_PATH . MODX_CONFIG_KEY . '/config.inc.php';
}
require_once MODX_CORE_PATH . 'components/dirwalker/model/dirwalker/dirwalker.class.php';


class MyDirWalker extends DirWalker {
    protected function processFile($dir, $file) {
        $content = file_get_contents($dir . '/' . $file);
        preg_match_all('#->invokeEvent.*(On[^\"\']+)#', $content, $matches);
        if (!empty($matches[1])) {
            // echo "\n * ";
            // print_r($matches);
            foreach ($matches[1] as $match) {
                /* If event is not already in the files array,
                   add it */
                if (!in_array($match, $this->files)) {
                    $this->files[] = $match;
                }
            }
        }

    }
}

$searchStart = MODX_CORE_PATH;

$eol = php_sapi_name() == 'cli'? "\n": '<br />';

$dw = new MyDirWalker();

$dw->setIncludes('.php');
$dw->setExcludeDirs('cache,packages,setup,components,_build');
/* Call dirWalk recursively */
$dw->dirWalk($searchStart, true);
$events = $dw->getFiles();
asort($events);

echo "\nCore Files\n";
foreach ($events as $event) {
    echo $eol . "   " . $event;
}
echo $eol . $eol . count($events) . ' core events found';

$dw->resetFiles();
$searchStart = MODX_MANAGER_PATH;
$dw->dirWalk($searchStart, true);
$events = $dw->getFiles();
asort($events);

echo $eol . $eol . "Manager Files\n";
foreach ($events as $event) {
    echo $eol . "   " . $event;
}
echo $eol . $eol . count($events) . ' manager events found';

