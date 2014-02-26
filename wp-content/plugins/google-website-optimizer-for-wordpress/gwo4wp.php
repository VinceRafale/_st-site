<?php 

/*
Plugin Name: Google Website Optimizer for WordPress
Plugin URI: http://www.masteringlandingpages.com/gwo4wp
Description: Test your WordPress Landing Pages with Google Website Optimizer for WordPress 
Version: 2.0
Author: Filippo Toso
Author URI: http://www.masteringlandingpages.com/
*/

/*  Copyright 2009 Filippo Toso (email : info@masteringlandingpages.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('GWO_META_NAME', 'gwo4wp');
define('GWO_CONTROL_IN_HEAD', true);

class GW04WP {

	var $meta    = 'gwo4wp';
	var $control_in_head   = true;
	var $control_script    = '';
	var $tracking_script   = '';
	var $conversion_script = ''; 

	function GW04WP($meta = 'gwo4wp', $control_in_head = true) {
		$this->__construct($meta, $control_in_head);
	}
	
	function __construct($meta = 'gwo4wp', $control_in_head = true) {
		$this->meta = $meta;
		$this->control_in_head = $control_in_head;
		add_action('plugins_loaded', array(&$this, 'action_plugins_loaded'));
		add_action('add_meta_boxes', array(&$this, 'action_add_meta_boxes'));
	}

	function action_add_meta_boxes() {
		add_meta_box('gwo_section', 'Google Website Optimizer', array(&$this, 'meta_box_post'), 'post', 'normal', 'high');
		add_meta_box('gwo_section', 'Google Website Optimizer', array(&$this, 'meta_box_post'), 'page', 'normal', 'high');
	}

	function action_plugins_loaded() {
		if (is_admin()) {
			add_action('save_post', array(&$this, 'action_save_post'), 1, 2);
		} else {
			add_action('template_redirect',  array(&$this, 'action_template_redirect'));
		}
	}
	
	function action_wp_head() {
		echo('<meta name="generator" content="Google Website Optimizer for WordPress - http://www.masteringlandingpages.com/gwo4wp" />');
		if ($this->control_in_head) {
			echo($this->control_script);
		}
	}
	
	function action_template_redirect() {

		if (is_single() || is_page()) {
			global $post;
			$options = get_post_meta($post->ID, $this->meta, true);
			if (isset($options['enabled']) && $options['enabled']) {

				add_action('wp_head',  array(&$this, 'action_wp_head'));

				$this->control_script    = isset($options['control_script'])    ? trim($options['control_script'])    : '';
				$this->tracking_script   = isset($options['tracking_script'])   ? trim($options['tracking_script'])   : '';
				$this->conversion_script = isset($options['conversion_script']) ? trim($options['conversion_script']) : '';

				ob_start(array(&$this, 'ob_start_callback'));

			}		
		}

	}
	
	function ob_start_callback($content) {
		if (!$this->control_in_head) {
			$html = $this->control_script;
			if ($html != '') {
				$content = preg_replace('#<body[^>]*>#si', "$0\r\n{$html}\r\n", $content);
			}
		}
		$html = trim($this->tracking_script . "\r\n" . $this->conversion_script);
		if ($html != '') {
			$content = preg_replace('#</body[^>]*>#si', "\r\n{$html}\r\n$0", $content);
		}
		return $content;
	}
	
	function action_save_post($post_id) {

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		} 
		if (!wp_verify_nonce($_POST[$this->meta . '_noncename'], plugin_basename( __FILE__ ))) {
			return;
		}
		if (wp_is_post_revision($post_id)) {
			return;
		}
	
		$edit = ('page' == $_POST['post_type']) ? 'edit_page' : 'edit_post';
		if (!current_user_can($edit, $post_id)) {
			return;
		}
		
		$options = array();
		$options['enabled']           = isset($_POST['enable_gwo']) && ($_POST['enable_gwo'] == '1');
		$options['control_script']    = isset($_POST['control_script'])    ? trim($_POST['control_script'])    : '';
		$options['tracking_script']   = isset($_POST['tracking_script'])   ? trim($_POST['tracking_script'])   : '';
		$options['conversion_script'] = isset($_POST['conversion_script']) ? trim($_POST['conversion_script']) : '';
		if (!update_post_meta($post_id, $this->meta, $options)) {
			add_post_meta($post_id, $this->meta, $options); 
		}
	}
	
	function meta_box_post() {
	
		global $post;
		$options = get_post_meta($post->ID, $this->meta, true);

		if (is_array($options)) {
			$options['enabled']           = isset($options['enabled'])           ?  (bool)$options['enabled']          : false;	 
			$options['control_script']    = isset($options['control_script'])    ? trim($options['control_script'])    : '';	
			$options['tracking_script']	  = isset($options['tracking_script'])   ? trim($options['tracking_script'])   : '';
			$options['conversion_script'] = isset($options['conversion_script']) ? trim($options['conversion_script']) : '';
		} else {
			$options['enabled'] = false;
			$options['control_script']    = '';		
			$options['tracking_script']	  = '';	
			$options['conversion_script'] = '';
		}	
		
		$url = get_bloginfo('wpurl') . '/' .  PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
		
		  wp_nonce_field(plugin_basename( __FILE__ ), $this->meta . '_noncename');

		
?>
<table border="0" width="100%">
  <tr>
    <td colspan="2">To use this plugin you must register a <a href="http://www.google.com/websiteoptimizer" target="_blank">Google Website Optimizer</a> account. It's free.</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="checkbox" name="enable_gwo" value="1" <?php if ($options['enabled']) { echo('checked="checked"'); } ?> /> <label for="enable_gwo">Enable the Google Website Optimizer support for this page/post.</label></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label for="control_script" style="color: #CC6600; font-weight: bold;">Control Script</label></td>
  </tr>
  <tr>
    <td width="35" valign="top"><img src="<?php echo($url); ?>/control_script.gif" align="Control Script" width="30" height="37" /></td>
    <td><textarea rows="2" cols="40" name="control_script" tabindex="5" id="control_script" style="width: 98%"><?php echo(htmlentities($options['control_script'])); ?></textarea>
      <br/>
      <span style="font-size: 11px">Insert here the <strong style="color: #CC6600;">Control Script</strong> provided by the Google Website Optimizer. <br/>
      You must fill this field only if this page/post is your <strong>Original page</strong>.</span> </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label for="tracking_script" style="color: #006600; font-weight: bold;">Tracking Script</label></td>
  </tr>
  <tr>
    <td width="35" valign="top"><img src="<?php echo($url); ?>/tracking_script.gif" align="Tracking Script" width="30" height="37"  /></td>
    <td><textarea rows="2" cols="40" name="tracking_script" tabindex="5" id="tracking_script" style="width: 98%"><?php echo(htmlentities($options['tracking_script'])); ?></textarea>
      <br/>
      <span style="font-size: 11px">Insert here the <strong style="color: #006600;">Tracking Script</strong> provided by the Google Website Optimizer. <br/>
      You must fill this field only if this page/post is your <strong>Original page</strong> or a <strong>Variation page</strong>.</span> </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label for="conversion_script" style="color: #660000; font-weight: bold;">Conversion Script</label></td>
  </tr>
  <tr>
    <td width="35" valign="top"><img src="<?php echo($url); ?>/conversion_script.gif" align="Conversion Script" width="30" height="37"  /></td>
    <td><textarea rows="2" cols="40" name="conversion_script" tabindex="5" id="conversion_script" style="width: 98%"><?php echo(htmlentities($options['conversion_script'])); ?></textarea>
      <br/>
      <span style="font-size: 11px">Insert here the <strong style="color: #660000;">Conversion Script</strong> provided by the Google Website Optimizer. <br/>
      You must fill this field only if this page/post is your <strong>Conversion  page</strong>.</span> </td>
  </tr>
</table>
<?php		
	}

}

$gwo = new GW04WP(GWO_META_NAME, GWO_CONTROL_IN_HEAD);

?>