<?php
	// Add RSS links to <head> section
	// automatic_feed_links();


	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');

    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => __('Sidebar Widgets','epicsupreme' ),
    		'id'   => 'sidebar-widgets',
    		'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));

    	register_sidebar(array(
    		'name' => __('Twtiter','epicsupreme' ),
    		'id'   => 'twitter',
    		'description'   => __( 'These are widgets for the sidebar.','html5reset' ),
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h6>',
    		'after_title'   => '</h6>'
    	));
    }

     add_action( 'wp_enqueue_scripts', 'gc_add_scripts' );

    function gc_add_scripts() {
    	/* styles */

		wp_register_style( 'base', get_template_directory_uri() . '/stylesheets/base.css', array(), '20132408', 'all' );
		wp_enqueue_style( 'base' );

		wp_register_style( 'skeleton', get_template_directory_uri() . '/stylesheets/skeleton.css', array(), '20132408', 'all' );
		wp_enqueue_style( 'skeleton' );

		wp_register_style( 'layout', get_template_directory_uri() . '/stylesheets/layout.css', array(), '20132408', 'all' );
		wp_enqueue_style( 'layout' );



		/*     	jquery */

    	wp_deregister_script('jquery');
    	wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js' );
    	wp_enqueue_script( 'jquery' );





    	/* functions */

    	wp_register_script( 'functions', get_template_directory_uri() . '/js/functions.js' );
    	wp_enqueue_script( 'functions' );

	}

	function get_ID_by_slug($slug) {
    	global $wpdb;
	    $id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'");
	    return $id;
    }


	/* Run widget in shortcode
	 * ------------------------------------------------ */

	 function widget($atts) {

    global $wp_widget_factory;

    extract(shortcode_atts(array(
        'widget_name' => FALSE
    ), $atts));

    $widget_name = wp_specialchars($widget_name);

    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));

        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;

    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;

}
add_shortcode('widget','widget');


?>