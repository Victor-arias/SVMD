<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app = JFactory::getApplication();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline.css" type="text/css" />
	<?php if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline_rtl.css" type="text/css" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/css/style.css">
</head>
<body>
<jdoc:include type="message" />
<div id="fixed">
	<div id="header">
		<h1><img src="img/isvimed.png" alt="ISVIMED" width="400" height="331"/></h1>
		<h2><img src="img/en-construccion.png" alt="Página en construcción" /></h2>
	</div>
	<div id="main" role="main">
		<h3><img src="img/logo-alcaldia-armas.png" alt="Alcaldía de medellín" width="283" height="95"/></h3>
		<div id="form">
			<iframe allowtransparency="true" src="http://www.jotformpro.com/form/21446881380961" frameborder="0" style="width:100%; height:450px; border:none;" scrolling="no"></iframe>
		</div>
	</div>
	<div id="footer">
		<div class="fleft">
			<p>Acérquese a nuestra entidad:<br />
			<address>Calle 47D # 75-240</address> (Detrás del velódromo) o a nuestro punto de atención en el sótano de la Alcaldía, taquilla 53.</p>
			<p><strong>PBX: 4304310</strong></p>
			<p>Todos los derechos reservados - <strong>ISVIMED 2012</strong></p>
		</div>
		<div class="fright">
			<a class="contratacion" href="http://www.contratos.gov.co">Contratación</a>
		</div>
	</div>
</div>
<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
	<div id="frame" class="outline">
	<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
	<fieldset class="input">
		<p id="form-login-username">
			<label for="username"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
			<input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" />
		</p>
		<p id="form-login-password">
			<label for="passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
			<input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" />
		</p>
		<p id="form-login-remember">
			<label for="remember"><?php echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
			<input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
		</p>
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	</form>
	</div>
</body>
</html>
