<?php
add_thickbox();
require_once('admin-topmenu.php');
$args = array(
			'p'			=>	$_GET['slideshow'],
			'post_type'	=>	array('sz_slideshow'),
		);
$query = new WP_Query($args);
$slideshow = $query->posts[0];
?>
<form action="#" method="post">
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
    	<?php echo $slideshow->post_title; ?> 
    </h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
            	<div class="tablenav top">
                    <div class="alignleft actions add-slides">
                        <a href="#" id="insert-media-button" class="button" data-editor="content" title="Add Media"><span class="dashicons dashicons-format-image"></span> Add slides</a>
                    </div>
                    <br class="clear">
                </div>
				<table class="wp-list-table widefat sz_slider sz_slider_edit sortable">
                    <thead>
                        <tr>
                            <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
                            <th scope="col" id="thumbnail" class="manage-column column-thumbnail" style="width:75px;">Thumbnail</th>
                            <th scope="col" id="caption" class="manage-column column-caption">Caption</th>
                            <th scope="col" id="actions" class="manage-column column-actions" style="width:90px;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
                            <th scope="col" id="thumbnail" class="manage-column column-thumbnail">Thumbnail</th>
                            <th scope="col" id="caption" class="manage-column column-caption">Caption</th>
                            <th scope="col" id="actions" class="manage-column column-actions" style="width:90px;">&nbsp;</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $images = SZ_Slider::get_slides($slideshow->ID);
						$imagesCount = count($images);
                        if($images):
						$index = 0;
                        foreach($images as $id=>$image):
                        $thumbnail = wp_get_attachment_image($id, 'thumbnail', false);
                        ?>
                        <tr class="thumbnail">
                            <th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-<?php echo $id; ?>">Select <?php echo $image->post_title; ?></label>
                                <input id="cb-select-<?php echo $id; ?>" type="checkbox" name="delete-slides[]" value="<?php echo $id; ?>">
                                <div class="locked-indicator"></div>
							</th>
                            <td class="column-thumbnail"><a href="#"><?php echo $thumbnail; ?></a></td>
                            <td class="column-caption">
							<textarea class="large-text" name="sz_slidecaption[<?php echo $id; ?>]" rows="3" style="resize:none !important;"><?php echo $image->post_excerpt; ?></textarea>
                            </td>
                            <td class="column-actions"><input type="hidden" class="slideorder" name="sz_slideorder[<?php echo $id; ?>]" value="<?php echo $image->menu_order; ?>" /><input type="hidden" name="sz_slide[<?php echo $id; ?>]" value="<?php echo $id; ?>"/></td>
                        </tr>
                        <?php
                        endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
                <div class="tablenav bottom">
                    <div class="alignleft actions bulkactions">
                        <input type="submit" name="bulk-action" id="doaction" class="button button-red action" value="Delete selected slides">
                    </div>
                    <div class="tablenav-pages one-page">
                        <span class="displaying-num">
                        <?php
                        echo sprintf( _n( '1 slide', '%s slides', $imagesCount, 'snipzine' ), $imagesCount );
                        ?>
                        </span>
                    </div>
                    <br class="clear">
                </div>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3><span>Slideshow Settings</span></h3>
						<div class="inside form-sidebar">
                        	<?php
							wp_nonce_field('save_slideshow_'.$slideshow->ID);
							$options = get_post_meta($slideshow->ID,'_sz_slideshow_options',true);
							?>
                            <p class="group">
                            	<label style="line-height:28px;">Engine:</label>
                            	<select name="sz_slideshow_options[engine]" class="large-text">
                                	<option value="responsiveslidesjs" selected="selected">ResponsiveSlides.js</option>
                            	</select>
                            </p>
                            <p class="group">
                            	<label style="line-height:28px;">Theme:</label>
                            	<select name="sz_slideshow_options[theme]" class="large-text">
                                	<option value="blank" <?php if(isset($options['theme'])) selected($options['theme'],'blank'); ?>>Blank</option>
                                	<option value="clean" <?php if(isset($options['theme'])) selected($options['theme'],'clean'); ?>>Clean</option>
                                    <option value="clean-invert" <?php if(isset($options['theme'])) selected($options['theme'],'clean-invert'); ?>>Clean Invert</option>
                            	</select>
                            </p>
                            <p class="group">
                            	<label style="line-height:28px;">Size:</label>
                            	<select name="sz_slideshow_options[image_size]" class="large-text">
                                	<option value="0">Choose image size</option>
                                <?php
								global $_wp_additional_image_sizes;
								$sz_image_sizes = get_option('_sz_image_sizes');
								$sizes = get_intermediate_image_sizes();
								foreach($sizes as $size):
								?>
                                	<option <?php if(isset($options['image_size'])) selected($options['image_size'], $size, true); ?> value="<?php echo $size; ?>"><?php echo $size; ?></option>
                                <?php
								endforeach;
								?>
                            	</select>
                            </p>
                            <p class="group"><a href="javascript:toggleOptions();" style="display:block; color:#999; background:#fafafa; text-align:center; text-decoration:none; padding:5px 0px;">Advanced options</a></p>
                            <div id="engine_options">
                            <?php
							$optionfields = apply_filters('_sz_engine_options', 'responsiveslidesjs', $slideshow->ID);
							foreach($optionfields as $_html):
								?>
                                <p class="group">
                                	<?php echo $_html; ?>
                                </p>
                                <?php
							endforeach;
							?>
                            </div>
                            <p class="group">
                            	<input type='hidden' id='post_ID' name='post_ID' value='<?php echo $slideshow->ID; ?>' />
								<input class="button-primary alignright" type="submit" name="save-slideshow" value="Save" /> 
                            </p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
</form>
<script type="text/javascript">
jQuery(document).ready( function($) {
    $('table.sz_slider.sortable tbody').sortable({
        stop: function () {
            var inputs = $('input.slideorder');
            var nbElems = inputs.length;
            inputs.each(function(idx) {
                $(this).val(idx);
            });
        }
    });
	wp.media.model.settings.post.id = <?php echo $slideshow->ID?>;
});
</script>