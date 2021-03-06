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

defined('_MJ') or die('Incorrect usage of Mobile Joomla.');

//load language file (to allow users to rename template)
$lang = JFactory::getLanguage();
$lang->load('tpl_mobile_smartphone');

$MobileJoomla = MobileJoomla::getInstance();

$base = $this->baseurl.'/templates/'.$this->template;
$home = $this->baseurl.'/';

$MobileJoomla_Device =& MobileJoomla::getDevice();
if($MobileJoomla_Device['markup'] != $MobileJoomla_Device['default_markup'])
	$home .= '?device='.$MobileJoomla_Device['markup'];

$MobileJoomla->showXMLheader();
$MobileJoomla->showDocType();
?>
<html<?php echo $MobileJoomla->getXmlnsString(); ?>>
<head>
<?php $MobileJoomla->showHead(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="MobileOptimized" content="<?php echo $MobileJoomla_Device['screenwidth']; ?>" />
	<meta http-equiv="cleartype" content="on" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="format-detection" content="address=no" />
	<style type="text/css" media="screen">@import "<?php echo $base;?>/resources/styles/reset.css";</style>
	<style type="text/css" media="screen">@import "<?php echo $base;?>/resources/styles/baseStyles.css";</style>
	<style type="text/css" media="screen">@import "<?php echo $base;?>/css/mj_xhtml.css";</style>
<?php
	if(@filesize(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'css'.DS.'custom.css'))
	{
		if($MobileJoomla->getParam('embedcss', false))
		{
			echo "<style>\n";
			@readfile(JPATH_SITE.DS.'templates'.DS.$this->template.DS.'css'.DS.'custom.css');
			echo "</style>\n";
		}
		else
		{
?>
	<style type="text/css" media="screen">@import "<?php echo $base;?>/css/custom.css";</style>
<?php
		}
	}
?>
	<script type="text/javascript" src="<?php echo $base;?>/resources/scripts/templates.js"></script>
	<script src="<?php echo $base;?>/mj_xhtml.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		/* prepend menu icon */
		$('.menu').prepend('<div id="menu-icon">Menu</div>');
		$(".vmenu").hide();
		/* toggle nav */
		$("#menu-icon").on("click", function(){
			$(".vmenu").slideToggle();
			$(this).toggleClass("active");
		});
	});
	</script>
</head>
<body>
<div id="wrap">
	<div id="header">
<?php
		$modulepos = $MobileJoomla->getPosition('header');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;
?>
	</div>
<?php
	$modulepos = $MobileJoomla->getPosition('header2');
	if($modulepos && $this->countModules($modulepos) > 0):
		?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
	endif;

	$MobileJoomla->showMessage();

	$modulepos = $MobileJoomla->getPosition('header3');
	if($modulepos && $this->countModules($modulepos) > 0):
		?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
	endif;
?>
	<div id="content">
<?php
		$modulepos = $MobileJoomla->getPosition('middle');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;

		$MobileJoomla->showComponent();

		$modulepos = $MobileJoomla->getPosition('middle2');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;
		$modulepos = $MobileJoomla->getPosition('middle3');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;

?>
		<div class="top">
			<a href="#header"><?php echo JText::_('TPL_MOBILE_SMARTPHONE__BACK_TO_THE_TOP'); ?></a>
		</div>
<?php
		if(!$MobileJoomla->isHome())
		{
?>
			<div class="home">
				<a href="<?php echo $home; ?>"><?php echo JText::_('TPL_MOBILE_SMARTPHONE__HOME'); ?></a>
			</div>
<?php
		}
?>
	</div>
	<div id="footer">
<?php
		$modulepos = $MobileJoomla->getPosition('footer');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;
		$modulepos = $MobileJoomla->getPosition('footer2');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;

		//$MobileJoomla->showFooter();

		$modulepos = $MobileJoomla->getPosition('footer3');
		if($modulepos && $this->countModules($modulepos) > 0):
			?><div id="<?php echo $modulepos; ?>"><?php $MobileJoomla->loadModules($modulepos); ?></div><?php
		endif;
?>
	</div>
</div>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34649352-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>