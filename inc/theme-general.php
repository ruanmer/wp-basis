<?php 
/*
 * Theme General Settings
 */


/*
 * Add caps to Editor
 */
if ( ADD_CAPS_TO_EDITOR === true ) {
    function add_theme_caps() {
        $role = get_role( 'editor' );

        $role->add_cap( 'edit_theme_options' );
    }
    add_action( 'admin_init', 'add_theme_caps');
}

/*
 * Post Name in body_class
 */
add_filter( 'body_class', 'post_name_in_body_class' );

function post_name_in_body_class( $classes ){
    if( is_singular() )
    {
        global $post;
        array_push( $classes, "{$post->post_type}-{$post->post_name}" );
    }
    return $classes;
}

/*
 * Browser Classes
 */
add_filter('body_class','browser_body_class');

function browser_body_class($classes) {  
    $browser = $_SERVER[ 'HTTP_USER_AGENT' ];
 
    // Mac, PC, Linux, iPad, iPhone, iPod
    if ( preg_match( "/iPad/", $browser ) ){
        $classes[] = 'ipad touch';
 
    } elseif ( preg_match( "/iPod/", $browser ) ){
        $classes[] = 'ipod touch';
 
    } elseif ( preg_match( "/iPhone/", $browser ) ){
        $classes[] = 'iphone touch';

    } elseif ( preg_match( "/Mac/", $browser ) ){
        $classes[] = 'mac';
 
    } elseif ( preg_match( "/Windows/", $browser ) ){
        $classes[] = 'windows';
 
    } elseif ( preg_match( "/Linux/", $browser ) ) {
        $classes[] = 'linux';
 
    } else {
        $classes[] = 'unknown-os';
    }
 
    // Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
    if ( preg_match( "/Chrome/", $browser ) ) {
        $classes[] = 'chrome';

    } elseif ( preg_match( "/Safari/", $browser ) ) {
        $classes[] = 'safari';

    } elseif ( preg_match( "/Opera/", $browser ) ) {
        $classes[] = 'opera';

    } elseif ( preg_match( "/MSIE/", $browser ) ) {
        $classes[] = 'msie';

        if( preg_match( "/MSIE 6.0/", $browser ) ) {
            $classes[] = 'ie6';
        } elseif ( preg_match( "/MSIE 7.0/", $browser ) ){
            $classes[] = 'ie7';
        } elseif ( preg_match( "/MSIE 8.0/", $browser ) ){
            $classes[] = 'ie8';
        } elseif ( preg_match( "/MSIE 9.0/", $browser ) ){
            $classes[] = 'ie9';
        }

    } elseif ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
        $classes[] = 'firefox';

        preg_match( "/Firefox\/(\d)/si", $browser, $matches);
        $ff_version = 'ff' . str_replace( '.', '-', $matches[1] );
        $classes[] = $ff_version;

    } else {
        $classes[] = 'unknown-browser';
    }
 
    return $classes;
}

/*
 * Excerpt Length
 */
function theme_excerpt_length($length) {
    return POST_EXCERPT_LENGTH;
}
add_filter('excerpt_length', 'theme_excerpt_length');


/* 
 * Homepage in Nav Menu
 */
function show_home_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'show_home_page_menu_args' );


/*
 * Custom Post Type Archives in Nav Menus
 * Adds an archive checkbox to the nav menu meta box for Custom Post Types that support archives
 */
class cptArchiveNavMenu {
    public function __construct() {
        add_action( 'admin_head-nav-menus.php', array( $this, 'add_filters' ) );
    }

    public function add_filters() {
        $post_type_args = array(
        'show_in_nav_menus' => true
        );

        $post_types = get_post_types( $post_type_args, 'object' );

        foreach ( $post_types as $post_type ) {
            if ( $post_type->has_archive ) {
                add_filter( 'nav_menu_items_' . $post_type->name, array( $this, 'add_archive_checkbox' ), null, 3 );
            }
        }
    }

    public function add_archive_checkbox( $posts, $args, $post_type ) {
        global $_nav_menu_placeholder, $wp_rewrite;

        $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1;

        //dump( $post_type, '$post_type', 'htmlcomment' );

        $archive_slug = $post_type['args']->has_archive === true ? $post_type['args']->rewrite['slug'] : $post_type['args']->has_archive;
        
        if ( $post_type['args']->rewrite['with_front'] )
            $archive_slug = substr( $wp_rewrite->front, 1 ) . $archive_slug;
        else
            $archive_slug = $wp_rewrite->root . $archive_slug;

        array_unshift( $posts, (object) array(
            'ID' => 0,
            'object_id' => $_nav_menu_placeholder,
            'post_content' => '',
            'post_excerpt' => '',
            'post_title' => $post_type['args']->labels->all_items,
            'post_type' => 'nav_menu_item',
            'type' => 'custom',
            'url' => site_url( $archive_slug ),
        ) );

        return $posts;
    }
}

$cptArchiveNavMenu = new cptArchiveNavMenu();