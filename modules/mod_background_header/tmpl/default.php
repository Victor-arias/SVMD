<?php
/*------------------------------------------------------------------------
# mod_background_header - Background Header
# ------------------------------------------------------------------------
# author Victor Arias
# copyright Copyright (C) Telemedellín. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.telemedellin.tv
# Technical Support:  
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


$slidePath = trim($params->get('slide_path', '/'));
if ($slidePath == '' || !file_exists(JPATH_ROOT.DS.$slidePath)) {
    echo '<div class="fun_supersized_error">'.JText::_('MOD_BACKGROUNDHEADER_ERROR_INVALID_SLIDE_PATH').'</div>';
    return;
}

$slides = array();
/*if (is_dir(JPATH_ROOT.DS.$slidePath)) {
    $files = JFolder::files(JPATH_ROOT.DS.$slidePath, '(.\.jpg)|(.\.png)');
    if (empty($files)) {
        echo '<div class="fun_supersized_error">'.JText::_('MOD_BACKGROUNDHEADER_ERROR_SLIDE_PATH_DONT_HAVE_IMAGE').'</div>';
        return;
    }
    foreach($files as $file) {
        $slide = new stdClass();
        $slide->image = JURI::root().$slidePath.'/'.$file;
        $slides[] = $slide;
    }
} else {
    $handle = fopen(JPATH_ROOT.DS.$slidePath, 'r');
    if (!$handle) {
       echo '<div class="fun_supersized_error">'.JText::_('MOD_BACKGROUNDHEADER_ERROR_CANNOT_READ_SLIDE_LIST').'</div>';
    }
    
    $line = 1;
    while (($buffer = fgets($handle)) !== false) {
        if (trim($buffer) == '') {
            continue;
        }
        $slide = json_decode($buffer);
        if ($slide && $slide->image) {
            $slides[] = $slide;
        } else {
            echo '<div class="fun_supersized_error">'.JText::sprintf('MOD_BACKGROUNDHEADER_ERROR_SLIDE_LIST_LINE_INVALID', $line).'</div>';
        }
        $line++;
    }
    if (empty($slides)) {
        echo '<div class="fun_supersized_error">'.JText::_('MOD_BACKGROUNDHEADER_ERROR_SLIDE_LIST_EMPTY').'</div>';
        return;
    }
}*/

$options = new stdClass();
$options->autoplay              = (int)$params->get('autoplay', 1);
$options->height            = (int)$params->get('min_height', 0);
$options->width             = (int)$params->get('min_width', 0);
$options->random                = (int)$params->get('random', 0);
$options->slideshow             = (int)$params->get('slideshow', 1);
$options->slide_interval        = (int)$params->get('slide_interval', 5000);
$options->start_slide           = (int)$params->get('start_slide', 1);
$options->stop_loop             = (int)$params->get('stop_loop', 0);
$options->thumb_links           = (int)$params->get('thumb_links', 1);
$options->thumbnail_navigation  = (int)$params->get('thumbnail_navigation', 1);
$options->transition            = (int)$params->get('transition', 1);
$options->transition_speed      = (int)$params->get('transition_speed', 750);
$options->arrow_navigation      = (int)$params->get('arrow_navigation', 1);
$options->progress_bar          = (int)$params->get('progress_bar', 1);
$options->play_button           = (int)$params->get('play_button', 1);
$options->slide_counter         = (int)$params->get('slide_counter', 1);
$options->slide_caption         = (int)$params->get('slide_caption', 1);

$options->slides                = $slides;

JHTML::stylesheet('background_header.css', 'media/mod_backgroundheader/css/');

if ($params->get('load_jquery', 0)) {
    JHtml::script('jquery-1.6.4.js', 'media/mod_backgroundheader/js/', false);
}
JHTML::script('jquery.easing.min.js', 'media/mod_backgroundheader/js/', false);
JHTML::script('supersized.3.2.7.js', 'media/mod_backgroundheader/js/', false);
JHTML::script('supersized.shutter.js', 'media/mod_backgroundheader/theme/', false);
JHTML::script('default.js', 'media/mod_BACKGROUNDHEADER/js/', false);
?>
<script type="text/javascript">
    supersizedImgPath = '<?php echo JURI::base().'media/mod_BACKGROUNDHEADER/img/'; ?>';
    supersizedOptions = <?php echo json_encode($options); ?>;
</script>