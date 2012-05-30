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
        echo '<div class="no-results">' . $text . '</div>';
    }
}
