<?php
/**
 * @package Plugin Hit Counter for Joomla! 2.5
 * @author http://ajleeonline.com
 * @copyright (C) 2010- ajleeonline.com
 * @license PHP files are GNU/GPL
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );

class plgSystemHitCounter extends JPlugin
{
    function onAfterDispatch()
	{
        jimport('joomla.html.parameter');
        
        $mainframe = JFactory::getApplication();
		
        if($mainframe->isAdmin()) {
            return;
        }
		
        $this->_db 			= JFactory::getDbo();
        $this->_params 		= $this->getParams();
        $this->_sessions 	= $this->getSession();
        $this->saveData();
        
        return;
    }
    
    function saveData()
	{
        $db   = $this->_db;
        $data = $this->getDataLogs();
        
        if($sessions = $this->_sessions)
		foreach($sessions as $key=>$session)
		{
            $sessionData 	= $this->parseSessionData($session->data);
            $session_id     = $session->session_id;
            $user_id        = $session->userid;
            $timelast       = $session->time;
            $counter        = (int)$sessionData['session.counter'];
            $lasturl        = '';
            
            if(isset($data[$key]))
			{
                //update logs
                if($data[$key]->timelast < $session->time)
				{
                    //update
                    $queries[] = "UPDATE #__hitcounter_logs SET user_id = '$user_id', timelast = '$timelast', counter = '$counter', lasturl = '$lasturl' 
					WHERE session_id = '$key'";
                }
            }
			else
			{
                //insert new  
                $ip        = $this->getRemoteIP();
                $timestart = $sessionData['session.timer.start'] ? $sessionData['session.timer.start'] : $timelast;
                $timezone  = $sessionData['user']?$sessionData['user']->get('_params')->get('timezone') : '';
                $browser   = $sessionData['session.client.browser'] ? $sessionData['session.client.browser'] : $_SERVER['HTTP_USER_AGENT'];
                $queries[] = "INSERT INTO #__hitcounter_logs(session_id, user_id, ip, timestart, timelast, counter, browser, timezone, lasturl) 
				VALUES('$session_id', '$user_id', '$ip', '$timestart', '$timelast', '$counter', '$browser', '$timezone', '$lasturl')";
            }
        }
        
        if($queries)
		foreach($queries as $query) {
            $db->setQuery($query);
            if(!$db->query()) {
                JError::raiseError(500,$db->getErrorMsg());
            }
        }
       
        return;
    }
    
    function getDataLogs()
	{
        $db    = $this->_db;
        $query = "SELECT * FROM #__hitcounter_logs";
		
        $db->setQuery($query);
        return $db->loadObjectList('session_id');
    }
    
    function getParams()
	{        
        $plugin = &JPluginHelper::getPlugin('system', 'hitcounter');
        $params = new JParameter($plugin->params);
        
        return $params;
    }
	
    function getSession()
	{
	   $db 	  = $this->_db;
       $query = "SELECT * FROM #__session where client_id = 0"; //site
       $db->setQuery($query);
       $items = $db->loadObjectList('session_id');
       
       return $items;
	}
	
    function parseSessionData($data)
	{
        $tmp = explode('|',$data);
        return unserialize($tmp[1]);
    }
	
    function getRemoteIP()
	{        
        $ip = $_SERVER['REMOTE_ADDR'];
		
        if($ip == '::1') {
            $ip = 'local';
        }
		
        return $ip;
    }
}
?>