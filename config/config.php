<?php if(!defined('TL_ROOT')) {die('You cannot access this file directly!');
}

/**
 * @copyright 4ward.media 2012 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */

$GLOBALS['TL_HOOKS']['outputFrontendTemplate']['inserttags_js_support'] = array('InserttagsJsSupport','copyGlobals');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate']['inserttags_js_support'] = array('InserttagsJsSupport','insertPlaceholders');

$GLOBALS['TL_HOOKS']['replaceInsertTags']['inserttags_js_support'] = array('InserttagsJsSupport','myReplaceInsertTags');