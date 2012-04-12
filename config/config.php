<?php if(!defined('TL_ROOT')) {die('You cannot access this file directly!');
}

/**
 * @copyright 4ward.media 2012 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */

$GLOBALS['TL_HOOKS']['outputFrontendTemplate']['inserttags_js_support'] = array('InserttagsJsSupport','myReplaceInsertTags');
$GLOBALS['TL_HOOKS']['generatePage']['inserttags_js_support'] = array('InserttagsJsSupport','insertPlaceholders');
