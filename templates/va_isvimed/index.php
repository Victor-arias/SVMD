<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.va_isvimed
 * @copyright	CC
 * @license		CC
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.framework', true);

$app			= JFactory::getApplication();
$doc			= JFactory::getDocument();
$templateparams	= $app->getTemplate(true)->params;
//$doc->addScript($this->baseurl.'/templates/'.$this->template.'/javascript/md_stylechanger.js', 'text/javascript', true);
$app = JFactory::getApplication();
$menu = $app->getMenu();
$home = false;
if ($menu->getActive() == $menu->getDefault()) {
	$home = true;
}
?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<meta charset="utf-8">
		<jdoc:include type="head" />
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
		<!--[if lte IE 6]>
			<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if IE 7]>
			<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<!--[if lte IE 6]>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/PIE.js"></script>
		<![endif]-->
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
	</head>

	<body>
		<header>
			<jdoc:include type="modules" name="header" style="none" />
			<div id="top">
				<jdoc:include type="modules" name="top" style="none" />
			</div>
		</header>
		<nav>
			<jdoc:include type="modules" name="menu" style="none" />
		</nav>
		<section>
		<?php if($home): ?>
			<div id="banner">
				<jdoc:include type="modules" name="banner" style="none" />
			</div>
			<div>
				<jdoc:include type="modules" name="middle" style="none" />
			</div>
			<div>
				<jdoc:include type="modules" name="bottom" style="none" />
			</div>
		<?php else: ?>
			<jdoc:include type="modules" name="breadcrumbs" style="none" />
			<jdoc:include type="component" />
		<?php endif; ?>
		</section>
		<footer>
			<jdoc:include type="modules" name="footer" style="none" />
		</footer>

		<jdoc:include type="modules" name="debug" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
		<script src="js/libs/bootstrap/bootstrap.min.js"></script>
	</body>
</html>
