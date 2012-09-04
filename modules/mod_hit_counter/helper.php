<?php
/**
 * @package Module Hit Counter for Joomla! 2.5
 * @author http://ajleeonline.com
 * @copyright (C) 2010- ajleeonline.com
 * @license PHP files are GNU/GPL
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class modHitCounterHelper
{   
    function getTotalImage($params, $totalNumber)
	{
        
        $arrNumber = modHitCounterHelper::getArrayNumber($params->get('numberofdigits',5), $totalNumber);
        $type 	   = $params->get('styles', 'Basic');
		
		switch($type)
		{
			case "Classic":
				$number_style = $params->get('number_classic', 'arialb');
			break;
			case "Digital":
				$number_style = $params->get('number_digital', '3dangle');
			break;
			case "Extra Small":
				$number_style = $params->get('number_exsmall', 'hebtiny');
			break;
			case "Small":
				$number_style = $params->get('number_small', 'bold');
			break;
			case "Basic":
				$number_style = $params->get('number_basic', '1');
			default:
			break;
		}
		
        $html      = '';
		
        if($arrNumber)
		foreach($arrNumber as $number) {
            $html .= modHitCounterHelper::getDigitImage($number, $type, $number_style);
        }
       
        return $html;
    }
    
    function getArrayNumber($length, $number)
	{
        $strlen = strlen($number);
		$arr	=	array();
		$diff	=	$length -  $strlen;
		
		while($diff > 0) {
			array_push($arr, 0);
			$diff--;
		}
		
		$arrNumber = str_split($number);
		$arr	   = array_merge($arr, $arrNumber);
		
		return $arr;
    }
    
    function getDigitImage($number, $type, $number_style)
	{
        $html = '';
        $html .= '<img class="hitcounter_digit" src="modules/mod_hit_counter/assets/'.$type.'/'.$number_style.'/'.$number.'.gif" alt=""/>';
        return $html;
    }
	   
    function getVisits($params)
	{
        $options   			= modHitCounterHelper::getOptions($params);
        $timestart 			= modHitCounterHelper::getTimeStart($options);
        $startdaycounter 	= $params->get('startdaycounter');
        
        if($startdaycounter) {
            $startdaycounterUnix = JFactory::getDate($startdaycounter)->toUnix();
            $where[] = "a.timelast >= $startdaycounterUnix";
        }
		else {
            $where[] = "a.timelast >= ". $timestart['lastmonth'];
        }
        
        $where = count($where)?' WHERE '.implode(' and ',$where):'';
        $db    = &JFactory::getDbo();
        $query = "SELECT a.*, u.name, u.username, u.email
                    FROM #__hitcounter_logs as a
                    LEFT JOIN #__users as u ON u.id = a.user_id
                    $where
                    ORDER BY a.timelast desc";
					
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $visits['total'] = count($rows) + (int)$params->get('startofcounter', 0);
       
		if($rows)
		foreach($rows as $row)
		{            
            $timelast = (int)$row->timelast + $options['timeoffset'];
            
            if($timelast >= (int)$timestart['online'] && $params->get('showonline',1)) {
                if($row->user_id) {
                    $visits['online']['user'] = $row;
                }
				else {
                    $visits['online']['guest'] = $row;
                }
            }
            
            if($timelast >= $timestart['thismonth'] && $params->get('showthismonth',1))
			{
                $visits['thismonth'][] = $row;
                
                if($timelast >= $timestart['thisweek'] && $params->get('showthisweek',1)) {
                    $visits['thisweek'][] = $row;
                    
                    if($timelast >= $timestart['today'] && $params->get('showtoday',1)) {
                        $visits['today'][] = $row;
                    }
					else if($timelast >= $timestart['yesterday'] && $params->get('showyesterday',1)) {
                        $visits['yesterday'][] = $row;
                    }
                    
                }
				else if($timelast >= $timestart['lastweek'] && $params->get('showlastweek',1)) {
                    $visits['lastweek'][] = $row;
                }
            }
			else if($timelast >= $timestart['lastmonth'] && $params->get('showlastmonth',1)) {
                $visits['lastmonth'][] = $row;
            }
        }
        
        return $visits;
    }
    
    function getOptions($params)
	{
        $config			                  = &JFactory::getConfig();
		$options['lifetime']              = 60*(int)$config->getValue('config.lifetime');
        $options['timeoffset']            = 60*60*(int)$params->get('timeoffset',7);
        
        $options['now']['unix']           = mktime() + $options['timeoffset'];
        $options['now']['daymonthyear']   = explode('-',JFactory::getDate($options['now']['unix'])->toFormat('%d-%m-%Y'));
        
        $options['durationDay']           = 24*60*60;
        $options['onlinestarttime']       = $options['now']['unix'] - $options['lifetime'];
        
        return $options;
    }
    
    function getTimeStart($options)
	{
        
        $timestart['online']    = $options['onlinestarttime'];
        $timestart['today']     = $options['now']['unix'] - ($options['now']['unix'] % $options['durationDay']);
        $timestart['yesterday'] = $timestart['today'] - $options['durationDay'];
        
        $nameToday     = modHitCounterHelper::getNameOfDay($options['now']['daymonthyear'][0],$options['now']['daymonthyear'][1],$options['now']['daymonthyear'][2]);
        $positionToday = modHitCounterHelper::getPositionOfDay($nameToday);
        $timestart['thisweek']  = $timestart['today'] - $positionToday*$options['durationDay'];
        $timestart['lastweek']  = $timestart['thisweek'] - 7*$options['durationDay'];
        $timestart['thismonth'] = $timestart['today'] - ((int)$options['now']['daymonthyear'][0] - 1)*$options['durationDay'];
        
        $daysoflastmonth = modHitCounterHelper::getDaysofMonth((int)$options['now']['daymonthyear'][1] - 1,$options['now']['daymonthyear'][2]);
        $timestart['lastmonth'] = $timestart['thismonth'] - $daysoflastmonth*$options['durationDay'];
        
        
        return $timestart;
    }
    
    function getPositionOfDay($name)
	{
        $arrDay = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		
        foreach($arrDay as $key => $day) {
            if($day == $name){
                return $key;
            }
        }
        return;
    }
    
    function getDaysofMonth($month,$year)
	{
        $ts = mktime(0, 0, 0, $month, 1, $year);
        return date("t", $ts);
    }
    
	function getNameOfDay($day, $month, $year)
	{
        $ts = mktime(0, 0, 0, $month, $day, $year);
        return date("l", $ts);
    }
}
?>