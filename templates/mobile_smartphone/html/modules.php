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

function modChrome_xhtml_m($module, &$params, &$attribs)
{
	/** @var $params JRegistry */
	if(!empty($module->content))
	{
		?><div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>"><?php
		if($module->showtitle)
		{
			?><div class="h3"><?php echo $module->title; ?></div><?php
		}
		echo $module->content;
		?></div><?php
	}
}
