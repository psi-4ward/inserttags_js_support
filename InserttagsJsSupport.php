<?php if(!defined('TL_ROOT')) {die('You cannot access this file directly!');
}

/**
 * @copyright 4ward.media 2012 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */
 
class InserttagsJsSupport extends Controller
{

	public function insertPlaceholders()
	{
		// add some Placeholders to replace it later
		$GLOBALS['TL_MOOTOOLS'][] = '[[InserttagsJsSupport::TL_MOOTOOLS]]';
		$GLOBALS['TL_HEAD'][] = '[[InserttagsJsSupport::TL_HEAD]]'; // in TL_HEAD we will handle TL_JAVASCRIPT and TL_CSS also
	}


	public function myReplaceInsertTags($strBuffer, $strTemplate)
	{
		// copy GLOBAL arrays to find new elements inserted throught insert-tags
		foreach(array('TL_JAVASCRIPT','TL_HEAD','TL_MOOTOOLS','TL_CSS') as $opt)
		{
			if(isset($GLOBALS[$opt]) && is_array($opt))
			{
				$GLOBALS[$opt.'_COPY'] = $GLOBALS[$opt];
			}
			else
			{
				$GLOBALS[$opt.'_COPY'] = $GLOBALS[$opt] = array();
			}
		}

		// first run replaceInsertTags
		$strBuffer = $this->replaceInsertTags($strBuffer);

		// replace placeholders with new JS/CSS/MOOTOOLS/HEAD elements
		$strBuffer = str_replace('[[InserttagsJsSupport::TL_MOOTOOLS]]',$this->addNewMOOTOOLS(),$strBuffer);
		$strBuffer = str_replace('[[InserttagsJsSupport::TL_HEAD]]',$this->addNewHEAD(),$strBuffer);

		return $strBuffer;
	}


	protected function addNewHEAD()
	{
		$strHeadTags = '';

		// ADD TL_HEAD
		$diff = array_diff($GLOBALS['TL_HEAD'],$GLOBALS['TL_HEAD_COPY']);
		foreach (array_unique($diff) as $head)
		{
			$strHeadTags .= trim($head) . "\n";
		}

		// ADD TL_JAVASCRIPT
		$diff = array_diff($GLOBALS['TL_JAVASCRIPT'],$GLOBALS['TL_JAVASCRIPT_COPY']);
		foreach (array_unique($diff) as $javascript)
		{
			$strHeadTags .= '<script' . (($GLOBALS['objPage']->outputFormat == 'xhtml') ? ' type="text/javascript"' : '') . ' src="' . $this->addStaticUrlTo($javascript) . '"></script>' . "\n";
		}

		// ADD TL_CSS
		$diff = array_diff($GLOBALS['TL_CSS'],$GLOBALS['TL_CSS_COPY']);
		$strTagEnding = ($GLOBALS['objPage']->outputFormat == 'xhtml') ? ' />' : '>';
		foreach (array_unique($diff) as $stylesheet)
		{
			list($stylesheet, $media, $mode) = explode('|', $stylesheet);
			$strHeadTags .= '<link' . (($GLOBALS['objPage']->outputFormat == 'xhtml') ? ' type="text/css"' : '') . ' rel="stylesheet" href="' . $this->addStaticUrlTo($stylesheet) . '" media="' . (($media != '') ? $media : 'all') . '"' . $strTagEnding . "\n";
		}

		return $strHeadTags;
	}


	protected function addNewMOOTOOLS()
	{
		$diff = array_diff($GLOBALS['TL_MOOTOOLS'],$GLOBALS['TL_MOOTOOLS_COPY']);
		if(empty($diff))
		{
			return '';
		}

		$strMootools = '';
		foreach (array_unique($diff) as $script)
		{
			$strMootools .= "\n" . trim($script) . "\n";
		}
		return $strMootools;
	}
}