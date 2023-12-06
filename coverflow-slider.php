<?php
/**
 * Plugin Name: Swiper Slider Posts 3D-Coverflow Slider
 * Description: A WordPress plugin for creating a coverflow slider for posts. [latest_posts_slider], [latest_posts_slider posts_per_page="5" category="your_category_slug"]
 
 * Version: 1.0
 * Author: Hassan Naqvi
 */

// Enqueue styles
function posts_coverflow_slider_enqueue_styles() {
    // Enqueue Meyer Reset CSS
    wp_enqueue_style('meyer-reset', 'https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css');

    // Enqueue Swiper CSS
    wp_enqueue_style('swiperr', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css', array(), '4.0.7');

    // Enqueue your custom styles
    wp_enqueue_style('posts-coverflow-slider', plugin_dir_url(__FILE__) . 'style.css', array('swiper'), null);
}

add_action('wp_enqueue_scripts', 'posts_coverflow_slider_enqueue_styles');

// Enqueue scripts
function posts_coverflow_slider_enqueue_scripts() {
    // Enqueue jQuery (make sure it's already registered in your theme)
    wp_enqueue_script('jquery');

    // Enqueue Swiper JS
    wp_enqueue_script('swiper', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js', array('jquery'), '4.4.1', true);

    // Enqueue your custom script
    wp_enqueue_script('posts-coverflow-slider', plugin_dir_url(__FILE__) . 'script.js', array('swiper'), null, true);
}

add_action('wp_enqueue_scripts', 'posts_coverflow_slider_enqueue_scripts');



// Function to get latest WordPress posts and generate Swiper slider
function get_latest_posts_swiper_slider($atts) {
    // Attributes
    $atts = shortcode_atts(
        array(
            'posts_per_page' => -1, // Number of posts to display
            'category'       => '', // Category slug to include
            'fallback_image' => plugin_dir_url(__FILE__) . 'fallback-image.jpg', // Update the path to the fallback image
        ),
        $atts,
        'latest_posts_slider'
    );

    // Query latest posts
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'category_name'  => $atts['category'], // Include specified category
    );

    $latest_posts = new WP_Query($args);

    // Output Swiper slider HTML
    $output = '<section class="swiper-container loading">';
    $output .= '<div class="swiper-wrapper">';

    if ($latest_posts->have_posts()) {
        while ($latest_posts->have_posts()) {
            $latest_posts->the_post();

            // Unique ID for each swiper-slide
            $unique_id = 'swiper-slide-' . get_the_ID();

            $featured_image_url = get_the_post_thumbnail_url();

            // Use fallback image if featured image is not available
            if (empty($featured_image_url)) {
                $featured_image_url = $atts['fallback_image'];
            }

            $output .= '<div id="' . esc_attr($unique_id) . '" class="swiper-slide" style="background-image:url(' . esc_url($featured_image_url) . ')">';
            $output .= '<img src="' . esc_url($featured_image_url) . '" class="entity-img" />';
            $output .= '<div class="content">';
            
            // Display the first category
            $categories = get_the_category();
            if (!empty($categories)) {
                $output .= '<span class="category">' . esc_html($categories[0]->name) . '</span>';
            }

            // Display the post date
            $output .= '<span class="post-date">' . get_the_date() . '</span>';

            $output .= '<p class="title" data-swiper-parallax="-30%" data-swiper-parallax-scale=".7"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></p>';
            $output .= '<span class="caption" data-swiper-parallax="-20%">' . wp_trim_words(get_the_excerpt(), 20) . '</span>';

            // Add Read More link
            $output .= '<a class="read-more" href="' . esc_url(get_permalink()) . '">Read More</a>';

            $output .= '</div></div>';
        }
    }

    $output .= '</div>';
    $output .= '<div class="swiper-pagination"></div>';
    $output .= '<div class="swiper-button-prev swiper-button-white"></div>';
    $output .= '<div class="swiper-button-next swiper-button-white"></div>';
    $output .= '</section>';

    // Reset post data
    wp_reset_postdata();

    return $output;
}

// Register shortcode
add_shortcode('latest_posts_slider', 'get_latest_posts_swiper_slider');

// ... (your existing code)






// Function to display the settings page content
function coverflow_post_slider_settings_page() {
    ?>
    <div class="wrap">
        <h1>Coverflow Post Slider Shortcodes</h1>
        <p>Welcome to the Coverflow Post Slider plugin settings page.</p>
        <h2>How to Use Shortcode</h2>
        <p>Here are a couple of shortcode examples for the modified coverflow_post_slider function:</p>

        <p>Display all posts with the default settings:</p>
        <pre>[latest_posts_slider]</pre>

        <p>Display 3 latest posts from the "electronics" category:</p>
        <pre>[latest_posts_slider category="electronics" posts_per_page="3"]</pre>

        <p>Display 8 latest posts with no specific category:</p>
        <pre>[latest_posts_slider posts_per_page="8"]</pre>

        <p>Feel free to customize the shortcode attributes based on your specific needs. Adjust the category attribute to specify a particular post category, and use the posts_per_page attribute to control the number of posts displayed.</p>
    </div>
    <?php
}

// Function to add the settings page to the admin menu
function coverflow_post_slider_add_menu() {
    add_options_page('Coverflow Post Slider Settings', 'Coverflow Post Slider', 'manage_options', 'coverflow-post-slider-settings', 'coverflow_post_slider_settings_page');
}

// Hook to add the settings page
add_action('admin_menu', 'coverflow_post_slider_add_menu');

