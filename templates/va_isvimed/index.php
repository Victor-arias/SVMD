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
$lateral = false;
if($this->countModules('lateral')){
	$lateral = true;
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
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/jquery.megamenu.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/jquery.fancybox.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
		<!--[if lte IE 6]>
			<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
		<![endif]-->
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/bootstrap/bootstrap.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/jquery.megamenu.js"></script>
		<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/libs/jquery.fancybox.pack.js"></script>
		<script>
		jQuery(document).ready(function() {
			jQuery('#main_menu').megamenu();
			jQuery("a.pop_up").fancybox();

			jQuery('body').append('<div id="header_image"><div id="header_image_image"></div></div>');
			jQuery('header').prepend('<div id="header_image_controls"></div>');
			var imgArr = new Array( // relative paths of images
				<?php echo $urls; ?>
			);
			var preloadArr = new Array();
			var i;
			 
			/* preload images */
			for(i=0; i < imgArr.length; i++){
				preloadArr[i] = new Image();
				preloadArr[i].src = imgArr[i];
				jQuery('#header_image_controls').append('<a href="#" class="img_'+(i)+'">'+(i)+'</a>');
			}
			var currImg = 0;
			var newImg = -1;
			changeImg();
			var intID = setInterval(changeImg, 10000);

			/* image rotator */
			function changeImg(){
				console.log('currImg' + currImg%preloadArr.length);
				jQuery('.img_'+(currImg%preloadArr.length)).attr('class', '').addClass('img_'+(currImg%preloadArr.length));

				if(newImg == -1){
					newImg = (currImg+1)%preloadArr.length;
				}
				currImg = newImg;
				console.log('new newImg: ' + newImg);

				route = preloadArr[newImg].src;

				jQuery('#header_image_image').animate({opacity: 0}, 1000, function(){
					jQuery(this).css('background','url('+ route +') top center no-repeat');
				}).animate({opacity: 1}, 1000);
				jQuery('.img_'+(newImg)).addClass('active');
				newImg = -1;
			}
			
			jQuery('#header_image_controls a').click(function(e){
				var img = jQuery(this).text();
				if(img != currImg){
					newImg = img;
					changeImg();
				}
				e.preventDefault();
			});
		});
		</script>
	</head>
	<body>
		<div id="main">
			<div id="top">
					<jdoc:include type="modules" name="top" style="xhtml" />
				</div>
			<header>
				<div id="logos">
					<jdoc:include type="modules" name="header" style="none" />
				</div>
			</header>
			<nav>
				<jdoc:include type="modules" name="menu" style="none" />
			</nav>
			<section id="content" <?php if($home) echo"class='home'" ?>>
			<?php if($home): ?>
				<div id="banner">
					<jdoc:include type="modules" name="banner" style="none" />
				</div>
				<div id="middle">
					<jdoc:include type="modules" name="middle" style="none" />
				</div>

				<div id="bottom">
					<div class="f_left">
						<jdoc:include type="modules" name="bottom-left" style="xhtml" />
					</div>
					<div class="f_right">
						<jdoc:include type="modules" name="bottom-right" style="xhtml" />
					</div>
				</div>
				<div id="quick-bar">
					<jdoc:include type="modules" name="quick-bar" style="none" />
				</div>
			<?php else: ?>
				<jdoc:include type="modules" name="breadcrumbs" style="none" />
				<?php if($lateral): ?>
					<div id="lateral">
						<jdoc:include type="modules" name="lateral" style="xhtml" />
					</div>
				<?php endif; ?>
				<div id="main_content" <?php if(!$lateral): ?>class="full"<?php endif; ?> >
					<jdoc:include type="component" />
				</div>
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
		<jdoc:include type="modules" name="debug" />
	</body>
</html>
