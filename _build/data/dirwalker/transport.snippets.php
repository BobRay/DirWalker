<?php
/**
 * snippets transport file for DirWalker extra
 *
 * Copyright 2013 by Bob Ray <http://bobsguides.com>
 * Created on 01-25-2014
 *
 * @package dirwalker
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $snippets */


$snippets = array();

$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array (
  'id' => 1,
  'description' => 'Description for Snippet one',
  'name' => 'Snippet1',
), '', true, true);
$snippets[1]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/snippet1.snippet.php'));

return $snippets;
