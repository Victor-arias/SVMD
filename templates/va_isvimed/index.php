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


//SLIDER
$slidePath = 'images/encabezado';
$slides = array();
if (is_dir(JPATH_ROOT.DS.$slidePath)) {
    $files = JFolder::files(JPATH_ROOT.DS.$slidePath, '(.\.jpg)|(.\.png)');
    foreach($files as $file) {
        $slide = new stdClass();
        $slide->image = JURI::root().$slidePath.'/'.$file;
        $slides[] = $slide;
    }
} else {
    $handle = fopen(JPATH_ROOT.DS.$slidePath, 'r');    
    $line = 1;
    while (($buffer = fgets($handle)) !== false) {
        if (trim($buffer) == '') {
            continue;
        }
        $slide = json_decode($buffer);
        if ($slide && $slide->image) {
            $slides[] = $slide;
        }
        $line++;
    }
}
$urls = '';
foreach($slides as $slide){
	$urls .= '"' . $slide->image . '", ';
}
$urls = substr(trim($urls), 0, -1);
?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<meta charset="utf-8">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/jquery-1.7.2.min.js"><\/script>')</script>
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
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/bootstrap/bootstrap.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
		<script>
		$(document).ready(function() {
			$('body').append('<div id="header_image"><div id="header_image_image"></div><div id="header_image_controls"></div></div>');
			var imgArr = new Array( // relative paths of images
				<?php echo $urls; ?>
			);
			var preloadArr = new Array();
			var i;
			 
			/* preload images */
			for(i=0; i < imgArr.length; i++){
				preloadArr[i] = new Image();
				preloadArr[i].src = imgArr[i];
				$('#header_image_controls').append('<a href="#">'+(i+1)+'</a>');
			}
			var currImg = 1;
			var intID = setInterval(changeImg, 6000);
			$('#header_image_image').css('background','url(' + preloadArr[currImg%preloadArr.length].src +') top center no-repeat');
			/* image rotator */
			function changeImg(){
				$('#header_image_image').animate({opacity: 0}, 1000, function(){
				$(this).css('background','url(' + preloadArr[currImg++%preloadArr.length].src +') top center no-repeat');
				}).animate({opacity: 1}, 1000);
			}
			$('#header_image_controls a').click(function(e){
				console.log('a');
				e.preventDefault();
			});
		});
		</script>
	</head>
	<body>
		<div id="main">
			<header>
				<div id="logos">
					<jdoc:include type="modules" name="header" style="none" />
				</div>
				<div id="top">
					<jdoc:include type="modules" name="top" style="xhtml" />
				</div>
			</header>
			<nav>
				<jdoc:include type="modules" name="menu" style="none" />
			</nav>
			<section id="content" <?php if($home) echo"class='home'" ?> ?>>
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
				<div class="f_left">
					<jdoc:include type="modules" name="footer-left" style="none" />
				</div>
				<div class="f_right">
					<jdoc:include type="modules" name="footer-right" style="none" />
				</div>
			</footer>
			<div id="copyright"><jdoc:include type="modules" name="copyright" style="none" /></div>
		</div>

		<jdoc:include type="modules" name="debug" />
	</body>
</html>
