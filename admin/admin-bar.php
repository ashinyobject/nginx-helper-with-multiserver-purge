<?php

namespace rtCamp\WP\Nginx {

    class AdminBar {

        function __construct() {
            add_action( 'admin_bar_menu', array( &$this, 'nginx_toolbar_purge_item' ), 999 );
           
        }

        function nginx_toolbar_purge_item( $admin_bar ) {
            global $tag, $wp_the_query;
            if ( !current_user_can( 'manage_options' ) ) {
                return;

            }
     
            if ( is_admin() ) {
                $current_screen = get_current_screen();
                $post = get_post();
        
                if ( 'post' == $current_screen->base
                    && 'edit' != $current_screen->action
                    && 'publish' == $post->post_status )
                {
                    $purge_url = add_query_arg( array( 'nginx_helper_action' => 'purge_url', 'nginx_main_type' => 'post', 'nginx_sub_type' => $post->post_type, 'nginx_purge_id' => $post->ID ) );
                    $nonced_url = wp_nonce_url( $purge_url, 'nginx_helper-purge_all' );
                 
                    $admin_bar->add_menu( array(
                        'id' => 'nginx-helper-purge-all',
                        'title' => __( 'Purge '.$post->post_type, 'nginx-helper' ),
                        'href' => $nonced_url,
                        'meta' => array( 'title' => __(  'Purge '.$post->post_type, 'nginx-helper' ), ),
                    ) );
                    
                } 
                elseif ( 'term' == $current_screen->base
                    && isset( $tag ) && is_object( $tag ) && ! is_wp_error( $tag )
                    && ( $tax = get_taxonomy( $tag->taxonomy ) )
                    && $tax->public )
                {
                    $purge_url = add_query_arg( array( 'nginx_helper_action' => 'purge_url', 'nginx_main_type' => 'taxonomy', 'nginx_sub_type' => $tag->taxonomy, 'nginx_purge_id' => $tag->term_id ) );
                    $nonced_url = wp_nonce_url( $purge_url, 'nginx_helper-purge_all' );
                    $admin_bar->add_menu( array(
                        'id' => 'nginx-helper-purge-all',
                        'title' => __( 'Purge '.$tag->taxonomy, 'nginx-helper' ),
                        'href' => $nonced_url,
                        'meta' => array( 'title' => __(  'Purge '.$tag->taxonomy, 'nginx-helper' ), ),
                    ) );
                } 
            } 
            else {
                $current_object = $wp_the_query->get_queried_object();
      
                if ( empty( $current_object ) )
                    return;
        
                if ( ! empty( $current_object->post_type )
                    && 'publish' == $current_object->post_status )
                {
                    $purge_url = add_query_arg( array( 'nginx_helper_action' => 'purge_url_non_admin', 'nginx_main_type' => 'post', 'nginx_sub_type' => $current_object->post_type, 'nginx_purge_id' => $current_object->ID ),network_admin_url().'admin.php' );
                    $nonced_url = wp_nonce_url( $purge_url, 'nginx_helper-purge_all' );
                 
                    $admin_bar->add_menu( array(
                        'id' => 'nginx-helper-purge-all',
                        'title' => __( 'Purge '.$current_object->post_type, 'nginx-helper' ),
                        'href' => $nonced_url,
                        'meta' => array( 'title' => __(  'Purge '.$current_object->post_type, 'nginx-helper' ), ),
                    ) );
                } elseif ( ! empty( $current_object->taxonomy )
                    && ( $tax = get_taxonomy( $current_object->taxonomy ) )
                    && $tax->public )
                {
                    $purge_url = add_query_arg( array( 'nginx_helper_action' => 'purge_url_non_admin', 'nginx_main_type' => 'taxonomy', 'nginx_sub_type' => $current_object->taxonomy, 'nginx_purge_id' => $current_object->term_id ),network_admin_url().'admin.php' );
                    $nonced_url = wp_nonce_url( $purge_url, 'nginx_helper-purge_all' );
                    $admin_bar->add_menu( array(
                        'id' => 'nginx-helper-purge-all',
                        'title' => __( 'Purge '.$current_object->taxonomy, 'nginx-helper' ),
                        'href' => $nonced_url,
                        'meta' => array( 'title' => __(  'Purge '.$current_object->taxonomy, 'nginx-helper' ), ),
                    ) );
                } 
            }
        }
            


    }
}
