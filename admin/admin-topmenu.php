<?php $screen = get_current_screen(); ?>
<h2 class="nav-tab-wrapper">
	<a href="<?php echo admin_url('admin.php?page=sz_slider');?>" class="nav-tab<?php if($screen->id=='toplevel_page_sz_slider' && !isset($_GET['slideshow'])) echo ' nav-tab-active';?>">Slideshows</a>
	<a href="<?php echo admin_url('admin.php?page=sz_slider_options');?>" class="nav-tab<?php if($screen->id=='snipzine-slider_page_sz_slider_options') echo ' nav-tab-active';?>">Options</a>
</h2>