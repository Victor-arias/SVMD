<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle FAQs
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php

// NOTE : All CSS definitions are now found in components/com_fsf/assets/css/fsf.css

ob_end_clean();
header('Content-type: text/css');
header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");

$path = JPATH_SITE.DS.'components'.DS.'com_fsf'.DS.'assets'.DS.'css'.DS.'fsf.css';
require_once($path);

exit;