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
		$GLOBALS['TL_MOOTOOLS'][] = '{{InserttagsJsSupport::TL_MOOTOOLS}}';
		$GLOBALS['TL_HEAD'][] = '{{InserttagsJsSupport::TL_HEAD}}'; // in TL_HEAD we will handle TL_JAVASCRIPT and TL_CSS also
	}


	public function myReplaceInsertTags1($strBuffer, $strTemplate)
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

		// first run of replaceInsertTags
		$strBuffer = $this->replaceInsertTags($strBuffer);

		return $strBuffer;
	}



	public function myReplaceInsertTags2($strTag)
	{
		if(substr($strTag,0,19) != 'InserttagsJsSupport') return false;
		list($tag,$val) = explode('::',$strTag);

		switch($val)
		{
			// first run, just hold the placeholders
			case 'TL_HEAD':
			case 'TL_MOOTOOLS':
				return '{{'.$tag.'::'.$val.'2'.'}}';
			break;

			// second run, add additional stuff
			case 'TL_MOOTOOLS2':
				return $this->addNewMOOTOOLS();
			break;
			case 'TL_HEAD2':
				return $this->addNewHEAD();
			break;

		}

		return false;
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