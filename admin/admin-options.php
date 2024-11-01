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
    	SnipZine Slider - Options
    </h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div id="postbox-container-2" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h3><span>Custom image sizes</span></h3>
                            <div class="inside">
                            <form action="#" method="post">
                            <?php
							global $_wp_additional_image_sizes;
							$sz_image_sizes = get_option('_sz_image_sizes');
							$sizes = get_intermediate_image_sizes();
							?>
                            <table class="wp-list-table widefat sz_slider">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col" style="width:55px; text-align:center;">Width</th>
                                        <th scope="col" style="width:55px; text-align:center;">Height</th>
                                        <th scope="col" style="width:55px; text-align:center;">Crop</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
                                foreach($sizes as $size):
								  if(isset($_wp_additional_image_sizes[$size])) {
								  if(isset($sz_image_sizes[$size])) $editable = true;
								  else $editable = false;
								  $csize = $_wp_additional_image_sizes[$size];
								  ?>
								  <tr  class="<?php if($editable) echo 'active'; else echo 'inactive'; ?>">
                                    <td scope="row">
                                    <?php
									if($editable){
									echo '<input type="text" class="large-text" id="image-size-name-'.$size.'" name="sz_image_size_name['.$size.']" value="'.$size.'" />';
									}else{
									echo $size;
									}
									?>
                                    </td>
                                    <td scope="row" align="center">
									<?php
									if($editable){
									echo '<input type="text" class="large-text" id="image-size-width-'.$size.'" name="sz_image_size_width['.$size.']" value="'.$csize['width'].'" />';
									}else{
									echo $csize['width'];
									}
									?>
                                    </td>
                                    <td scope="row" align="center">
									<?php
									if($editable){
									echo '<input type="text" class="large-text" id="image-size-height-'.$size.'" name="sz_image_size_height['.$size.']" value="'.$csize['height'].'" />';
									}else{
									echo $csize['height'];
									}
									?>
                                    </td>
                                    <td scope="row" align="center">
									<?php
									if($editable){
									echo '<select name="sz_image_size_crop['.$size.']" id="image-size-crop-'.$size.'"><option value="1" ';
									echo ($csize['crop'])?" selected":"";
									echo '>True</option>';
									echo '<option value="0" ';
									echo (!$csize['crop'])?" selected":"";
									echo '>False</option></select>';
									} else {
									echo ($csize['crop'])?'true':'false';
									}
									?></td>
								  </tr>
								  <?php
								  }
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                            <div class="tablenav bottom">
                            	<div class="alignleft">
                                	<a class="button-primary thickbox" href="#TB_inline?width=600&height=400&inlineId=add-new-image-size" title="Add new image size">Add new image size</a>
                            	</div>
                            	<div class="alignright">
                                	<input type="submit" name="update-image-sizes" id="update-image-sizes" class="button" value="Update">
                            	</div>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<br class="clear">
	</div>
</div>
<div id="add-new-image-size" class="hidden">
	<div class="form-modal">
        <h2>Add new image size</h2>
        <form action="#" method="post">
            <p>
                <label for="sz_image_size_name">Name: </label>
                <input name="sz_image_size_name" id="sz_image_size_name" type="text" value="" class="regular-text" />
            </p>
            <p>
                <label for="sz_image_size_width">Width: </label>
                <input name="sz_image_size_width" id="sz_image_size_width" type="text" value="" class="regular-text" />
            </p>
            <p>
                <label for="sz_image_size_height">Height: </label>
                <input name="sz_image_size_height" id="sz_image_size_height" type="text" value="" class="regular-text" />
            </p>
            <p>
                <label for="sz_image_size_crop">Crop: </label>
                <select name="sz_image_size_crop" id="sz_image_size_crop">
                    <option value="0">False</option>
                    <option value="1">True</option>
                </select>
            </p>
            <p>
                <input class="button-primary" type="submit" name="add-image-size" value="Add image size" /> 
            </p>
        </form>
    </div>
</div>