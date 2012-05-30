<?php
/*
 * Clean Up Wordpress


/* remove WordPress version from RSS feed */
function theme_no_generator() { return ''; }
add_filter('the_generator', 'theme_no_generator');


/* remove CSS from recent comments widget */
function remove_recent_comments_style() {
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
    }
}


/* theme head clean up */
function theme_head_cleanup() {
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'index_rel_link');
  remove_action('wp_head', 'parent_post_rel_link', 10, 0);
  remove_action('wp_head', 'start_post_rel_link', 10, 0);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

  add_action('wp_head', 'remove_recent_comments_style', 1);
}
add_action('init', 'theme_head_cleanup');


/* clean up the default WordPress style tags */
add_filter('style_loader_tag', 'theme_clean_style_tag');

function theme_clean_style_tag($input) {
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
  //only display media if it's print
  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}


/* Contact Form 7 - Deregister Scripts & Styles */
if ( function_exists( 'wpcf7' ) ){

    add_action( 'wp_print_scripts', 'cf7_deregister_javascript', 100 );
    function cf7_deregister_javascript() {
        global $post;
        
        $postcontent = $post->post_content;
        $shortcode = '[contact-form-7';
        $strpos = strpos($postcontent, $shortcode);
        
        if ( $strpos === false && !is_page_template( 'page-contato.php' ) ) {
            wp_deregister_script( 'contact-form-7' );
        }
    }

    add_action( 'wp_print_styles', 'cf7_deregister_styles', 100 );
    function cf7_deregister_styles() {
        global $post;

        $postcontent = $post->post_content;
        $shortcode = '[contact-form-7';
        $strpos = strpos($postcontent, $shortcode);

        if ( $strpos === false && !is_page_template( 'page-contato.php' ) ) {
            wp_deregister_style( 'contact-form-7' );
        }
    }

}


/*---------------------------------*
  CLEAN ADMIN
*---------------------------------*/
/* Remove Post Columns */
if ( HAS_POST === true ){
	add_filter('manage_posts_columns', 'remove_post_columns');
	
	function remove_post_columns($defaults) {
		if ( HAS_POST_CATEGORIES ) unset($defaults['categories']);
		if ( HAS_POST_TAGS ) unset($defaults['tags']);
		if ( HAS_POST_COMMENTS ) unset($defaults['comments']);
		return $defaults;
	}
}


/* Remove Dashboard Widgets */
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); 
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);   
}


/* Remove WP Logo from Admin Bar */
function remove_wp_logo_admin_bar() {
  global $wp_admin_bar;

  $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'remove_wp_logo_admin_bar', 0);


/* Custom Admin Footer */
function custom_admin_footer() {
  echo 'Desenvolvido por <a href="' . THEME_AUTHOR_URL . '" target="_blank">' . THEME_AUTHOR . '</a>.';
}
add_filter('admin_footer_text', 'custom_admin_footer');


/* Remove Footer Version */
function remove_footer_version() {
    return ' ';
}
add_filter( 'update_footer', 'remove_footer_version', 9999);


/* Remove Admin Bar Links */
if ( HAS_POST === false || HAS_LINK === false ){
	function remove_admin_bar_links() {
		global $wp_admin_bar;
		if ( ! HAS_POST ) $wp_admin_bar->remove_menu('new-post');
		if ( ! HAS_LINK ) $wp_admin_bar->remove_menu('new-link');
	}
	add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
}
