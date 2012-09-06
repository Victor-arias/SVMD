<?php
/*
(c) Tushev S.A, 2009. All rights reserved
*/

 // no direct access
defined('_JEXEC') or die('Restricted access'); 

//echo $params->get('pretext','') ." ". $last_update_time . $params->get('posttext', '.');$params->get('posttext', '.');

function modLastUpdateRenderOutput($times, &$params) {
	$dateformat	= trim( $params->get('dateformat') );
	$result = $params->get('pretext','').'<br />';
	$offset		= (int) $params->get('timeoffset',0);
	//var_dump($times);
	foreach($times as $name => $time) {
		//echo "-$name=> $time\n";
		//if current item's username, skip processing it as a timestamp
		if($name == 'Last modified by:') { $result .= "<strong>$name</strong> $time<br />"; continue; }
		if($offset) $time += $offset*60;
		$result .= (($name=='') ? strftime($dateformat,$time)."<br />" : "<strong>$name: </strong>".strftime($dateformat,$time)."<br />");
	}
	//echo $result;
	return $result . $params->get('posttext', '')."<!-----lastupdate----->";
}


?>