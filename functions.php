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

/**
 * Add query arguments to post count api
 */
add_filter( 'shoptet_post_count_query_args', function($query_args) {
    return [
        'developersArticlesCount' => [
        'post_type' => 'post',
        'post_status' => 'publish',
        ],
    ];
} );

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

add_action( 'wp_print_scripts', function () {
    global $post;
    if ( !is_front_page() && (!is_a( $post, 'WP_Post' ) || 'contact' != $post->post_name) ) {
        wp_dequeue_script( 'google-recaptcha' );
        wp_dequeue_script( 'wpcf7-recaptcha' );
    }
});

add_filter( 'shp_dl_page', function( $page ) {
    $page['category'] = get_the_first_category();
    $page['subCategory'] = get_the_first_subcategory();
    $page['type'] = get_datalayer_type();
    return $page;
} );
  
function get_the_first_category($subcategories_only = false) {
    $category = 'not_available_DL';
    if (is_category() && $term = get_queried_object()) {
      if ($term->parent > 0) {
        $category = $subcategories_only ? $term->name : get_cat_name($term->parent);
      } else if ($term->parent == 0 && !$subcategories_only) {
        $category = $term->name;
      }
    } else if (is_single() && $categories = get_the_category()) {
      $categories = array_values(array_filter($categories, function ($c) use ($subcategories_only) {
        return $subcategories_only ? $c->parent > 0 : $c->parent == 0;
      }));
      if ($categories) {
        $category = $categories[0]->name;
      }
    }
    return $category;
}
  
function get_the_first_subcategory() {
    return get_the_first_category(true);
}
  
function get_datalayer_type() {
    $type = 'other';
    if (is_front_page()) {
      $type = 'home';
    } else if (is_category()) {
      $type = 'category';
    } else if (is_single()) {
      $type = 'article';
    }
    return $type;
}