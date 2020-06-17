<?php
/**
 * Register widgets
 */
function shp_api_widgets_init() {
    register_sidebar( array(
        'name'          => 'Contact Page Half',
        'id'            => 'contact_page_half',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
    ) );
}
add_action( 'widgets_init', 'shp_api_widgets_init' );

add_filter('bloginfo', 'do_shortcode');

add_post_type_support( 'page', 'excerpt' );

if (get_option( 'close_comments_for_old_posts' ) && get_option( 'close_comments_days_old' ) == 0) {
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
}

function custom_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'custom_excerpt_length');

function content_replace_text($text) {
    $replace = array(
        // 'WORD TO REPLACE' => 'REPLACE WORD WITH THIS'
        '<table>' => '<table class="table table-bordered table-striped">',
    );
    $text = str_replace(array_keys($replace), $replace, $text);
    return $text;
}
add_filter('the_content', 'content_replace_text');

// Register and load the navigation widget
function wpb_load_widget() {
    register_widget( 'page_navigation_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

class page_navigation_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
        'page_navigation_widget',
        __('Page Navigation Widget', 'page_navigation_widget_domain'),
        array( 'description' => __( 'Add navigation to page', 'page_navigation_widget_domain' ), )
        );
    }

    public function widget( $args, $instance ) {
        the_post_navigation (
            array (
                'prev_text' => '&laquo; <span>%title</span>',
                'next_text' => '<span>%title</span> &raquo;',
            )
        );
    }
} // page_navigation_widget end

function resolveLanguage()
{
    return 'en_US';
};
add_filter('locale', 'resolveLanguage');
?>

