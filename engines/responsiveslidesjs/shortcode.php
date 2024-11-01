<?php
$list = "<div class=\"sz_slider_wrapper %s\"><ul class=\"%s\">%s</ul>%s</div>";
$list_item = array();
$has_captions = false;
foreach($_slides as $slide):
	$list_item_content = wp_get_attachment_image($slide->ID, $_options['image_size'], false);
	if($slide->post_excerpt) {
		$list_item_content .= '<p class="caption">'.$slide->post_excerpt.'</p>';
		$has_captions = true;
	}
	$list_item[] = '<li>'.$list_item_content.'</li>';
endforeach;
$list_items = implode('',$list_item);
if($_options['theme']=='clean' || $_options['theme']=='clean-invert') {
	$_options['options']['prevText'] = $_options['options']['nextText'] = '';
}
$js = '
<script>
jQuery(document).ready(function($) {
	$(".'.$class.'").responsiveSlides({
		auto: '.$_options['options']['auto'].',
		speed: '.$_options['options']['speed'].',
		timeout: '.$_options['options']['timeout'].',
		pager: '.$_options['options']['pager'].',
		nav: '.$_options['options']['nav'].',
		random: '.$_options['options']['random'].',
		pause: '.$_options['options']['pause'].',
		pauseControls: '.$_options['options']['pauseControls'].',
		prevText: "'.$_options['options']['prevText'].'",
		nextText: "'.$_options['options']['nextText'].'",
		maxwidth: "'.$_options['options']['maxwidth'].'",
		navContainer: "'.$_options['options']['navContainer'].'",
		manualControls: "'.$_options['options']['manualControls'].'",
		namespace: "rslides"
	});
});
</script>';
if($has_captions) $_options['theme'] .= ' has_captions';
$content = sprintf($list, $_options['theme'], $class, $list_items, $js);
?>