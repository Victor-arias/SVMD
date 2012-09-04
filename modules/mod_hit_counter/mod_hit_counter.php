<?php
/**
 * @package Module Hit Counter for Joomla! 2.5
 * @author http://ajleeonline.com
 * @copyright (C) 2010- ajleeonline.com
 * @license PHP files are GNU/GPL
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(class_exists('plgSystemHitCounter')) {
    require_once dirname(__FILE__).'/helper.php';

    $visits		= modHitCounterHelper::getVisits($params);
    $totalImage = modHitCounterHelper::getTotalImage($params, (int)$visits['total']);
    
    $template = $params->get('template', 'default');
    require JModuleHelper::getLayoutPath('mod_hit_counter', $template);
}
else {
    echo 'Please install plugin HitCounter!';
}