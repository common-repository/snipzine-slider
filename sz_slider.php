<?php
/*
Plugin Name: SnipZine Slider
Plugin URI: http://www.snipzine.com
Description: WordPress plugin which integrates the following jQuery sliders: ResponsiveSlides.js,
Version: 1.0.0
Author: Catalin Moiceanu
Author URI: http://www.snipzine.com
*/
/*  Copyright 2014 SnipZine.com (email : m.catalin2006@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class SZ_Slider
{
	/*
	 * Plugin constant values
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';
	
	/*
	 * Plugin properties
	 *
	 * @since 1.0.0
	 */
    private static $instance = null;
	private static $engines = array();
	
	/*
	 * Plugin initilialization
	 *
	 * @since 1.0.0
	 */
    public static function init()
    {
		register_activation_hook(__FILE__,array(self::instance(), '_setup'));
		#-- ACTIONS
		add_action('init', array(self::instance(), 'add_post_types'));
		add_action('init', array(self::instance(), 'add_image_sizes'));
		add_action('init', array(self::instance(), 'init_sz_engines'));
		add_action('wp_enqueue_scripts', array(self::instance(), 'add_public_scripts_syles'));
		add_action('admin_menu', array(self::instance(), 'add_admin_menu'));
		add_action('admin_head', array(self::instance(), 'add_admin_scripts_styles'));
		#-- SHORTCODES
		add_shortcode('sz_slider', array(self::instance(), 'add_sz_slider_shortcode'));
		#-- FILTERS
		add_filter('wp_generate_attachment_metadata', array(self::instance(), 'on_add_slide'), 10, 2);
		add_filter('_sz_engine_options', array(self::instance(), 'get_sz_engine_options'), 10, 2);
		add_filter('_sz_engines', array(self::instance(), 'add_sz_engines'));
		
    }
    public static function instance()
    {
        is_null(self::$instance) && self::$instance = new self;
        return self::$instance;
    }
	/*
	 * Plugin setup
	 *
	 * @since 1.0.0
	 */
    public static function _setup()
    {
		if (get_option('snipzine_slider_version')!== false)
			update_option('snipzine_slider_version', self::VERSION );
		else
			add_option('snipzine_slider_version', self::VERSION);
    }
	/*
	 * Register a new post type, 'sz_slide'
	 *
	 * Each sz_slide post will act as a slide for our slideshow
	 *
	 * @since 1.0.0
	 */
	public static function add_post_types()
	{
		if(!post_type_exists('sz_slideshow')) {
			$args = array(
				'public'				=>	false,
				'publicly_queryable'	=>	false,
				'show_ui'				=>	false,
				'rewrite'				=>	array('slug'=>'sz_slideshow'),
				'capability_type'		=>	'post',
				'supports'				=>	array('title','thumbnail','editor')
			  ); 
			register_post_type( 'sz_slideshow' , $args );
		}
	}
	/*
	 * Register plugin custom image sizes
	 *
	 * @since 1.0.0
	 */
	public static function add_image_sizes()
	{
		$sizes = get_option('_sz_image_sizes', array());
		if(count($sizes)):
		foreach($sizes as $name=>$val):
			add_image_size($name, $val[0], $val[1], $val[2]);
		endforeach;
		endif;
	}
	/*
	 * Add plugin administration pages to menu
	 *
	 * @since 1.0.0
	 */
	public static function add_admin_menu()
	{
		add_menu_page('SnipZine Slider', 'SnipZine Slider', 'manage_options', 'sz_slider', array(self::instance(), 'sz_slider_template_main'), 'dashicons-slides');
		add_submenu_page('sz_slider', 'Options', 'Options', 'manage_options', 'sz_slider_options', array(self::instance(), 'sz_slider_template_options'));
	}
	/*
	 * Page template for plugin's main administration page
	 *
	 * @since 1.0.0
	 */
	public static function sz_slider_template_main()
	{
		$success = $error = '';
		if(isset($_GET['slideshow']) && is_numeric($_GET['slideshow']))	{
			if(isset($_POST['save-slideshow'])) {
				check_admin_referer('save_slideshow_'.$_GET['slideshow']);
				foreach($_POST['sz_slide'] as $id):
					$args = array(
								'ID'			=>	$id,
								'menu_order'	=>	$_POST['sz_slideorder'][$id],
								'post_excerpt'	=>	$_POST['sz_slidecaption'][$id],
							);
					wp_update_post($args);
				endforeach;
				update_post_meta($_POST['post_ID'],'_sz_slideshow_options',$_POST['sz_slideshow_options']);
			}elseif(isset($_POST['bulk-action'])){
				foreach($_POST['delete-slides'] as $id):
					if(wp_delete_post($id,true)!=FALSE){
						$success = 'Selected slides have been succesfully deleted!';
					} else {
						$error = 'There was an error deleting the selected slides.';
					}
				endforeach;
			}
			require_once(__DIR__.'/admin/admin-slideshows-edit.php');
		} else {
			if(isset($_POST['add-slideshow'])) {
				$post_id = wp_insert_post(array(
								'post_title'	=>	$_POST['sz_slideshow_title'],
								'post_type'		=>	'sz_slideshow',
								'post_status'	=>	'publish'
							));
				if($post_id) {
					$success = 'New slideshow has been succesfully added!';
				} else {
					$error = 'There was an error adding the new slideshow.';
				}
			}
			require_once(__DIR__.'/admin/admin-slideshows.php');
		}
	}
	/*
	 * Page template for plugin's options administration page
	 *
	 * @since 1.0.0
	 */
	public static function sz_slider_template_options()
	{
		$success = $error = '';
		if(isset($_POST['add-image-size']))
		{
			$sizes = get_option('_sz_image_sizes');
			$sizes[sanitize_title($_POST['sz_image_size_name'])] = array($_POST['sz_image_size_width'],$_POST['sz_image_size_height'],$_POST['sz_image_size_crop']);
			update_option('_sz_image_sizes', $sizes);
			wp_redirect(admin_url('admin.php?page=sz_slider_options'), 301); 
		}
		if(isset($_POST['update-image-sizes']))
		{
			$names = $_POST['sz_image_size_name'];
			$width = $_POST['sz_image_size_width'];
			$height = $_POST['sz_image_size_height'];
			$crop = $_POST['sz_image_size_crop'];
			$keys = array_keys($names);
			$sizes = array();
			for($i=0;$i<count($names);$i++):
				if($names[$keys[$i]]) $sizes[sanitize_title($names[$keys[$i]])] = array($width[$keys[$i]],$height[$keys[$i]],$crop[$keys[$i]]);
			endfor;
			update_option('_sz_image_sizes', $sizes);
			wp_redirect(admin_url('admin.php?page=sz_slider_options'), 301);
		}
		require_once(__DIR__.'/admin/admin-options.php');
	}
	/*
	 * Add required scripts and styles to plugin administration pages
	 *
	 * @since 1.0.0
	 */
	public static function add_admin_scripts_styles()
	{
		$screen = get_current_screen();
		if($screen->id=='toplevel_page_sz_slider' || $screen->id=='snipzine-slider_page_sz_slider_options') {
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('sz-slider-admin', plugins_url('admin/admin.min.js', __FILE__), array(), '1.0.0');
			wp_enqueue_style('sz-slider-admin', plugins_url('admin/admin.min.css', __FILE__), array(), '1.0.0');
		}
	}
	/*
	 * Add required scripts and styles to frontend
	 *
	 * @since 1.0.0
	 */
	public static function add_public_scripts_syles()
	{
		wp_enqueue_style('sz_engine_responsiveslidesjs', plugins_url('engines/responsiveslidesjs/responsiveslides.css', __FILE__), array(), '1.5.4');
		wp_enqueue_style('sz_themes', plugins_url('themes/all.css', __FILE__), array(), '1.0.0');
		wp_enqueue_script('sz_engine_responsiveslidesjs', plugins_url('engines/responsiveslidesjs/responsiveslides.min.js', __FILE__), array('jquery'), '1.5.4', true);
	}
	/*
	 * Set slide order on creating new slide
	 *
	 * @since 1.0.0
	 */
	public static function on_add_slide($metadata, $attachment_id)
	{
		$args = array(
					'post_type'			=>	'attachment',
					'post_mime_type'	=>	'image',
					'post_parent'		=>	get_post_field('post_parent', $attachment_id),
				);
        $images = self::get_slides(get_post_field('post_parent', $attachment_id));//get_children($args);
		$imagesCount = count($images);
		$imagesCount++;
		wp_update_post(array('ID'=>$attachment_id,'menu_order'=>$imagesCount));
		return $metadata;
	}
	/*
	 * Add SnipZine Slider shortcode
	 *
	 * @since 1.0.0
	 */
	public static function add_sz_slider_shortcode($atts)
	{
		 extract( shortcode_atts( array(
			  'slideshow'	=>	'',
			  'class'		=>	'rslides'
		 ), $atts ) );
		 if(!$slideshow || !is_numeric($slideshow)) return false;
		 $_slideshow = get_post($slideshow);
		 $_slides = self::get_slides($slideshow);
		 $_options = get_post_meta($slideshow,'_sz_slideshow_options',true);
		 require_once(__DIR__.'/engines/responsiveslidesjs/shortcode.php');
		 return $content;
	}
	/*
	 * Get slideshow slides
	 *
	 * @since 1.0.0
	 */
	public static function get_slides($slideshow)
	{
		 $args = array(
			  'post_type'		=>	'attachment',
			  'post_mime_type'	=>	'image',
			  'post_parent'		=>	$slideshow,
			  'orderby'			=>	'menu_order',
			  'order'			=>	'ASC',
		  );
		 return get_children($args);
	}
	/*
	 * Load javascript engines
	 *
	 * @since 1.0.0
	 */
	public static function init_sz_engines()
	{
		$engines = apply_filters('_sz_engines',self::$engines);
		self::$engines = $engines;
	}
	/*
	 * Add ResponsiveSlides.js as a SnipZine Engine
	 *
	 * @since 1.0.0
	 */
	public static function add_sz_engines($engines)
	{
		$engines['responsiveslidesjs'] = array(
					'name'		=>	'ResponsiveSlides.js',
					'options'	=>	array(
									'auto'				=>	array('select',array('true','false'),'true'),
									'speed'				=>	array('text','500'),
									'timeout'			=>	array('text','4000'),
									'pager'				=>	array('select',array('true','false'),'false'),
									'nav'				=>	array('select',array('true','false'),'false'),
									'random'			=>	array('select',array('true','false'),'false'),
									'pause'				=>	array('select',array('true','false'),'false'),
									'pauseControls'		=>	array('select',array('true','false'),'true'),
									'prevText'			=>	array('text','Previous'),
									'nextText'			=>	array('text','Next'),
									'maxwidth'			=>	array('text',''),
									'navContainer'		=>	array('text',''),
									'manualControls'	=>	array('text',''),
								)
				);
		return $engines;
	}
	/*
	 * Get snipzine engine options
	 *
	 * @since 1.0.0
	 */
	public static function get_sz_engine_options($engine, $slideshow=0)
	{
		$_options = get_post_meta($slideshow,'_sz_slideshow_options',true);
		$_selected = @$_options['options'];
		$options = self::$engines[$engine]['options'];
		$form = array();
		foreach($options as $key=>$option):
			$form[] = self::build_sz_option($key, @$option[0], @$option[1], @$option[2], @$_selected[$key]);
		endforeach;
		return $form;
	}
	/*
	 * Create html based on engine options
	 *
	 * @since 1.0.0
	 */
	private static function build_sz_option($name, $type, $options, $default, $selected)
	{
		$label = ucfirst($name);
		$html = '';
		switch($type):
			case "text":
				if($selected) $value = $selected;
				elseif(is_array($options)) $value = $options[0];
				else $value = $options;
				$html = '<label for="sz_slideshow_options_'.$name.'">'.$label.':</label> <input type="text" value="'.$value.'" name="sz_slideshow_options[options]['.$name.']" id="sz_slideshow_options_'.$name.'" class="full-text" placeholder="default: '.$default.'" />';
			break;
			case "select":
				$html = '<label for="sz_slideshow_options_'.$name.'">'.$label.':</label> <select name="sz_slideshow_options[options]['.$name.']" id="sz_slideshow_options_'.$name.'" class="large-text">';
				if(!is_array($options)) $options = array($options);
				foreach($options as $opt):
					$html .= '<option value="'.$opt.'"';
					if(($selected && $opt==$selected)||(!$selected && $opt==$default)) $html .= ' selected="selected"'; 
					$html .= '>'.$opt.'</option>';
				endforeach;
				$html .= '</select>';
			break;
			default:break;
		endswitch;
		return $html;
	}
}
SZ_Slider::init();

?>