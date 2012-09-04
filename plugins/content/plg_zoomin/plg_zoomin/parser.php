<?php
/*
* Pixel Point Creative "Zoom In" Plugin for Joomla
* License: GNU General Public License version 3 
* License URL: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) 2011 Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
*/

defined( '_JEXEC' ) or die();

if (!class_exists('ReplaceCallbackParser_')) {
	define ('_MULTITABS_OPEN_TAG', 1);
	define ('_MULTITABS_CLOSE_TAG', 2);
	define ('_MULTITABS_FULL_TAG', 3);
	class ReplaceCallbackParser_ {
		var $_source = '';
		var $_tagname = '';
		var $_open = '{';
		var $_close = '}';
		var $_callback = '';
		function ReplaceCallbackParser_($tagName, $tagAttr='{', $tagClose='}') {
			$this->_tagname = $tagName;
			$this->_open = $tagAttr;
			$this->_close = $tagClose;
		}
		
		function parse ($strinput, $callback) {
			$this->_source = $strinput;
			$this->_callback = $callback;
			//Build delimiter
			$regex = "/(".$this->_open . "[\/]?".$this->_tagname."[^}]*".$this->_close.")/"; 
			//echo $this->_source.'<br>'; 
			$arr = preg_split($regex, $this->_source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			/*print_r($arr);
			die();*/
			$empty = true;
			$matches = array();
			$tagAttr = '';
			$isOpened = false;
	
			$stroutput = '';
			foreach ($arr as $item) {
		    	$tagtype = $this->parseTag($item);
		    	if ($tagtype == _MULTITABS_OPEN_TAG) {
		    		if ($isOpened) {
		    			$stroutput .= $this->callBack ($tagAttr, $tagContent);
		    			$isOpened = false;
		    		}
		    		$tagAttr = substr($item, strlen($this->_open)+strlen($this->_tagname),strlen($item)-strlen($this->_tagname)-strlen($this->_close)-strlen($this->_open));
		    		$tagContent = '';
		    		$isOpened = true;
		    		
		    		continue;
		    	}
		    	if ($tagtype == _MULTITABS_FULL_TAG) {
		    		if ($isOpened) {
		    			$stroutput .= $this->callBack ($tagAttr, $tagContent);
		    			$isOpened = false;
		    		}
		    		$tagAttr = substr($item, strlen($this->_open)+strlen($this->_tagname),strlen($item)-strlen($this->_close)-strlen($this->_tagname)-strlen($this->_open)-1);
		    		$tagContent = '';
		    		$stroutput .= $this->callBack ($tagAttr, $tagContent);
		    		continue;
		    	}
		    	if ($tagtype == _MULTITABS_CLOSE_TAG) {
		  			$stroutput .= $this->callBack ($tagAttr, $tagContent);
		  			$isOpened = false;
		    		continue;
		    	}
		    	
		  		if ($isOpened) {
		  			$tagContent .= $item;
		  		} else {
		  			$stroutput .= $item;
		  		}	  		
		    }
			if ($isOpened) {
				$stroutput .= $this->callBack ($tagAttr, $tagContent);
				$isOpened = false;
			}
			
			return $stroutput;
		}
		
		function parseTag ($tag) {
			$arr = split ($this->_tagname, $tag, 2);
			if (count($arr) < 2) return 0;		
			if ($arr[0] == $this->_open) {
				if (substr($arr[1], - (strlen ($this->_close)+1)) == '/'.$this->_close) return _MULTITABS_FULL_TAG;
				else return _MULTITABS_OPEN_TAG;
			}
			if ($arr[0] == $this->_open.'/') return _MULTITABS_CLOSE_TAG;
			return 0;
		}
		
		function callBack ($tagAttr, $tagContent) {
			if (is_array($this->_callback) && count($this->_callback) >= 2) {
				$callbackobj = $this->_callback[0];
				$callbackmethod = $this->_callback[1]; 
				return $callbackobj->$callbackmethod($tagAttr, $tagContent);
			} else {
				if (function_exists($this->_callback)) {
					$callback = $this->_callback;
					return $callback($tagAttr, $tagContent);
				}
			}
		}	
	}
}
?>
