<?php
/*
(c) Tushev S.A, 2009. All rights reserved
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

//require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modLastUpdateHelper {
	function getLastUpdate(&$params) {
		//prepares output array.
		
		//echo "=$accessfilter=";
		$virtuemart	= (int) $params->get('virtuemart', 0);
		$joomfish	= (int) $params->get('joomfish', 0);
		$joomgallery	= (int) $params->get('joomgallery', 0);
		$docman		= (int) $params->get('docman', 0);
		$weblinks	= (int) $params->get('weblinks', 0);
		$users		= (int) $params->get('users', 0);
		$username	= (int) $params->get('username', 0);
			
		$time["Última actualización"] = self::getTimeFromContent($params);
		if ($virtuemart) {
			$_time = self::getTimeFromVirtuemart();
			//echo "-$virtuemart=$vmtime";
			if ($virtuemart == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["Store"] = $_time;
		}
		if ($joomfish) {
			$_time = self::getTimeFromJoomfish();
			if ($joomfish == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["Translation"] = $_time;
		}
		if ($joomgallery) {
			$_time = self::getTimeFromJoomgallery();
			if ($joomgallery == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["JoomGallery"] = $_time;
		}
		if ($docman) {
			$_time = self::getTimeFromDocman();
			if ($joomgallery == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["File repository"] = $_time;
		}
		if ($weblinks) {
			$_time = self::getTimeFromWeblinks();
			if ($joomgallery == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["Links"] = $_time;
		}
		if ($users) {
			$_time = self::getTimeFromUsers();
			if ($joomgallery == 1) $time["General update"] = ($time["General update"] > $_time) ? $time["General update"] : $_time;
			else $time["Last registration"] = $_time;
		}
		
		
		
		
		//do not forget to change texts in template as well, or it will not work properly!!!
		if ($username) 
			$time["Last modified by:"] = self::getUserName($params);
			
		//var_dump($time);
		return $time;							
		
	}
	function saveTimeToFile($timestring, $fileName, $mark, $offset = 0) {
		$f=fopen($fileName, 'w');
		fwrite($f, $timestring);
		if($mark) fwrite($f, "<span class='cachemark'>".strftime($mark, time()+$offset*60)."</span>");
		fclose($f);
	}
	function getCacheIfFresh($caching_time, $fileName) {
		/*	 Checks, whether it's possible to use the cache
			Returns false if it's necessary to update it, formatted string with concrete value if cache is up-to-date
		*/	
			
		//no cache exist
		//	This may raise a warning on first launch(cachefile does not exist), but everything's ok
		//	Just set your PHP error level to something more secure than E_WARNING
		// v 3.1 :: corrected this warning
		if(!($cacheModified = @filemtime($fileName))) return false;

		//cache is up-to-date
		if( (time() - $cacheModified) < $caching_time ) return file_get_contents($fileName);
		else return false;
	}
	function getTimeFromVirtuemart() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT mdate"
			. "\n FROM #__vm_product"
			. "\n ORDER BY mdate DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= (int) $rows[0]->mdate;
		} else {
			$modtime	= 0;
		}

		$query = "SELECT cdate"
		. "\n FROM #__vm_product"
		. "\n ORDER BY cdate DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();

		if ( count( $rows ) ) {
			$crtime	= (int) $rows[0]->cdate;
		} else {
			$crtime	= 0;
		}
															//////////self::saveTimeToFile("mdate = $modtime; cdate = $crtime;", dirname(__FILE__).DS.'1.txt');
		return ($crtime > $modtime) ? $crtime : $modtime  ;
	}
	function getTimeFromContent(&$params) {
	//RETURNS  timestamp
		global $mainframe;
		
		$qconditions = "\n FROM #__content";
		
		//works only if caching's disabled - for security purproses
		$accessfilter	= $params->get('accessfilter', 0);
		if ($accessfilter)	{
			if ( ($accessfilter==2) &&  (!((bool) $params->get('cacheresults',0)))  ) {
				$user	=& JFactory::getUser();
				$aid	= (int) $user->get('aid');
				$qconditions .=	"\n WHERE access <= $aid";
			} else $qconditions .=	" WHERE access = 0";
		}
		$publishedfilter = $params->get('publishedfilter', 0);
		if ($publishedfilter) {
			if ($accessfilter)	 $qconditions .= " AND state = 1";
			else			 $qconditions .= " WHERE state = 1";
		}
		
		$db	=& JFactory::getDBO();
		$query	= "SELECT modified \n $qconditions"
			. "\n ORDER BY modified DESC";
				
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= strtotime($rows[0]->modified);
		} else {
			$modtime	= 0;
		}
	
		$query = "SELECT created \n $qconditions"
			. "\n ORDER BY created DESC";
		
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();

		if ( count( $rows ) ) {
			$crtime	= strtotime($rows[0]->created);
		} else {
			$crtime	= 0;
		}

		return ($crtime > $modtime) ? $crtime : $modtime  ;
	}
	function getUserName(&$params) {
	//RETURNS  username
	////?to be optimized - to reduce num of queries
		global $mainframe;
		
		$qconditions = "\n FROM #__users, #__content as c ";
		
		//works only if caching's disabled - for security purproses
		$accessfilter	= $params->get('accessfilter', 0);
		$username	= $params->get('username', 0);
		if ($accessfilter)	{
			if ( ($accessfilter==2) &&  (!((bool) $params->get('cacheresults',0)))  ) {
				$user	=& JFactory::getUser();
				$aid	= (int) $user->get('aid');
				$qconditions .=	"\n WHERE access <= $aid";
			} else $qconditions .=	" WHERE access = 0";
		}
		$publishedfilter = $params->get('publishedfilter', 0);
		if ($publishedfilter) {
			if ($accessfilter)	 $qconditions .= " AND state = 1";
			else			 $qconditions .= " WHERE state = 1";
		}
		
		$db	=& JFactory::getDBO();
		$query	= "SELECT name, username \n $qconditions"
			. "\n ORDER BY c.modified DESC";
				
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			if ($username==1) $modtime	= $rows[0]->name;
			else $modtime			= $rows[0]->username;
		} else {
			$modtime	= "unknown user";
		}
		
		//echo $query;
		//echo $modtime;
		return $modtime  ;
	}
	function getTimeFromJoomfish() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT modified"
			. "\n FROM #__jf_content"
			. "\n ORDER BY modified DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= strtotime($rows[0]->modified);
		} else {
			$modtime	= 0;
		}

		return $modtime  ;
	}
	function getTimeFromJoomgallery() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT imgdate"
			. "\n FROM #__joomgallery"
			. "\n ORDER BY imgdate DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= $rows[0]->imgdate;
		} else {
			$modtime	= 0;
		}
		return $modtime  ;
	}
	function getTimeFromDocman() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT dmlastupdateon"
			. "\n FROM #__docman"
			. "\n ORDER BY dmlastupdateon DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= strtotime($rows[0]->dmlastupdateon);
		} else {
			$modtime	= 0;
		}
		return $modtime  ;
	}
	function getTimeFromWeblinks() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT created"
			. "\n FROM #__weblinks"
			. "\n ORDER BY created DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= strtotime($rows[0]->created);
		} else {
			$modtime	= 0;
		}
		return $modtime  ;
	}
	function getTimeFromUsers() {
	//RETURNS  timestamp
		global $mainframe;

		$db	=& JFactory::getDBO();
		$query	= "SELECT registerDate"
			. "\n FROM #__users"
			. "\n ORDER BY registerDate DESC";
	
		$db->setQuery($query, 0, 1);
		$rows = $db->loadObjectList();
		if ( count( $rows ) ) { 
			$modtime	= strtotime($rows[0]->registerDate);
		} else {
			$modtime	= 0;
		}
		return $modtime  ;
	}
}
