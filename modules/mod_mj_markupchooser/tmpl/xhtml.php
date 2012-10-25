<?php
/**
 * Mobile Joomla!
 * http://www.mobilejoomla.com
 *
 * @version		1.1.1
 * @license		GNU/GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
 * @copyright	(C) 2008-2012 Kuneri Ltd.
 * @date		August 2012
 */
defined('_JEXEC') or die('Restricted access');

/** @var $params JRegistry */
/** @var $links array */

echo $params->get('show_text', ' ');

$parts = array();
foreach($links as $link)
{
	if($link['url'])
		$parts[] = '<a class="markupchooser" href="'.$link['url'].'">'.$link['text'].'</a>';
	else
		$parts[] = '<span class="markupchooser">'.$link['text'].'</span>';
}

echo implode('<span class="markupchooser"> | </span>', $parts);
