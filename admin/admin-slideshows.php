<?php
add_thickbox();
require_once('admin-topmenu.php');
?>
<div class="wrap">
	<?php if($success) : ?>
    <div class="updated below-h2">
    	<p><?php echo $success; ?></p>
    </div>
    <?php elseif($error) : ?>
    <div class="error below-h2">
    	<p><?php echo $error; ?></p>
    </div>
    <?php endif; ?>
	<h2>
    	SnipZine Slider - Slideshows
        <a class="button-primary alignright thickbox" href="#TB_inline?width=600&height=350&inlineId=add-new-slideshow" title="Add new slideshow">Add new slideshow</a>
    </h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
            <table class="wp-list-table widefat sz_slider">
            	<thead>
                	<tr>
                    	<th scope="col" id="slider_id" class="manage-column column-slider_id" style="width:40px;">ID</th>
                    	<th scope="col" id="name" class="manage-column column-name">Slideshow</th>
                        <th scope="col" id="slides" class="manage-column column-slides" style="width:90px;">Slides</th>
                        <th scope="col" id="shortcode" class="manage-column column-shortcode" style="width:250px;">Shortcode</th>
                    </tr>
                </thead>
                <tfoot>
                	<tr>
                    	<th scope="col" class="manage-column column-slider_id">ID</th>
                    	<th scope="col" class="manage-column column-name">Slideshow</th>
                        <th scope="col" class="manage-column column-slides">Slides</th>
                        <th scope="col" class="manage-column column-shortcode">Shortcode</th>
                    </tr>
                </tfoot>
                <tbody>
                	<?php
					$args = array(
								'post_type'			=>	array('sz_slideshow'),
								'posts_per_page'	=>	'-1',
							);
					$query = new WP_Query($args);
					if(count($query->posts)):
					foreach($query->posts as $slideshow):
					?>
                	<tr class="active">
                        <td class="column-slider_id"><?php echo $slideshow->ID; ?></td>
                        <td class="column-name"><a href="<?php echo admin_url('admin.php?page=sz_slider&slideshow='.$slideshow->ID);?>"><?php echo $slideshow->post_title; ?></a></td>
                        <td class="column-slides"><?php echo count(SZ_Slider::get_slides($slideshow->ID)); ?> slides</td>
                        <td class="column-shortcode">[sz_slider slideshow="<?php echo $slideshow->ID; ?>"]</td>
                    </tr>
                    <?php
					endforeach;
					endif;
					?>
                </tbody>
            </table>
		</div>
		<br class="clear">
	</div>
</div>
<div id="add-new-slideshow" class="hidden">
	<div class="form-modal">
        <h2>Add new slideshow</h2>
        <form action="#" method="post">
            <p>
                <label for="sz_slideshow_title">Title: </label>
                <input name="sz_slideshow_title" id="sz_slideshow_title" type="text" value="" class="regular-text" />
            </p>
            <p>
                <input class="button-primary" type="submit" name="add-slideshow" value="Add slideshow" /> 
            </p>
        </form>
    </div>
</div>