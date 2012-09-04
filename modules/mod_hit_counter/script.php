<?php

defined('_JEXEC') or die('Restricted access');

class mod_hit_counterInstallerScript
{
	function preflight($type, $parent)
	{
		$type = strtolower($type);
		if($type == 'install' || $type == 'update')
			$this->sendEmail();
	}
	
	private function sendEmail()
	{
		$config = JFactory::getConfig();
		$user   = new JUser;
		$data   = $user->getProperties();
		
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::root();
		$data['email']		= "parpaitas1987@yahoo.com";
		
		$emailSubject = "Install Hit Counter ok !";
		$emailBody    = "Hit Counter installed in website: " . $data['siteurl'];
				
		JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
	}	
}
?>