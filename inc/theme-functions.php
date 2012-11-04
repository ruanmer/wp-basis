<?php 
/*
 * Theme Functions 
 */

/* NO SERVER CACHE TO CSS AND JS FILES */
function prevent_file_cache() {
    if ( is_user_logged_in() ) {
        echo $nocache = '?nocache=' . rand();
    }
}

/* IMAGE FOLDER */
function theme_img_path() {
    echo get_template_directory_uri() . '/' . IMG_FOLDER_NAME . '/';
}

/* META BOX */
function get_meta_box( $meta_key, $single = true ){
    global $post;

    $meta_value = get_post_meta( $post->ID, $meta_key, $single);

    return $meta_value;
}

/* 
 * THE META BOX
 * 
 * $meta_key = ''
 * $before = ''
 * $after = ''
 * $options = 'image' || array( 'type' => 'image', 'img_size' => 'full', 'img_attr' => array( 'alt' => ... ) )
 */
function the_meta_box( $meta_key, $before = "", $after = "", $options = false ){
    global $post;

    $meta     = get_post_meta( $post->ID, $meta_key, false);
    $img_size = ( !is_array( $options ) ? 'full' : ( !$options['img_size'] ? 'full' : $options['img_size'] ) );

    foreach( $meta as $the_meta )
    {    
        $value .= $before;

        if ( $options == 'image' || $options['type'] == 'image' )
        {
            $value .= wp_get_attachment_image( $the_meta, $img_size, false, $options['img_attr'] );
        }
        else
        {
            $value .= $the_meta;
        } 

        $value .= $after;
    }
    
    echo $value;
}

/* IS POST TYPE */
if ( ! function_exists( 'is_post_type' ) ) {
    function is_post_type( $post_type ){
      if ( $post_type == get_post_type() ) return true;
    }
}

/* GET POST TYPE NAME */
if ( ! function_exists( 'get_post_type_name' ) ) {
    function get_post_type_name( $post_id = false, $singular = false ) {
        global $post;

        if ( false === $post_id ) 
            $id = $post->ID;
        elseif ( is_numeric( $post_id ) )
            $id = $post_id;

        $post_type     = get_post_type( $id );
        $post_type_obj = get_post_type_object( $post_type );

        if ( true === $singular )
            return $post_type_obj->labels->singular_name;
        else
            return $post_type_obj->labels->name;

    }
}

/* GET PAGE LINK BY SLUG */
if ( ! function_exists( 'get_page_link_by_slug' ) ) {
    function get_page_link_by_slug($page_slug) {
        return get_bloginfo('url') . "/" . $page_slug;
    }
}

/* THE CUSTOM EXCERPT */
if ( ! function_exists( 'the_custom_excerpt' ) ) {
    function the_custom_excerpt( $length, $excerpt_more="[...]", $excerpt_more_link=true ) {
        global $post, $is_home; 

        $post_text = ($post->post_excerpt) ? $post->post_excerpt : $post->post_content; 
        $post_text = preg_replace("/(<a[^>]+\><img[^>]+\><\/a>|<img[^>]+\>)/i", "", $post_text);

        $new_excerpt = substr($post_text, 0, $length);
        
        if( strlen($new_excerpt) < strlen($post_text) ) {
            if( is_string( $excerpt_more ) ){
                if ( $excerpt_more_link ){
                    $new_excerpt = $new_excerpt .' <a href="'. get_permalink($post->ID) .'" title="Continue lendo: '. the_title_attribute( array( 'echo' => 0 ) ) .'" class="read_more read_more_excerpt">';
                }

                $new_excerpt = $new_excerpt . $excerpt_more;

                if ( $excerpt_more_link ){
                    $new_excerpt = $new_excerpt .'</a>';
                }
            }
        }

        // return
        echo '<p>'.$new_excerpt.'</p>';
    }
}

/* THE CONTENT BY ID */
if ( ! function_exists( 'the_content_by_id' ) ) {
    function the_content_by_id( $id ) {
        $post = get_post($id);
        $content = $post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]>', $content);

        echo $content;
    }
}

/* GET THE POST THUMBNAIL SRC */
if ( ! function_exists( 'get_the_post_thumbnail_src' ) ) {
    function get_the_post_thumbnail_src( $id = true, $size = 'full' ) {
        global $post;

        $id = $post->ID;

        $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );
        
        return $image_url[0];
    }
}

/* THE TERM NAME */
if ( ! function_exists( 'the_term_name' ) ) {
    function the_term_name(){
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
        
        echo $term->name;
    }
}

