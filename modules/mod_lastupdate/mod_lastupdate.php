<?php
/*
(c) Tushev S.A, 2009. All rights reserved
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JModuleHelper::getLayoutPath('mod_lastupdate'));
//$last_update_time = modLastUpdateHelper::getUpdate($params);

$cacheresults	= (int) $params->get('cacheresults',0);
$cachetime	= (int) $params->get('cachetime', 3600);
$cacheoffset	= (int) $params->get('cachetimeoffset',0);

$modLastUpdateCacheFileName = dirname(__FILE__).DS.'lastupdate';

//echo "<pre>";

if ( $cacheresults && ( $last_update = modLastUpdateHelper::getCacheIfFresh($cachetime, $modLastUpdateCacheFileName))	) ;
else {				//there's no caching or file is old or do not exist/
	$last_update = modLastUpdateRenderOutput(modLastUpdateHelper::getLastUpdate($params), $params); 
	if ($cacheresults) modLastUpdateHelper::saveTimeToFile($last_update, $modLastUpdateCacheFileName, (((bool) $params->get('cachemark', 0)) ? $params->get('cachemarktext', '[cached]') : false), $cacheoffset);
}
echo '<div class="'.$params->get('moduleclass').'">'.$last_update.'</div>';

//echo "</pre>";

