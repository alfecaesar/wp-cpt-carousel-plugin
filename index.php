<?php
/**
 * Plugin Name: Custom Post Type Carousel
 * Description: A wordpress plugin to display custom post types in a Swiper.js carousel. Shortcode [cpt_carousel]
 * Version: 1.0.0
 * Author: Alfe Caesar Lagas
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Register the custom post type
function cpt_carousel_register_custom_post_type() {
    $labels = array(
        'name'               => 'Carousel Items',
        'singular_name'      => 'Carousel Item',
        'menu_name'          => 'Carousel Items',
        'name_admin_bar'     => 'Carousel Item',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Carousel Item',
        'new_item'           => 'New Carousel Item',
        'edit_item'          => 'Edit Carousel Item',
        'view_item'          => 'View Carousel Item',
        'all_items'          => 'All Carousel Items',
        'search_items'       => 'Search Carousel Items',
        'not_found'          => 'No carousel items found.',
        'not_found_in_trash' => 'No carousel items found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'carousel-item' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );

    register_post_type( 'carousel_item', $args );
}
add_action( 'init', 'cpt_carousel_register_custom_post_type' );


function cpt_carousel_enqueue_scripts() {
    // Swiper CSS
    wp_enqueue_style( 'swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css' );
    
    // Custom CSS for the carousel
    wp_enqueue_style( 'cpt-carousel-style', plugins_url( '/css/carousel-style.css', __FILE__ ), array(), null );

    // Swiper JS
    wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true );

    // Custom JS for the carousel
    wp_enqueue_script( 'cpt-carousel-script', plugins_url( '/js/carousel-script.js', __FILE__ ), array( 'swiper-js' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'cpt_carousel_enqueue_scripts' );


function cpt_carousel_shortcode() {
    $args = array(
        'post_type'      => 'carousel_item',
        'posts_per_page' => -1,
    );
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        ob_start();
        ?>
        <div class="carousel-container">
            <div class="swiper-container-cpt">
                <div class="swiper-wrapper">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <div class="swiper-slide" data-id="<?php echo get_the_ID() ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="carousel-image">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </div>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <div class="swiper-content" style="display:none;"><?php the_content(); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="carousel-description">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="swiper-content" data-id="<?php echo get_the_ID() ?>" style="display:none;"><?php the_content(); ?></div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
add_shortcode( 'cpt_carousel', 'cpt_carousel_shortcode' );