/* CUSTOM OPTION TREE FUNCTIONS */
if ( function_exists( 'get_option_tree' ) ) {

    function get_option_tree_c( $item_id = '', $echo = false, $before = '', $after = '', $link = false, $image = false ) {
        $options = get_option( 'option_tree' );    

        // no value return
        if ( !isset( $options[$item_id] ) || empty( $options[$item_id] ) )
            return;

        // set content value & strip slashes
        $content = option_tree_stripslashes( $options[$item_id] );

        /*
         * IMAGE
         * $image = array (
         *  'title' => 'Foo'
         * )
         */
        if( !empty( $image ) ) 
        {
            $content = '<img src="' . $content . '" alt="' . $image['alt'] . '" title="' . $image['alt'] . '" />';
        }

        /*
         * LINK 
         *
         * $link = array( 
         *  'href' => true || 'http://www.site.com',
         *  'target_blank' => true || false,
         *  'content' => true || 'Foo',
         *  'id' => 'Foo'
         * )
         */
        if( !empty( $link ) || $link['href'] )
        {
            $href = ( $link === true || $link['href'] === true ) ? option_tree_stripslashes( $options[$item_id] ) : $link['href'];
            $anchor_id = $link['id'] ? ' id="'. $link['id'] .'"' : ''; 
            $anchor_class = $link['class'] ? ' class="'. $link['class'] .'"' : ''; 
            $target_blank = $link['target_blank'] ? ' target="_blank"' : ''; 
            $link_content = ( $link === true || $link['content'] === true ) ? $content : $link['content'];

            $content = '<a href="' . $href . '"' . $anchor_id . $anchor_class . $target_blank . '>' . $link_content .'</a>';
        }

        // befor and after
        $content = $before . $content . $after;

        // echo content
        if ( $echo === true )
        {
            echo $content;  
        }        
        else
        {
            return $content;
        }

    }    
}

/* BACK TO */
if ( function_exists( 'back_to' ) ) {
    function back_to( $url = '/', $txt = 'Voltar', $classes = '' ) {
        echo '<a href="' . home_url( $url ) . '" class="back ' . $classes .'"><span>' . $txt . '</span></a>';
    }
}

/* NO RESULTS */
if ( function_exists( 'no_result' ) ) {
    function no_result( $text = 'Nenhum resultado encontrado.' ) {
        echo '<article id="post-0" class="no-results">' . $text . '</article>';
    }
}

/* CUSTOM WP LIST CATEGORIES */
function custom_wp_list_categories( $args = '', $item_classes = '', $anchor_classes = '', $wrap_text_elem = '' ) {
    $categories = get_categories( $args );

    foreach( $categories as $category ) { 
        echo '  
            <li class="' . ( $item_classes ? $item_classes . ' ' : '' ) . 'cat-item cat-item-' . $category->term_id . '">
                <a href="' . get_category_link( $category->term_id ) . '" class="' . $anchor_classes . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>
                    ' . ( $wrap_text_elem ? '<' . $wrap_text_elem . '>' : '' ) . $category->name . ( $wrap_text_elem ? '</' . $wrap_text_elem . '>' : '' ) . '
                </a>
            </li>
        '; 
    } 
}

/* PAGINATION */
if ( ! function_exists( 'wp_pagination' ) ) {
    function wp_pagination( $pagination_id = '', $pagination_classes = '' ) {
        global $wp_query;

        if ( $wp_query->max_num_pages > 1 ) : ?>
            <nav id="<?php echo $pagination_id; ?>" class="pagination<?php if ( $pagination_classes ) echo ' ' . $pagination_classes; ?>">
        <?php
            if ( function_exists( 'wp_paginate' ) ) {
                wp_paginate();
            }
            else { ?>
                <div class="pagination-prev"><?php previous_posts_link( 'Anterior' ); ?></div>    
                <div class="pagination-next"><?php next_posts_link( 'Próximo' ); ?></div>         
        <?php 
            } 
        ?>
            </nav><!-- /.pagination -->
<?php       
        endif;      
    }
}

/* 
 * HIERARCHICAL LIST OF PAGES 
 * 
 * @return ancestors, parent, siblings, children of current page
 */
if ( ! function_exists( 'wp_hierarchical_list_pages' ) ) {
    function wp_hierarchical_list_pages() {
        global $post;

        /* if has parent */
        if( $post->post_parent ) {
            $parent_id  = $post->post_parent;

            /* while has parent */
            while ( $parent_id ) {
                $page = get_page($parent_id);
                $children = wp_list_pages("title_li=&child_of=".$parent_id."&echo=0");
                $parent_id  = $page->post_parent;
            }
        }
        /* else */
        else { 
            $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
        }

        /* echo */
        if ( !empty( $children ) )
            echo $children;
    }
}

/* WP LIST POSTS */
if ( ! function_exists( 'wp_list_posts' ) ) {
    function wp_list_posts( $wlp_args ){

        $defaults = array( 'numberposts' => -1 );
        
        $myposts = get_posts( wp_parse_args( $wlp_args, $defaults ) );

        foreach( $myposts as $post ) : ?>
        <li>
            <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
        </li>
    <?php 
        endforeach;
    }
}

