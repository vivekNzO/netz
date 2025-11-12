<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('child_theme_configurator_css')):

    function child_theme_configurator_css()
    {
        $random_number = rand();
        wp_enqueue_style('chld_thm_cfg_separate', trailingslashit(get_stylesheet_directory_uri()) . 'ctc-style.css?nocache=' . $random_number, array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer'));

        wp_enqueue_style('woo_custom_style', trailingslashit(get_stylesheet_directory_uri()) . 'woocommerce/woo-style.css?nocache=' . $random_number, array('woocommerce-general'));
        // owl carousel 
        wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '2.3.4');
        wp_enqueue_style('owl-theme-default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', array(), '2.3.4');
        wp_enqueue_script('jquery');
        wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '2.3.4', true);
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 20);

function enqueue_custom_scripts()
{
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Enqueue the ajax file 
// function enqueue_ajax_scripts()
// {
//     wp_enqueue_script('custom-ajax', get_stylesheet_directory_uri() . '/js/custom-ajax.js?v=' . time(), ['jquery'], null, true);
//     wp_localize_script('custom-ajax', 'ajax_params', [
//         'ajax_url' => admin_url('admin-ajax.php'),
//     ]);
// }
// add_action('wp_enqueue_scripts', 'enqueue_ajax_scripts');


// remove wp version number from scripts and styles
function remove_css_js_version($src)
{
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'remove_css_js_version', 9999);
add_filter('script_loader_src', 'remove_css_js_version', 9999);


// remove wp version number from head and rss
function artisansweb_remove_version()
{
    return '';
}
add_filter('the_generator', 'artisansweb_remove_version');


// Redirect  404 Page to Home page 

add_action('template_redirect', 'redirecting_404_to_home');

function redirecting_404_to_home()
{
    if (is_404()) {
        wp_safe_redirect(site_url());
        exit();
    }
};

// Redirect  search Page to Home page

// function redirect_search_to_home() {
//     if (is_search()) {
//         wp_redirect(home_url());
//         exit();
//     }
// }
// add_action('template_redirect', 'redirect_search_to_home');

// Add Body Class 
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter('body_class', 'add_slug_body_class');

// END ENQUEUE PARENT ACTION
function custom_theme_widgets_init()
{
    register_sidebar(array(
        'name' => 'Sidebar Widget Area',
        'id' => 'sidebar-1',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'custom_theme_widgets_init');

//Fetch subcategory ,brands with ajax in category page

function display_products_with_filters_shortcode()
{
    ob_start();
    $current_cat = get_queried_object();
    $current_cat_id = isset($current_cat->term_id) ? $current_cat->term_id : 0;
    $current_cat_name = isset($current_cat->name) ? esc_html($current_cat->name) : '';
    $current_cat_description = isset($current_cat->description) ? esc_html($current_cat->description) : '';

?>
    <div class="overlay" id="overlay" style="display:none;"></div>
    <div class="filter-wrapper" id="filter-wr" style="opacity:0;">
        <div class="filter-clear-container">
            <div class="filter-clear-icon" id="Apply-btn" onclick="popupclose();"><svg width="20" height="20"
                    viewBox="0 0 24 24" fill="red" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6L18 18M6 18L18 6" stroke="#d11241" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg></div>
            <div class="filter-text">Filter By</div>
            <button class="show-all-products" id="clear-all-filter" data-id="<?php echo esc_attr($current_cat_id); ?>">Clear
                All</button>


        </div>


        <!-- Subcategories -->
        <?php
        $subcategories = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => $current_cat_id,
            'hide_empty' => true,
        ));

        if (!empty($subcategories)):
        ?>
            <!-- Subcategories -->
            <div class="subcategory-list">
                <h2 class="filterby">Filter By</h2>
                <h2 class="accordion">category</h2>
                <div class="panel">
                    <?php
                    foreach ($subcategories as $subcategory) {
                        echo '<label>
                    <input type="checkbox" class="subcategory-checkbox" data-id="' . esc_attr($subcategory->term_id) . '">
                    <span class="subcategory-name">' . esc_html($subcategory->name) . '</span>
                </label><br>';
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>


<div class="prize-filtersec">Prize</div>
        <!-- Price Filter -->
        <div class="price-filter">
            <h3 class="accordion"> Price</h3>
            <div class="panel">
                <?php
                ob_start();
                the_widget('WC_Widget_Price_Filter');
                echo ob_get_clean();
                ?>
            </div>
        </div>

        <!-- Brands (Now using Checkboxes) -->
        <div class="brand-list">
            <h2 class="accordion">Brands</h2>
            <div class="panel">
                <?php
                $brands = get_terms(array(
                    'taxonomy' => 'product_brand',
                    'hide_empty' => true,
                ));

                if (!empty($brands)) {
                    foreach ($brands as $brand) {
                        echo '<label>
                    <input type="checkbox" class="brand-checkbox" data-id="' . esc_attr($brand->term_id) . '"> 
                    <span class="brand-name">' . esc_html($brand->name) . '</span>
                  </label><br>';
                    }
                }
                ?>



            </div>
        </div>
        <div class="clear-res"> <button class="show-all-products" id="clear-all-filter"
                data-id="<?php echo esc_attr($current_cat_id); ?>">Clear All</button></div>


        <button id="Apply-btn" onclick="popupclose();">Apply Filter</button>
    </div>

    <div id="subcategory-products">
        <div class="category-info">
            <h1><?php echo $current_cat_name; ?></h1>
            <p><?php echo $current_cat_description; ?></p>
        </div>
        <div class="filter-container">
            <label for="product-sort">Sort by</label>
            <select id="product-sort">
                <option value="default">Default</option>
                <option value="price_low_high">Price: Low to High</option>
                <option value="price_high_low">Price: High to Low</option>
                <option value="date_newest">Newest First</option>
                <option value="product_name">Product Name</option>
            </select>

        </div>
        <div class="product-list"></div>
        <div class="loading-overlay">
            <div class="spinner"></div>
        </div>
        <div class="pagination"></div>
    </div>
    <div class="responsive-filter">
        <button id="filter-btn" onclick="popupOpen();">
            Filter <span id="filter-sign" style="display: none;">✔</span>
        </button>

    </div>



<?php

    return ob_get_clean();
}
add_shortcode('display_products_with_filters', 'display_products_with_filters_shortcode');





// AJAX function to load products based on subcategory selection
add_action('wp_ajax_load_filtered_products', 'load_filtered_products');
add_action('wp_ajax_nopriv_load_filtered_products', 'load_filtered_products');
function load_filtered_products()
{
    // Get and sanitize user inputs
    $category_ids = isset($_POST['category_ids']) ? array_map('intval', $_POST['category_ids']) : [];
    $brand_ids = isset($_POST['brand_ids']) ? array_map('intval', $_POST['brand_ids']) : [];
    $sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']) : 'default';
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 9999;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // Setup arguments for WP_Query
    $args = [
        'post_type' => 'product',
          'post_status'    => 'publish', 

        'posts_per_page' => 15,
        'paged' => $paged,
        'tax_query' => [],
         'meta_query' => [
    'relation' => 'AND',
    [
        'key' => '_price',
        'value' => [$min_price, $max_price],
        'type' => 'NUMERIC',
        'compare' => 'BETWEEN',
    ],
    [
        'key'     => '_stock_status',
        'value'   => 'instock',
        'compare' => '=',
    ],
        ],
    ];

    if (!empty($category_ids)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $category_ids,
            'operator' => 'IN',
        ];
    }
    // Filter by brand if any brands are selected
    if (!empty($brand_ids)) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_brand',
            'field' => 'term_id',
            'terms' => $brand_ids,
            'operator' => 'IN',
        ];
    }

    // Sorting products based on user selection
    switch ($sort_by) {
        case 'price_low_high':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price_high_low':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'date_newest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'product_name':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        default:
            $args['orderby'] = 'menu_order';
            $args['order'] = 'ASC';
            break;
    }

    // Run the query
    $products = new WP_Query($args);
    $total_products = $products->found_posts;
    $per_page = 15;
    $start = ($paged - 1) * $per_page + 1;
    $end = min($paged * $per_page, $total_products);

    // Display products
    if ($products->have_posts()) {
        echo '<p class="total-products">Showing ' . $start . '-' . $end . ' of ' . $total_products . ' products</p>';
        echo '<div class="products-wrapper">';

        while ($products->have_posts()) {
            $products->the_post();
            global $product;

            // Make sure $product is a valid WooCommerce product object
            if (! $product) {
                $product = wc_get_product(get_the_ID());
            }

           $stock_class = $product && ! $product->is_in_stock() ? ' outofstock' : ' instock';
            echo '<div class="product-item' . $stock_class . '" style="padding: 10px;">';

            // Add banner for out-of-stock products
            if (!$product->is_in_stock()) {
                echo '<span class="out-of-stock-banner">Out of Stock</span>';
            }


            // Product link and thumbnail (using WooCommerce method)
            echo '<div class="product-image">';
            echo '<a href="' . esc_url(get_permalink($product->get_id())) . '">';
            echo $product->get_image(); // safer than woocommerce_get_product_thumbnail()

            echo '</div>';
            echo '<div class="product-details">';
            echo '<h4>' . esc_html(get_the_title()) . '</h4>';
            echo '</a>';


            // Price

            echo '<p>' . wc_price($product->get_price()) . '</p>';

            // ========== Check for tiered pricing using plugin API ==========

            $product_id = $product->get_id();

            $pricing_rule = \TierPricingTable\PriceManager::getPricingRule($product_id);


            $tier_rules = $pricing_rule ? $pricing_rule->getRules() : null;


            if (is_array($tier_rules) && count($tier_rules) > 0) {
                do_action('woocommerce_after_shop_loop_item_title');
            }





            echo '</div>';
            // ==== Rating + Compare button (always show) ====
            // ==== Compare (only compare button here) ====
            echo '<div class="custom-rating-compare-outer">';
            echo '<div class="custom-rating-compare">';
            echo do_shortcode('[th_compare pid="' . esc_attr($product->get_id()) . '"]');
            echo '</div>';

            // ==== Rating (before Add to Cart) ====
            echo '<div class="custom-rating">';
            echo wp_kses_post(wc_get_rating_html($product->get_average_rating()));
            echo '</div>';
            echo '</div>';



            // Add to Cart button
            echo '<a href="' . esc_url($product->add_to_cart_url()) . '" 
            class="button add_to_cart_button ajax_add_to_cart" 
            data-product_id="' . esc_attr($product->get_id()) . '" 
            data-product_sku="' . esc_attr($product->get_sku()) . '" 
            aria-label="Add “' . esc_attr(get_the_title()) . '” to your cart" 
            rel="nofollow">
            Add to Cart
          </a>';



            echo '</div>';
        }




        echo '</div>';

        // Pagination
        $total_pages = $products->max_num_pages;

        // Check if the total number of pages is greater than 1
        if ($total_pages > 1) {
            echo '<div class="pagination">';

            // Previous button (always show, disable if on first page)
            // Previous button
            $prev_disabled = ($paged <= 1) ? 'disabled class="prev-page disabled"' : 'class="prev-page"';
            $prev_data_page = ($paged > 1) ? $paged - 1 : 1;
            echo '<button ' . $prev_disabled . ' data-page="' . $prev_data_page . '"></button>';

            // Always show first page
            echo '<button class="page-number' . ($paged == 1 ? ' active' : '') . '" data-page="1">1</button>';

            // Show dots if necessary before the current page
            if ($paged > 3) {
                echo '<span class="dots">...</span>';
            }

            // Show previous page (when moving back)
            if ($paged > 2) {
                echo '<button class="page-number" data-page="' . ($paged - 1) . '">' . ($paged - 1) . '</button>';
            }

            // Show current page (if not first or last)
            if ($paged > 1 && $paged < $total_pages) {
                echo '<button class="page-number active" data-page="' . $paged . '">' . $paged . '</button>';
            }

            // Show next page (when moving forward)
            if ($paged + 1 < $total_pages) {
                echo '<button class="page-number" data-page="' . ($paged + 1) . '">' . ($paged + 1) . '</button>';
            }

            // Show dots before the last page if there's a gap
            if ($paged + 2 < $total_pages) {
                echo '<span class="dots">...</span>';
            }

            // Always show last page
            if ($total_pages > 1) {
                echo '<button class="page-number' . ($paged == $total_pages ? ' active' : '') . '" data-page="' . $total_pages . '">' . $total_pages . '</button>';
            }

            // Next button (always show, disable if on last page)
            $next_disabled = ($paged >= $total_pages) ? 'disabled class="next-page disabled"' : 'class="next-page"';
            $next_data_page = ($paged < $total_pages) ? $paged + 1 : $total_pages;
            echo '<button ' . $next_disabled . ' data-page="' . $next_data_page . '"></button>';

            echo '</div>';
        }

        // Close pagination div
    } else {
        echo '<p>No products found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}




// ✅ Enqueue AJAX Scripts
function enqueue_custom_ajax_script()
{
    // Enqueue subcategory products script with cache busting
    $random_number = rand();
    $subcategory_script_version = file_exists(get_stylesheet_directory() . '/js/custom-ajax.js') ? filemtime(get_stylesheet_directory() . '/js/custom-ajax.js') : null;
    wp_enqueue_script('subcategory-products', get_stylesheet_directory_uri() . '/js/custom-ajax.js?nocache=' . $random_number, array('jquery'), $subcategory_script_version, true);

    // Localize script for AJAX
    wp_localize_script('subcategory-products', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_ajax_script');

// function enable_ajax_price_filtering()
// {
//     wp_enqueue_script('wc-price-filter', WC()->plugin_url() . '/assets/js/frontend/price-slider.min.js', array('jquery', 'jquery-ui-slider'), WC_VERSION, true);
// }
// add_action('wp_enqueue_scripts', 'enable_ajax_price_filtering');
function load_wc_price_slider_assets()
{
    if (is_product_category()) {
        // Load the price slider JS
        wp_enqueue_script(
            'wc-price-slider',
            WC()->plugin_url() . '/assets/js/frontend/price-slider.min.js',
            array('jquery', 'jquery-ui-slider'),
            WC_VERSION,
            true
        );

        // Load the jQuery UI CSS for slider styling
        wp_enqueue_style(
            'jquery-ui-slider-style',
            'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
        );
    }
}
add_action('wp_enqueue_scripts', 'load_wc_price_slider_assets');


/*
function custom_quantity_buttons()
{
    ?>
    <div class="quantity-adjustment quantity">
        <button type="button" class="qty-adjust qty-minus">-</button>
        <input type="number" id="custom_quantity" name="quantity" value="1" min="1" step="1">
        <button type="button" class="qty-adjust qty-plus">+</button>
    </div>
    <?php
}
add_action('woocommerce_before_add_to_cart_quantity', 'custom_quantity_buttons');
*/



// Discount functionality on cart page///
// add_action('woocommerce_cart_calculate_fees', 'custom_cart_total_discount', 20, 1);

// function custom_cart_total_discount($cart)
// {
//     if (is_admin() && !defined('DOING_AJAX'))
//         return;

//     $subtotal = $cart->get_subtotal();
//     $discount = 0;
//     $label = '';

//     if ($subtotal <= 249) {
//         $discount = $subtotal * 0.05;
//         $label = '5% Cart Discount';
//     } elseif ($subtotal <= 499) {
//         $discount = $subtotal * 0.10;
//         $label = '10% Cart Discount';
//     } elseif ($subtotal <= 1499) {
//         $discount = $subtotal * 0.15;
//         $label = '15% Cart Discount';
//     } elseif ($subtotal <= 3999) {
//         $discount = $subtotal * 0.20;
//         $label = '20% Cart Discount';
//     } elseif ($subtotal >= 4000) {
//         $discount = $subtotal * 0.25;
//         $label = '25% Cart Discount';
//     }

//     if ($discount > 0) {
//         $cart->add_fee($label, -$discount);
//     }
// }



// // --- WordPress DB credentials (from wp-config.php) ---
// $wp_host = 'localhost';
// $wp_db = 'dbxivfgjhebdm7';
// $wp_user = 'ut8fs4accegqk';
// $wp_pass = 'gbp1lhyryslb';

// // --- Magento DB credentials ---
// $mg_host = 'localhost';
// $mg_db = 'dbistx9eq8oqhr';
// $mg_user = 'uqsencnqfvays';
// $mg_pass = 'x4qugbkdwbvv';

// // Create WordPress DB connection
// $wp_conn = new mysqli($wp_host, $wp_user, $wp_pass, $wp_db);
// if ($wp_conn->connect_error) {
//     die("WordPress DB connection failed: " . $wp_conn->connect_error);
// }

// // Create Magento DB connection
// $mg_conn = new mysqli($mg_host, $mg_user, $mg_pass, $mg_db);
// if ($mg_conn->connect_error) {
//     die("Magento DB connection failed: " . $mg_conn->connect_error);
// }

// // Step 1: Get url_key attribute_id from Magento
// $attr_sql = "
//     SELECT attribute_id 
//     FROM eav_attribute 
//     WHERE attribute_code = 'url_key' 
//       AND entity_type_id = (
//         SELECT entity_type_id 
//         FROM eav_entity_type 
//         WHERE entity_type_code = 'catalog_product'
//     ) LIMIT 1
// ";
// $attr_result = $mg_conn->query($attr_sql);
// if (!$attr_result || $attr_result->num_rows == 0) {
//     die("Failed to fetch url_key attribute_id.");
// }
// $attr_row = $attr_result->fetch_assoc();
// $url_key_attr_id = $attr_row['attribute_id'];

// // Step 2: Fetch all Magento SKUs + url_keys
// $product_sql = "
//     SELECT cpe.sku, cpev.value AS url_key
//     FROM catalog_product_entity AS cpe
//     JOIN catalog_product_entity_varchar AS cpev
//       ON cpev.entity_id = cpe.entity_id
//     WHERE cpev.attribute_id = $url_key_attr_id
// ";
// $product_result = $mg_conn->query($product_sql);
// if (!$product_result) {
//     die("Magento product fetch failed.");
// }

// $updated = 0;

// // Step 3: Loop through Magento products
// while ($row = $product_result->fetch_assoc()) {
//     $sku = $wp_conn->real_escape_string($row['sku']);
//     $url_key = $wp_conn->real_escape_string($row['url_key']);

//     // Find matching post_id in WordPress
//     $meta_sql = "
//         SELECT post_id 
//         FROM vii_postmeta 
//         WHERE meta_key = '_sku' AND meta_value = '$sku'
//         LIMIT 1
//     ";
//     $meta_result = $wp_conn->query($meta_sql);
//     if ($meta_result && $meta_result->num_rows > 0) {
//         $meta_row = $meta_result->fetch_assoc();
//         $post_id = $meta_row['post_id'];

//         // Update post_name in vii_posts
//         $update_sql = "
//             UPDATE vii_posts 
//             SET post_name = '$url_key' 
//             WHERE ID = $post_id AND post_type = 'product'
//         ";
//         $wp_conn->query($update_sql);
//         $updated++;
//     }
// }



// // Close connections
// $wp_conn->close();
// $mg_conn->close();


// add_filter( 'woocommerce_shop_order_search_fields', 'custom_admin_product_search_sku' );
// add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'custom_admin_product_query_sku', 10, 2 );

// function custom_admin_product_search_sku( $search_fields ) {
//     $search_fields[] = '_sku';
//     return $search_fields;
// }

// function custom_admin_product_query_sku( $query, $query_vars ) {
//     if ( ! empty( $query_vars['search'] ) ) {
//         global $wpdb;
//         $sku = $query_vars['search'];
//         $product_ids = $wpdb->get_col(
//             $wpdb->prepare("
//                 SELECT post_id
//                 FROM {$wpdb->prefix}postmeta
//                 WHERE meta_key = '_sku' AND meta_value LIKE %s
//             ", '%' . $wpdb->esc_like( $sku ) . '%')
//         );
//         if ( ! empty( $product_ids ) ) {
//             $query['post__in'] = array_merge( $product_ids, $query['post__in'] ?? [] );
//         }
//     }
//     return $query;
// }

// function update_all_product_images_alt_to_title_cleaned() {
//     $products = get_posts([
//         'post_type' => 'product',
//         'posts_per_page' => -1,
//         'fields' => 'ids',
//     ]);

//     foreach ($products as $product_id) {
//         // Get featured image ID
//         $thumbnail_id = get_post_meta($product_id, '_thumbnail_id', true);

//         // Get gallery image IDs
//         $gallery_ids_string = get_post_meta($product_id, '_product_image_gallery', true);
//         $gallery_ids = $gallery_ids_string ? explode(',', $gallery_ids_string) : [];

//         // Combine and clean
//         $image_ids = array_unique(array_filter(array_merge([$thumbnail_id], $gallery_ids)));

//         foreach ($image_ids as $image_id) {
//             $title = get_the_title($image_id);

//             if ($title) {
//                 // Remove file extension (.png, .jpg, .jpeg, .webp, etc.)
//                 $cleaned_title = preg_replace('/\.(png|jpg|jpeg|webp|gif)$/i', '', $title);

//                 update_post_meta($image_id, '_wp_attachment_image_alt', $cleaned_title);
//             }
//         }
//     }

//     echo "✅ ALT texts updated (file extensions removed) for all product images.";
// }

// // Run it once
// update_all_product_images_alt_to_title_cleaned();

// add_action('admin_init', 'count_images_with_alt_text');

// function count_images_with_alt_text() {
//     if (!current_user_can('manage_options')) return; // Only for admins

//     if (isset($_GET['check_alt_count']) && $_GET['check_alt_count'] == '1') {
//         global $wpdb;

//         $alt_count = $wpdb->get_var("
//             SELECT COUNT(*) 
//             FROM {$wpdb->postmeta}
//             WHERE meta_key = '_wp_attachment_image_alt'
//               AND meta_value != ''
//         ");

//         echo "<div style='padding:20px; font-size:16px; background:#fff3cd; color:#856404;'>
//             ✅ Total images with ALT text: <strong>{$alt_count}</strong>
//         </div>";
//     }
// }




// Add Product Category Dropdown Filter in the Sidebar on Shop Page



/// AJAX Filter for Products (with multiple categories)
function ajax_filter_products()
{
    // Collect Filter Data
    $categories = isset($_POST['categories']) ? $_POST['categories'] : '';

    // Set up arguments for WP_Query
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1, // Show all matching products
    );

    if (!empty($categories)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $categories,
                'operator' => 'IN',  // Matches any of the selected categories
            ),
        );
    }

    // Run the Query
    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()):
            $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
    else:
        echo 'No products found.';
    endif;

    die();
}
add_action('wp_ajax_filter_products', 'ajax_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'ajax_filter_products');

// Enqueue Scripts for AJAX Handling
function enqueue_ajax_filter_scripts()
{
    if (is_shop() || is_product_category()) {
        wp_enqueue_script('ajax-filter', get_template_directory_uri() . '/js/ajax-filter.js', array('jquery'), null, true);
        wp_localize_script('ajax-filter', 'ajaxfilter', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_filter_scripts');

// Add Custom JS for AJAX Filtering
function add_ajax_filter_script()
{
?>
    <script type="text/javascript">
        jQuery(function($) {
            // Trigger the filter when a category checkbox is clicked
            $('input[name="product_category_filter[]"]').change(function() {
                var selectedCategories = [];

                // Gather all selected categories
                $('input[name="product_category_filter[]"]:checked').each(function() {
                    selectedCategories.push($(this).val());
                });

                // Only trigger AJAX if there are any selected categories
                if (selectedCategories.length > 0) {
                    var data = {
                        action: 'filter_products',
                        categories: selectedCategories,
                    };

                    $.post(ajaxfilter.ajaxurl, data, function(response) {
                        // Update the products on the page
                        $('.products').html(response);
                    });
                } else {
                    // If no category is selected, display all products
                    var data = {
                        action: 'filter_products',
                        categories: [],
                    };

                    $.post(ajaxfilter.ajaxurl, data, function(response) {
                        // Update the products on the page
                        $('.products').html(response);
                    });
                }
            });
        });
        jQuery(document).ready(function($) {
            // Load all products on page load
            load_products('');

            // Category checkbox change
            $(document).on('change', '.category-btn', function() {
                load_products('');
            });

            function load_products() {
                page = 1;
                $('#product-list').html('<div class="loading-overlay"><div class="spinner"></div></div>');

                // Collect checked category IDs
                let selectedCategories = [];
                $('.category-btn:checked').each(function() {
                    selectedCategories.push($(this).data('id'));
                });

                // Send first selected category or empty string
                let category_id = selectedCategories.length > 0 ? selectedCategories[0] : '';

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'load_filtered_products_by_category',
                        category_id: category_id,
                        page: page
                    },
                    success: function(response) {
                        $('#product-list').html(response);
                    }
                });
            }

            // custom compare ajax 

            let comparebuttons = document.querySelectorAll(".shop-compare");
            comparebuttons.forEach(comparebutton => {
                comparebutton?.addEventListener('click', function(e) {
                    e.preventDefault();
                    let productId = this.getAttribute("data-product_id");
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'th_get_compare_product',
                            product_id: productId,
                            add_remove: add
                        },
                        success: function(response) {
                            // console.log("response->", response);
                            // return;
                            if (thisBtn) {
                                thisBtn.removeClass("loading");
                                if (!thisBtn.hasClass("th-added-compare")) {
                                    thisBtn.addClass("th-added-compare");
                                }
                            }
                            if (response.no_product == "1") {
                                let getWrap = $(".thcompare-open-by-popup");

                                getWrap.find(">div").addClass("th-compare-output-wrap-close");
                                setTimeout(() => {
                                    getWrap.remove();
                                    // footer also remove and add more
                                    $(".th-compare-footer-wrap").remove();
                                    $(".th-add-more-product-container").remove();
                                }, 500);
                                $("body").removeClass("th_product_Compare_body_Class");
                            } else {
                                $(".thcompare-open-by-popup .th-compare-output-product").html(
                                    response.html
                                );
                                // footer bar ----------------------
                                if (response.footer_bar && response.footer_bar != "") {
                                    let footer_bar = $(".th-compare-footer-wrap");
                                    if (footer_bar.length) {
                                        footer_bar.remove();
                                    }
                                    $("body").append(response.footer_bar);
                                    $(".th-compare-footer-wrap > div").append(thCompare.loaderIcon());
                                    if (parseInt(ids)) {
                                        setTimeout(() => {
                                            $(".th-compare-footer-wrap").addClass("active");
                                        }, 500);
                                    }
                                }
                                // footer bar ----------------------
                                // error show in heading
                                if (response.product_limit) {
                                    $(".th-compare-heading .error_").html(response.product_limit);
                                }
                                thCompare.containerScroll();
                                $(".thcompare-open-by-popup .th-compare-output-wrap").removeClass(
                                    "th-loading"
                                );
                            }
                        },
                    });
                })
            });
        });
    </script>
<?php
}
add_action('wp_footer', 'add_ajax_filter_script', 20);








// Shortcode to display all products initially and then filter by category on click
function display_all_products_with_category_filter_shortcode()
{
    ob_start();
    $current_cat = get_queried_object();
    $current_cat_description = isset($current_cat->description) ? esc_html($current_cat->description) : '';

    // Get Parent Categories
    $parent_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'parent' => 0,
        'hide_empty' => true,
    ));
?>

    <!-- Overlay -->
    <div class="overlay" id="overlay" style="display:none;"></div>

    <!-- Filter Wrapper (Popup on Mobile) -->
    <div class="filter-wrapper" id="filter-wr" style="display: none;">
        <div class="filter-clear-container">
            <div class="filter-clear-icon" onclick="popupclose();">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="red" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6L18 18M6 18L18 6" stroke="#d11241" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <div class="filter-text">Filter By</div>
            <button class="show-all-products" id="clear-all-filter">Clear All</button>
        </div>

        <!-- Parent Categories Filter -->
        <div class="subcategory-list">
            <h2 class="accordion">Category</h2>
            <div class="panel">
                <?php
                if (!empty($parent_categories)) {
                    foreach ($parent_categories as $parent_cat) {
                        echo '<label>
                            <input type="checkbox" class="category-btn" data-id="' . esc_attr($parent_cat->term_id) . '"> 
                            <span>' . esc_html($parent_cat->name) . '</span>
                          </label><br>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- Apply Button -->
        <div class="clear-res">
            <button class="show-all-products" id="clear-all-filter">Clear All</button>
        </div>
        <button id="Apply-btn" onclick="popupclose();">Apply Filter</button>
    </div>

    <!-- Main Layout -->
    <div class="shop-page-container">
        <!-- Sidebar (Desktop) -->
        <div class="shop-sidebar">
            <div class="widget woocommerce widget_product_categories">
                <h2 class="filterby">Filter By Category</h2>
                <ul class="product-categories">
                    <?php
                    if (!empty($parent_categories)) {
                        foreach ($parent_categories as $parent_cat) {
                            // Skip category with ID 22
                            if ($parent_cat->term_id == 22) {
                                continue;
                            }

                            $cat_id = 'category-' . esc_attr($parent_cat->term_id);
                            echo '<li class="cat-item">
                            <div class="form-group">
                                <input type="checkbox" id="' . $cat_id . '" class="subcategory-checkbox" data-id="' . esc_attr($parent_cat->term_id) . '">
                                <label for="' . $cat_id . '">' . esc_html($parent_cat->name) . '</label>
                            </div>
                          </li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="shop-content">
            <div class="category-info">
                <h1>Shop</h1>
                <p><?php echo $current_cat_description; ?></p>
            </div>

            <div class="filter-container">
                <label for="product-sort">Sort by</label>
                <select id="product-sort">
                    <option value="default">Default</option>
                    <option value="price_low_high">Price: Low to High</option>
                    <option value="price_high_low">Price: High to Low</option>
                    <option value="date_newest">Newest First</option>
                    <option value="product_name">Product Name</option>
                </select>
            </div>

            <div class="product-list" id="product-list"></div>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>

    <!-- Mobile Filter Button -->
    <div class="responsive-filter">
        <button id="filter-btn" onclick="popupOpen();">
            Filter <span id="filter-sign" style="display: none;">✔</span>
        </button>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('display_all_products_with_category_filter', 'display_all_products_with_category_filter_shortcode');



// AJAX handler to load products based on category selection
add_action('wp_ajax_load_filtered_products_by_category', 'load_filtered_products_by_category');
add_action('wp_ajax_nopriv_load_filtered_products_by_category', 'load_filtered_products_by_category');
function load_filtered_products_by_category()
{
    // Get and sanitize user inputs
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Setup arguments for WP_Query
    $args = [
        'post_type' => 'product',
        'post_status'    => 'publish', 
        'posts_per_page' => 15,
        'paged' => $paged,
        'tax_query' => [], 'meta_query' => [
    'relation' => 'AND',
    [
        'key' => '_price',
        'value' => [$min_prices, $max_price],
        'type' => 'NUMERIC',
        'compare' => 'BETWEEN',
    ],
    [
        'key'     => '_stock_status',
        'value'   => 'instock',
        'compare' => '=',
    ],
    ],
        
        
    ];

    // Filter by category if a category is selected
    if ($category_id) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $category_id,
            'operator' => 'IN',
        ];
    }

    // Run the query
    $products = new WP_Query($args);
    $total_products = $products->found_posts;
    $per_page = 15;
    $start = ($paged - 1) * $per_page + 1;
    $end = min($paged * $per_page, $total_products);

    // Display products
    if ($products->have_posts()) {
        echo '<p class="total-products">Showing ' . $start . '-' . $end . ' of ' . $total_products . ' products</p>';
        echo '<div class="products-wrapper">';

        while ($products->have_posts()) {
            $products->the_post();
            global $product;

            // Make sure $product is a valid WooCommerce product object
            if (! $product) {
                $product = wc_get_product(get_the_ID());
            }
            $stock_class = $product && ! $product->is_in_stock() ? ' outofstock' : ' instock';
            echo '<div class="product-item' . $stock_class . '" style="padding: 10px;">';

            // Add banner for out-of-stock products
            if (!$product->is_in_stock()) {
                echo '<span class="out-of-stock-banner">Out of Stock</span>';
            }



            // Product link and thumbnail (using WooCommerce method)
            echo '<div class="product-image">';
            echo '<a href="' . esc_url(get_permalink($product->get_id())) . '">';
            echo $product->get_image(); // safer than woocommerce_get_product_thumbnail()

            echo '</div>';
            echo '<div class="product-details">';
            echo '<h4>' . esc_html(get_the_title()) . '</h4>';
            echo '</a>';


            // Price

            echo '<p>' . wc_price($product->get_price()) . '</p>';

            // ========== Check for tiered pricing using plugin API ==========

            $product_id = $product->get_id();

            $pricing_rule = \TierPricingTable\PriceManager::getPricingRule($product_id);


            $tier_rules = $pricing_rule ? $pricing_rule->getRules() : null;


            if (is_array($tier_rules) && count($tier_rules) > 0) {
                do_action('woocommerce_after_shop_loop_item_title');
            }





            echo '</div>';
            // ==== Rating + Compare button (always show) ====
            // ==== Compare (only compare button here) ====
            echo '<div class="custom-rating-compare-outer">';
            echo '<div class="custom-rating-compare">';
            echo do_shortcode('[th_compare pid="' . esc_attr($product->get_id()) . '"]');
            echo '</div>';

            // ==== Rating (before Add to Cart) ====
            echo '<div class="custom-rating">';
            echo wp_kses_post(wc_get_rating_html($product->get_average_rating()));
            echo '</div>';
            echo '</div>';



            // Add to Cart button
            echo '<a href="' . esc_url($product->add_to_cart_url()) . '" 
            class="button add_to_cart_button ajax_add_to_cart" 
            data-product_id="' . esc_attr($product->get_id()) . '" 
            data-product_sku="' . esc_attr($product->get_sku()) . '" 
            aria-label="Add “' . esc_attr(get_the_title()) . '” to your cart" 
            rel="nofollow">
            Add to Cart
          </a>';
            // echo '<a href="#" data-product_id="' . $product->get_id() . '" class="shop-compare">Compare</a>';
            echo '</div>';
        }

        echo '</div>';



        // Pagination
        $total_pages = $products->max_num_pages;

        // Check if the total number of pages is greater than 1
        if ($total_pages > 1) {
            echo '<div class="pagination">';

            // Previous button
            if ($paged > 1) {
    echo '<button class="prev-page" data-page="' . ($paged - 1) . '"></button>';
}

            // Always show first page
            echo '<button class="page-number" data-page="1">1</button>';

            // Show dots if necessary before the current page
            if ($paged > 3) {
                echo '<span class="dots">...</span>';
            }

            // Show previous page (when moving back)
            if ($paged > 2) {
                echo '<button class="page-number" data-page="' . ($paged - 1) . '">' . ($paged - 1) . '</button>';
            }

            // Show current page (if not first or last)
            if ($paged > 1 && $paged < $total_pages) {
                echo '<button class="page-number active" data-page="' . $paged . '">' . $paged . '</button>';
            }

            // Show next page (when moving forward)
            if ($paged + 1 < $total_pages) {
                echo '<button class="page-number" data-page="' . ($paged + 1) . '">' . ($paged + 1) . '</button>';
            }

            // Show dots before the last page if there's a gap
            if ($paged + 2 < $total_pages) {
                echo '<span class="dots">...</span>';
            }

            // Always show last page
            if ($total_pages > 1) {
                echo '<button class="page-number" data-page="' . $total_pages . '">' . $total_pages . '</button>';
            }

            // Next button
            if ($paged < $total_pages) {
                echo '<button class="next-page" data-page="' . ($paged + 1) . '">
              
              </button>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}

// fetch the stock and quantity//





function custom_stock_quantity_message()
{
    global $post;

    if (!is_a($post, 'WP_Post')) {
        return '<p>Error: No valid post object found.</p>';
    }

    $product = wc_get_product($post->ID);

    if (!$product) {
        return '<p style="color:red;">Error: No product found for this post.</p>';
    }

    ob_start();

    if ($product->is_in_stock()) {
        $stock_qty = $product->get_stock_quantity();

        if ($stock_qty && $stock_qty > 0) {
            echo '<p style="color:#d11241;"><strong>IN STOCK</strong><br>Only ' . $stock_qty . ' left</p>';
        } else {
            echo '<p style="color:#d11241;">In stock</p>';
        }
    } else {
        echo '<p style="color:gray;">Out of stock</p>';
    }

    return ob_get_clean();
}
add_shortcode('custom_stock_quantity', 'custom_stock_quantity_message');

// remove - from page title for seo data//
// add_filter('pre_get_document_title', function () {
//     if (is_singular()) {
//         return single_post_title('', false); // For posts, pages, products
//     } elseif (is_home()) {
//         return get_the_title(get_option('page_for_posts')); // Blog page title
//     } elseif (is_archive()) {
//         return get_the_archive_title(); // Category, tag, etc.
//     } elseif (is_search()) {
//         return 'Search results for: ' . get_search_query();
//     } elseif (is_404()) {
//         return 'Page Not Found';
//     }
//     return ''; // Fallback: empty title
// });

// Add more information tab in single product page//
add_filter('woocommerce_product_tabs', 'add_more_information_tab');
function add_more_information_tab($tabs)
{
    $tabs['more_information'] = array(
        'title' => __('More Information', 'woocommerce'),
        'priority' => 25,
        'callback' => 'custom_more_information_content'
    );
    return $tabs;
}

function custom_more_information_content()
{
    global $product;

    if ($product && $product->get_shipping_class() === 'free-shipping') {
        echo '<h2>More Information</h2>';
        echo '<div class="free-shipping-info">';
        echo '<p class="free-shipping-line">Free Shipping</p>';
        echo '<p class="ground-shipping-line">Free Ground Shipping</p>';
        echo '</div>';
    } else {
        // Optional: you can leave this blank or show default
        echo '<p>No additional information available for this product.</p>';
    }
}


// script for adding free shipping classes to all the products//
// add_action('init', 'assign_free_shipping_to_all_products');
// function assign_free_shipping_to_all_products() {
//     if ( ! current_user_can('manage_woocommerce') ) return;

//     $shipping_class = get_term_by('slug', 'free-shipping', 'product_shipping_class');
//     if ( ! $shipping_class ) return;

//     $args = array(
//         'post_type'      => 'product',
//         'posts_per_page' => -1,
//         'post_status'    => 'publish',
//         'fields'         => 'ids',
//     );
//     $products = get_posts($args);

//     foreach ( $products as $product_id ) {
//         wp_set_object_terms( $product_id, intval($shipping_class->term_id), 'product_shipping_class' );
//     }

//     echo 'Shipping class "Free Shipping" assigned to all products.';
//     exit; // Remove after running once
// }



//=========================== custom reviews ======================================
// Add custom rating fields to review form
add_filter('woocommerce_product_review_comment_form_args', 'add_custom_star_fields_to_review_form', 10);
function add_custom_star_fields_to_review_form($args)
{
    ob_start();

    foreach (
        [
            'price_rating' => 'Price',
            'value_rating' => 'Value',
            'quality_rating' => 'Quality'
        ] as $name => $label
    ) {
    ?>
        <div class="comment-form-rating">
            <label for="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>-label">
                <?php echo esc_html($label); ?> <span class="required">*</span>
            </label>
            <p class="stars">
                <span role="group" aria-labelledby="<?php echo esc_attr($name); ?>-label">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <a class="star-<?php echo $i; ?>" href="#" data-value="<?php echo $i; ?>" role="radio" aria-checked="false"
                            tabindex="0">
                            <?php echo $i; ?> of 5
                        </a>
                    <?php endfor; ?>
                </span>
            </p>
            <select name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" required style="display:none;">
                <option value=""><?php _e('Rate…', 'woocommerce'); ?></option>
                <option value="5"><?php _e('Perfect', 'woocommerce'); ?></option>
                <option value="4"><?php _e('Good', 'woocommerce'); ?></option>
                <option value="3"><?php _e('Average', 'woocommerce'); ?></option>
                <option value="2"><?php _e('Not that bad', 'woocommerce'); ?></option>
                <option value="1"><?php _e('Very poor', 'woocommerce'); ?></option>
            </select>
        </div>
    <?php
    }

    $args['comment_field'] .= ob_get_clean(); // Append custom fields
    return $args;
}

// Prevent validation error on missing Woo rating
add_filter('preprocess_comment', 'bypass_default_rating_validation');
function bypass_default_rating_validation($commentdata)
{
    if (is_product() && isset($_POST['rating']) && $_POST['rating'] === '') {
        unset($_POST['rating']);
    }
    return $commentdata;
}

// Save custom ratings to comment meta
add_action('comment_post', 'save_custom_review_ratings');
function save_custom_review_ratings($comment_id)
{
    foreach (['price_rating', 'value_rating', 'quality_rating'] as $key) {
        if (!empty($_POST[$key])) {
            add_comment_meta($comment_id, $key, intval($_POST[$key]));
        }
    }
}

// Frontend JavaScript for star rating behavior
add_action('wp_footer', 'custom_review_star_script');
function custom_review_star_script()
{
    if (!is_product())
        return;
    ?>
    <script>
        jQuery(function($) {
            ['price_rating', 'value_rating', 'quality_rating'].forEach(function(field) {
                var select = $('#' + field);
                var container = select.closest('.comment-form-rating');
                var stars = container.find('.stars a');

                stars.on('click', function(e) {
                    e.preventDefault();
                    var value = $(this).data('value');
                    select.val(value).trigger('change');
                    stars.removeClass('active');
                    $(this).addClass('active').prevAll().addClass('active');
                });
            });
        });
    </script>
    <style>
        .comment-form-rating .stars a {
            color: #ccc;
            text-decoration: none;
            font-size: 20px;
        }

        .comment-form-rating .stars a.active {
            color: #f90;
        }
    </style>
    <?php
}




// review import 

// Only run if ?script=true is passed
// if (isset($_GET['script']) || $_GET['script'] === 'true') {

//     // Load WordPress environment if running outside theme/plugin
//     if (!defined('ABSPATH')) {
//         require_once(dirname(__FILE__) . '/wp-load.php');
//     }

//     global $wpdb;

//     // Magento DB connection (update credentials)
//     $magentoDb = new mysqli('localhost', 'uqsencnqfvays', '|1yc7l#@3}fk', 'dbistx9eq8oqhr');

//     if ($magentoDb->connect_error) {
//         die('Magento DB connection failed: ' . $magentoDb->connect_error);
//     }

//     // Fetch approved Magento reviews, joined with SKU and rating info
//     $reviewQuery = "
//     SELECT
//         r.review_id,
//         r.entity_pk_value AS magento_product_id,
//         r.created_at,
//         d.nickname,
//         d.detail,
//         cpe.sku,
//         v.rating_id,
//         v.value
//     FROM review r
//     JOIN review_detail d ON r.review_id = d.review_id
//     JOIN rating_option_vote v ON r.review_id = v.review_id
//     JOIN catalog_product_entity cpe ON r.entity_pk_value = cpe.entity_id
//     WHERE r.status_id = 1 -- only approved reviews
//     ORDER BY r.review_id, v.rating_id
// ";

//     $reviewResults = $magentoDb->query($reviewQuery);

//     $reviews = [];
//     while ($row = $reviewResults->fetch_assoc()) {
//         $id = $row['review_id'];
//         if (!isset($reviews[$id])) {
//             $reviews[$id] = [
//                 'sku' => $row['sku'],
//                 'nickname' => $row['nickname'],
//                 'detail' => $row['detail'],
//                 'created_at' => $row['created_at'],
//                 'ratings' => []
//             ];
//         }
//         // Map rating_id to keys
//         switch ($row['rating_id']) {
//             case 1:
//                 $type = 'quality_rating';
//                 break;
//             case 2:
//                 $type = 'value_rating';
//                 break;
//             case 3:
//                 $type = 'price_rating';
//                 break;
//             default:
//                 continue 2;
//         }
//         $reviews[$id]['ratings'][$type] = $row['value'];
//     }

//     // Insert into WordPress
//     foreach ($reviews as $review) {
//         $sku = $review['sku'];

//         // Find WordPress product by SKU
//         $post_id = $wpdb->get_var(
//             $wpdb->prepare(
//                 "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sku' AND meta_value = %s LIMIT 1",
//                 $sku
//             )
//         );

//         if (!$post_id) {
//             echo "❌ SKU not found in WP: $sku<br>";
//             continue;
//         }

//         // Insert comment/review
//         $wpdb->insert(
//             $wpdb->comments,
//             [
//                 'comment_post_ID' => $post_id,
//                 'comment_author' => $review['nickname'],
//                 'comment_content' => $review['detail'],
//                 'comment_type' => 'review',
//                 'comment_approved' => 1,
//                 'comment_date' => $review['created_at'],
//                 'comment_date_gmt' => $review['created_at'],
//             ]
//         );
//         $comment_id = $wpdb->insert_id;

//         // Insert rating metas
//         foreach ($review['ratings'] as $key => $value) {
//             add_comment_meta($comment_id, $key, $value);
//         }

//         echo "✅ Imported review for SKU: $sku<br>";
//     }

//     $magentoDb->close();
//     echo "<br>🎉 Done importing Magento reviews to WooCommerce!";
// } 



// if (isset($_GET['script']) && $_GET['script'] === 'true') {

//     // Load WordPress environment if running standalone
//     if (!defined('ABSPATH')) {
//         require_once(dirname(__FILE__) . '/wp-load.php');
//     }

//     global $wpdb;

//     // Magento DB connection
//     $magentoDb = new mysqli('localhost', 'uqsencnqfvays', '|1yc7l#@3}fk', 'dbistx9eq8oqhr');

//     if ($magentoDb->connect_error) {
//         die('Magento DB connection failed: ' . $magentoDb->connect_error);
//     }

//     // Fetch approved Magento reviews
//     $reviewQuery = "
//         SELECT
//             r.review_id,
//             r.entity_pk_value AS magento_product_id,
//             r.created_at,
//             d.nickname,
//             d.detail,
//             cpe.sku,
//             v.rating_id,
//             v.value
//         FROM review r
//         JOIN review_detail d ON r.review_id = d.review_id
//         JOIN rating_option_vote v ON r.review_id = v.review_id
//         JOIN catalog_product_entity cpe ON r.entity_pk_value = cpe.entity_id
//         WHERE r.status_id = 1
//         ORDER BY r.review_id, v.rating_id
//     ";

//     $reviewResults = $magentoDb->query($reviewQuery);

//     $reviews = [];
//     while ($row = $reviewResults->fetch_assoc()) {
//         $id = $row['review_id'];
//         if (!isset($reviews[$id])) {
//             $reviews[$id] = [
//                 'sku' => $row['sku'],
//                 'nickname' => $row['nickname'],
//                 'detail' => $row['detail'],
//                 'created_at' => $row['created_at'],
//                 'ratings' => []
//             ];
//         }

//         // Map rating_id to keys
//         switch ($row['rating_id']) {
//             case 1:
//                 $type = 'quality_rating';
//                 break;
//             case 2:
//                 $type = 'value_rating';
//                 break;
//             case 3:
//                 $type = 'price_rating';
//                 break;
//             default:
//                 continue 2;
//         }

//         $reviews[$id]['ratings'][$type] = $row['value'];
//     }

//     // Prepare per-product rating totals
//     $productRatingStats = [];

//     foreach ($reviews as $review) {
//         $sku = $review['sku'];

//         // Find WP product ID
//         $post_id = $wpdb->get_var(
//             $wpdb->prepare(
//                 "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sku' AND meta_value = %s LIMIT 1",
//                 $sku
//             )
//         );

//         if (!$post_id) {
//             echo "❌ SKU not found in WP: $sku<br>";
//             continue;
//         }

//         // Insert comment
//         $wpdb->insert(
//             $wpdb->comments,
//             [
//                 'comment_post_ID' => $post_id,
//                 'comment_author' => $review['nickname'],
//                 'comment_content' => $review['detail'],
//                 'comment_type' => 'review',
//                 'comment_approved' => 1,
//                 'comment_date' => $review['created_at'],
//                 'comment_date_gmt' => $review['created_at'],
//             ]
//         );
//         $comment_id = $wpdb->insert_id;

//         // Insert comment metas
//         $rating_sum = 0;
//         $rating_count = 0;

//         foreach ($review['ratings'] as $key => $value) {
//             $val = (float)$value;
//             if ($val > 0) {
//                 $rating_sum += $val;
//                 $rating_count++;
//             }
//             add_comment_meta($comment_id, $key, $val);
//         }

//         // Also save the main "rating" meta that WooCommerce expects
//         if ($rating_count > 0) {
//             $avg_rating = round($rating_sum / $rating_count, 2);
//             add_comment_meta($comment_id, 'rating', $avg_rating);

//             // Update product-level stats
//             if (!isset($productRatingStats[$post_id])) {
//                 $productRatingStats[$post_id] = [
//                     'total' => 0,
//                     'count' => 0
//                 ];
//             }
//             $productRatingStats[$post_id]['total'] += $avg_rating;
//             $productRatingStats[$post_id]['count']++;
//         }

//         echo "✅ Imported review for SKU: $sku<br>";
//     }

//     // Update product-level meta for average rating & count
//     foreach ($productRatingStats as $post_id => $stats) {
//         $avg = round($stats['total'] / $stats['count'], 2);
//         update_post_meta($post_id, '_wc_average_rating', $avg);
//         update_post_meta($post_id, '_wc_review_count', $stats['count']);
//         wc_delete_product_transients($post_id);

//         echo "⭐ Updated product ID $post_id: Average = $avg, Count = {$stats['count']}<br>";
//     }

//     $magentoDb->close();
//     echo "<br>🎉 Done importing Magento reviews and updating product ratings!";
// }





// --- Add Gift Message field on single product page ---
add_action('woocommerce_before_add_to_cart_button', 'wmh_add_gift_message_field');
function wmh_add_gift_message_field()
{
    echo '<div class="product-gift-message" style="margin:10px 0;">
        <label for="gift_message">' . __('Gift Message (optional)', 'wmh') . '</label>
        <textarea id="gift_message" name="gift_message" rows="3" style="width:100%;"></textarea>
    </div>';
}

// --- Save message into cart item when adding to cart ---
add_filter('woocommerce_add_cart_item_data', 'wmh_store_gift_message_cart', 10, 2);
function wmh_store_gift_message_cart($cart_item_data, $product_id)
{
    if (!empty($_POST['gift_message'])) {
        $cart_item_data['gift_message'] = sanitize_textarea_field($_POST['gift_message']);
        // unique key so same product with different messages stay separate
        $cart_item_data['unique_key'] = md5(microtime() . rand());
    }
    return $cart_item_data;
}

// --- Show gift message under product in Cart/Checkout (read-only line) ---
add_filter('woocommerce_get_item_data', 'wmh_display_gift_message_cart', 10, 2);
function wmh_display_gift_message_cart($item_data, $cart_item)
{
    if (!empty($cart_item['gift_message'])) {
        $item_data[] = array(
            'name'  => __('Gift Message', 'wmh'),
            'value' => nl2br(esc_html($cart_item['gift_message']))
        );
    }
    return $item_data;
}

// --- Add editable textarea in Cart for each item ---
add_action('woocommerce_after_cart_item_name', 'wmh_cart_row_gift_input', 10, 2);
function wmh_cart_row_gift_input($cart_item, $cart_item_key)
{
    $value = isset($cart_item['gift_message']) ? $cart_item['gift_message'] : '';
    echo '<div class="cart-gift-message" style="margin-top:6px;">
        <label>' . __('Gift Message', 'wmh') . '</label><br/>
        <textarea name="cart[' . esc_attr($cart_item_key) . '][gift_message]" rows="2" style="width:100%;">' . esc_textarea($value) . '</textarea>
    </div>';
}

// --- Update cart item gift message when cart updated ---
add_action('woocommerce_cart_updated', 'wmh_update_cart_gift_messages', 20);
function wmh_update_cart_gift_messages()
{
    if (empty($_POST['cart'])) return;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($_POST['cart'][$cart_item_key]['gift_message'])) {
            $msg = sanitize_textarea_field($_POST['cart'][$cart_item_key]['gift_message']);
            WC()->cart->cart_contents[$cart_item_key]['gift_message'] = $msg;
        }
    }
}

// --- Save gift message to Order Item Meta ---
add_action('woocommerce_checkout_create_order_line_item', 'wmh_add_gift_message_order_item_meta', 10, 4);
function wmh_add_gift_message_order_item_meta($item, $cart_item_key, $values, $order)
{
    if (!empty($values['gift_message'])) {
        $item->add_meta_data(__('Gift Message', 'wmh'), $values['gift_message'], true);
    }
}

add_filter('big_image_size_threshold', '__return_false');

/**
 * Enable WooCommerce product search by SKU (compatible with Elementor Search)
 */
function custom_search_by_sku_in_woocommerce($query)
{

    if (! is_admin() && $query->is_search() && $query->is_main_query()) {

        $query->set('post_type', array('product'));


        $search_term = $query->get('s');
        if (! empty($search_term)) {
            add_filter('posts_where', function ($where) use ($search_term) {
                global $wpdb;
                $like = '%' . $wpdb->esc_like($search_term) . '%';
                $where .= $wpdb->prepare(
                    " OR {$wpdb->posts}.ID IN (
                        SELECT post_id FROM {$wpdb->postmeta}
                        WHERE meta_key = '_sku' AND meta_value LIKE %s
                    )",
                    $like
                );
                return $where;
            });
        }
    }
}
add_action('pre_get_posts', 'custom_search_by_sku_in_woocommerce');


// Add product url to the single//

add_action('wp_footer', 'cf7_add_product_url_script');
function cf7_add_product_url_script()
{
    if (is_product()) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var urlField = document.querySelector('#product-url');
                if (urlField) {
                    urlField.value = window.location.href;
                }
            });
        </script>
    <?php }
}
// Display form for out of stocks products//
function cf7_out_of_stock_form()
{
    if (! is_product()) return;
    global $product;
    if (! $product) return;
    if (! $product->is_in_stock()) {
        return do_shortcode('[contact-form-7 id="81ebe3f" title="Single 
         product contact form"]');
    }
}
add_shortcode('cf7_out_of_stock', 'cf7_out_of_stock_form');
add_filter('th_compare_fields', 'th_compare_add_short_description', 20);




// validation on checkout page//
add_action('woocommerce_checkout_process', 'validate_us_city_zipcode');

function validate_us_city_zipcode()
{
    $country  = isset($_POST['billing_country']) ? sanitize_text_field($_POST['billing_country']) : '';
    $city     = isset($_POST['billing_city']) ? sanitize_text_field($_POST['billing_city']) : '';
    $postcode = isset($_POST['billing_postcode']) ? sanitize_text_field($_POST['billing_postcode']) : '';

    if ($country !== 'US' || empty($postcode) || empty($city)) {
        return; // Only for US and filled values
    }

    // API URL for ZIP lookup
    $url = "https://api.zippopotam.us/us/" . urlencode($postcode);

    // Fetch ZIP data
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wc_add_notice(__('Unable to validate ZIP code at the moment. Please try again.'), 'error');
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data) || !isset($data['places'][0]['place name'])) {
        wc_add_notice(__('Invalid ZIP code. Please check and try again.'), 'error');
        return;
    }

    // Extract city name from API
    $valid_city = strtolower($data['places'][0]['place name']);

    // Compare with user input (case-insensitive)
    if (strtolower(trim($city)) !== $valid_city) {
        wc_add_notice(sprintf(
            __('City and ZIP code do not match. ZIP %s belongs to %s.'),
            esc_html($postcode),
            esc_html($data['places'][0]['place name'])
        ), 'error');
    }
}



// Shortcode to show price only if the product is in stock
function show_price_if_in_stock()
{
    global $product;

    if (! $product) {
        $product = wc_get_product(get_the_ID());
    }


    if (! $product) return '';


    if (! $product->is_in_stock()) {
        return '';
    }


    return '<div class="product-price">' . $product->get_price_html() . '</div>';
}
add_shortcode('show_price_if_in_stock', 'show_price_if_in_stock');

// Apply Discount to all the proudcts on checkout page//
add_action('woocommerce_cart_calculate_fees', 'tiered_discount_based_on_cart_total', 20, 1);

function tiered_discount_based_on_cart_total($cart)
{
    if (is_admin() && !defined('DOING_AJAX')) return;

    $cart_total = $cart->get_subtotal();

    $discount = 0;
    $discount_message = '';

    if ($cart_total <= 249) {
        $discount = $cart_total * 0.05;
        $discount_message = '5% off (Spend $249.00 and under)';
    } elseif ($cart_total >= 250 && $cart_total <= 499) {
        $discount = $cart_total * 0.10;
        $discount_message = '10% off (Spend $250.00 - $499.00)';
    } elseif ($cart_total >= 500 && $cart_total <= 1499) {
        $discount = $cart_total * 0.15;
        $discount_message = '15% off (Spend $500.00 - $1499.00)';
    } elseif ($cart_total >= 1500 && $cart_total <= 3999) {
        $discount = $cart_total * 0.20;
        $discount_message = '20% off (Spend $1500.00 - $3999.00)';
    } elseif ($cart_total >= 4000) {
        $discount = $cart_total * 0.25;
        $discount_message = '25% off (Spend $4000.00 and up)';
    }

    if ($discount > 0) {
        $cart->add_fee(__('Discount', 'woocommerce') . ' - ' . $discount_message, -$discount);
    }
}







// /**
//  * Safely link guest WooCommerce orders to existing users or create new ones.
//  * Supports dry-run, batch mode, and auto-resume for large stores.
//  */

// function link_guest_orders_to_customers($dry_run = true, $limit = 50) {
//     $offset = get_option('guest_order_linker_offset', 0); // Resume point

//     $args = [
//         'limit'    => $limit,
//         'offset'   => $offset,
//         'status'   => ['completed', 'processing', 'on-hold', 'pending'],
//         'customer' => 0 // guest orders only
//     ];

//     $orders = wc_get_orders($args);
//     $processed = 0;

//     if (empty($orders)) {
//         error_log("All guest orders processed. Offset: $offset");
//         echo "All guest orders processed (Offset: $offset).";
//         return;
//     }

//     foreach ($orders as $order) {
//         $order_id   = $order->get_id();
//         $email      = $order->get_billing_email();
//         $first_name = $order->get_billing_first_name();
//         $last_name  = $order->get_billing_last_name();

//         if (!$email) {
//             error_log("Order #$order_id skipped — no billing email.");
//             continue;
//         }

//         $user = get_user_by('email', $email);
//         $user_created = false;

//         // Create new user if not found
//         if (!$user) {
//             $base_username = sanitize_user(strtolower(trim($first_name . '.' . $last_name)));
//             if (empty($base_username)) {
//                 $base_username = sanitize_user(current(explode('@', $email)));
//             }

//             // Ensure unique username
//             $username = $base_username;
//             $i = 1;
//             while (username_exists($username)) {
//                 $username = $base_username . $i;
//                 $i++;
//             }

//             if (!$dry_run) {
//                 $random_password = wp_generate_password(12, false);
//                 $user_id = wp_create_user($username, $random_password, $email);

//                 if (is_wp_error($user_id)) {
//                     error_log("Failed to create user for order #$order_id: " . $user_id->get_error_message());
//                     continue;
//                 }

//                 wp_update_user([
//                     'ID'         => $user_id,
//                     'first_name' => $first_name,
//                     'last_name'  => $last_name,
//                 ]);

//                 $user = get_user_by('id', $user_id);
//                 $user_created = true;
//             } else {
//                 error_log("(Dry Run) Would create new user '$username' for email $email");
//             }
//         }

//         // Assign user to order
//         if ($user) {
//             if ($order->get_customer_id() == 0) {
//                 if (!$dry_run) {
//                     $order->set_customer_id($user->ID);
//                     $order->save();
//                     error_log("Linked Order #$order_id → User ID {$user->ID}" . ($user_created ? " (new user)" : ""));
//                 } else {
//                     error_log("(Dry Run) Would link Order #$order_id → User ID {$user->ID}");
//                 }
//             }
//         }

//         $processed++;
//     }

//     // Update offset
//     $new_offset = $offset + $processed;
//     if (!$dry_run) {
//         update_option('guest_order_linker_offset', $new_offset);
//     }

//     error_log("Batch processed: $processed orders. Next offset: $new_offset (Dry Run: " . ($dry_run ? 'Yes' : 'No') . ")");
//     echo "Processed $processed orders. Next offset: $new_offset (Dry Run: " . ($dry_run ? 'Yes' : 'No') . ")";
// }

// // ────────────────────────────────────────────────
// // Run via Browser (Admin Trigger)
// add_action('init', function() {
//     if (isset($_GET['link_guest_orders']) && current_user_can('manage_woocommerce')) {
//         $dry_run = isset($_GET['dry_run']) ? filter_var($_GET['dry_run'], FILTER_VALIDATE_BOOLEAN) : true;
//         $limit   = isset($_GET['limit']) ? intval($_GET['limit']) : 50;

//         link_guest_orders_to_customers($dry_run, $limit);
//         wp_die("Guest order linking batch complete (Dry Run: " . ($dry_run ? 'Yes' : 'No') . ").");
//     }

//     // Reset offset manually if needed
//     if (isset($_GET['reset_guest_order_offset']) && current_user_can('manage_woocommerce')) {
//         delete_option('guest_order_linker_offset');
//         wp_die("Offset reset complete. Next run will start from the beginning.");
//     }
// });

// // ────────────────────────────────────────────────
// // WP-CLI Command Support
// if (defined('WP_CLI') && WP_CLI) {
//     WP_CLI::add_command('orders:link_guests', function($args, $assoc_args) {
//         $dry_run = isset($assoc_args['dry-run']);
//         $limit   = isset($assoc_args['limit']) ? intval($assoc_args['limit']) : 50;

//         link_guest_orders_to_customers($dry_run, $limit);
//     });
// }




add_filter('woocommerce_get_price_html', 'custom_regular_and_as_low_as_price', 99, 2);
function custom_regular_and_as_low_as_price($price_html, $product)
{

    // Get main (regular) price
    $regular_price = $product->get_regular_price();

    // Get current or lowest/tiered price
    $lowest_price = $product->get_price();

    // Only modify if both prices exist and differ
    if ($regular_price && $lowest_price && $regular_price > $lowest_price) {
        $price_html = sprintf(
            '<span class="custom-price-display">
                <span class="regular-price" style="display:block; font-weight:bold; color:#000;">%s</span>
                <span class="as-low-as" style="display:block; color:#0073aa;">As low as %s</span>
            </span>',
            wc_price($regular_price),
            wc_price($lowest_price)
        );
    }

    return $price_html;
}




// Remove the default rating placement
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

// // Add a new function to output both in one div, only on shop/archive pages
// add_action( 'woocommerce_after_shop_loop_item_title', 'custom_rating_and_compare', 25 );

// function custom_rating_and_compare() {
//     if ( is_product() ) {
//         // Don't run on single product page
//         return;
//     }

//     global $product;

//     echo '<div class="custom-rating-compare">';

//     // Product rating
//     echo wc_get_rating_html( $product->get_average_rating() );

//     // Compare button via shortcode
//     echo do_shortcode('[th_compare pid="' . $product->get_id() . '"]');

//     echo '</div>';
// }



// *********************Adding recaptcha in review form**************//



//  CONFIG — replace with your actual keys
define('RECAPTCHA_SITE_KEY', '6Lff5_srAAAAAELg0sjKQQvx7njL0TsbUIn7v5bb
');
define('RECAPTCHA_SECRET_KEY', '6Lff5_srAAAAAFVtmwCFabgQD--buvLXhLh7rMpV
');

//  1. Enqueue Google reCAPTCHA script on product pages
function load_recaptcha_script_on_product()
{
    if (is_product()) {
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'load_recaptcha_script_on_product');

//  2. Add reCAPTCHA field to review form
function add_recaptcha_to_review_form($comment_form)
{
    if (is_product()) {
        $captcha_html = '<div class="g-recaptcha" data-sitekey="' . esc_attr(RECAPTCHA_SITE_KEY) . '"></div>';
        $comment_form['comment_field'] .= '<p class="form-row">' . $captcha_html . '</p>';
    }
    return $comment_form;
}
add_filter('woocommerce_product_review_comment_form_args', 'add_recaptcha_to_review_form');

// 3. Verify reCAPTCHA before allowing comment/review
function verify_recaptcha_on_review_submission($commentdata)
{
    if (is_product()) {
        if (empty($_POST['g-recaptcha-response'])) {
            wp_die('Error: Please complete the CAPTCHA to submit your review.', 'CAPTCHA Required', array('back_link' => true));
        }

        $response = sanitize_text_field($_POST['g-recaptcha-response']);
        $verify_response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret'   => RECAPTCHA_SECRET_KEY,
                'response' => $response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            )
        ));

        $result = json_decode(wp_remote_retrieve_body($verify_response), true);

        if (empty($result['success'])) {
            wp_die('CAPTCHA verification failed. Please try again.', 'CAPTCHA Failed', array('back_link' => true));
        }
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'verify_recaptcha_on_review_submission');


add_filter('woocommerce_get_price_html', function ($price, $product) {
    // Only modify for products with tiered pricing
    if (function_exists('wpt_get_product_tiered_price_table') && wpt_get_product_tiered_price_table($product->get_id())) {
        $regular_price = wc_price($product->get_regular_price());
        $tiered_price = $price; // existing tiered price HTML generated by plugin
        $price = '<span class="regular-price">Regular: ' . $regular_price . '</span><br>' .
            '<span class="tiered-price">Tiered: ' . $tiered_price . '</span>';
    }
    return $price;
}, 20, 2);




// Inline minimal jQuery UI Slider CSS for instant style
function alenar_inline_jquery_ui_slider_css()
{
    ?>
    <style id="jquery-ui-slider-inline-css">
        .ui-slider {
            position: relative;
            text-align: left;
        }

        .ui-slider .ui-slider-handle {
            position: absolute;
            z-index: 2;
            width: 1.2em;
            height: 1.2em;
            cursor: default;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #d11241;
        }

        .ui-slider .ui-slider-range {
            position: absolute;
            z-index: 1;
            font-size: .7em;
            display: block;
            border: 0;
            background: #d11241;
        }

        .ui-slider-horizontal {
            height: 6px;
        }

        .ui-slider-horizontal .ui-slider-handle {
            top: -0.5em;
            margin-left: -0.6em;
        }

        .ui-slider-horizontal .ui-slider-range {
            top: 0;
            height: 100%;
        }
    </style>
<?php
}
add_action('wp_head', 'alenar_inline_jquery_ui_slider_css', 5);

// In your functions.php
function alenar_early_enqueue_slider_css()
{
    if (is_shop() || is_product_category()) {
        wp_enqueue_style(
            'jquery-ui-slider-css',
            'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',
            array(),
            '1.13.2'
        );
    }
}
add_action('wp_enqueue_scripts', 'alenar_early_enqueue_slider_css', 5);

// Remove the Downloads tab from WooCommerce My Account
add_filter('woocommerce_account_menu_items', 'remove_downloads_my_account', 999);
function remove_downloads_my_account($items)
{
    if (isset($items['downloads'])) {
        unset($items['downloads']); // Remove the Downloads menu item
    }
    return $items;
}


// 

// function add_sequence_number_column_hpos($columns) {
//     // Add sequence number column after order number
//     $new_columns = array();
//     foreach ($columns as $key => $column) {
//         $new_columns[$key] = $column;
//         if ($key === 'order_number') {
//             $new_columns['sequence_number'] = 'Old Order ID';
//         }
//     }
//     return $new_columns;
// }

// // Add sequence number inside the header column after order meta
// add_action('woocommerce_admin_order_data_header_right', 'display_sequence_in_header');
// function display_sequence_in_header($order) {
//     $order_number = $order->get_meta('_magento_increment_id');

//     if (!empty($order_number)) {
//         echo '<div style="">';
//         echo '<p style="font-size: 16px;"> <strong>Old Order ID:</strong> #' . esc_html($order_number) . '</p>';
//         echo '</div>';
//     }
// }

// // Add sequence number inside the header column after order meta
// add_action('woocommerce_admin_order_data_header_right', 'display_sequence_in_header');
// function display_sequence_in_header($order) {
//     $order_number = $order->get_meta('_magento_increment_id');

//     if (!empty($order_number)) {
//         echo '<div style="">';
//         echo '<p style="font-size: 16px;"> <strong>Old Order ID:</strong> #' . esc_html($order_number) . '</p>';
//         echo '</div>';
//     }
// }

// Add outofstock class to custom product wrappers
add_filter('post_class', 'add_outofstock_class_to_products', 10, 3);
function add_outofstock_class_to_products($classes, $class, $post_id)
{
    if (get_post_type($post_id) === 'product') {
        $product = wc_get_product($post_id);
        if ($product && ! $product->is_in_stock()) {
            $classes[] = 'outofstock';
        } else {
            $classes[] = 'instock';
        }
    }
    return $classes;
}


add_action( 'elementor/query', function( $query ) {
    // Check if this is a product query (Loop Carousel or Grid)
    if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'product' ) {
        $meta_query = $query->get( 'meta_query' );
        $meta_query[] = [
            'key'     => '_stock_status',
            'value'   => 'outofstock',
            'compare' => '!='
        ];
        $query->set( 'meta_query', $meta_query );
    }
});







// ********show compare to loop widgets***********//
function th_compare_dynamic_button() {
    global $product;
    return do_shortcode('[th_compare pid="' . $product->get_id() . '"]');
}
add_shortcode('th_compare_dynamic', 'th_compare_dynamic_button');

add_shortcode('tiered_price', function($atts) {
    $atts = shortcode_atts([
        'product_id' => get_the_ID(),
    ], $atts, 'tiered_price');

    $product_id = $atts['product_id'];

    if (!$product_id) return '';

    // Check if the function from the plugin exists
    if (function_exists('wpt_get_tiered_price_table')) {
        ob_start();
        echo '<div class="tiered-pricing-wrapper">';
        echo wpt_get_tiered_price_table($product_id);
        echo '</div>';
        return ob_get_clean();
    } else {
        return '<!-- Tiered Price Table plugin not active or function missing -->';
    }
});




// Function to check tier pricing and trigger action
function check_and_trigger_tier_pricing_action($product) {
    $product_id = $product->get_id();
    $pricing_rule = \TierPricingTable\PriceManager::getPricingRule($product_id);
    $tier_rules = $pricing_rule ? $pricing_rule->getRules() : null;

    if (is_array($tier_rules) && count($tier_rules) > 0) {
        do_action('woocommerce_after_shop_loop_item_title');
    }
}


// ****************Adding prize in the loop widgets**********************//
add_shortcode('normal_price', function($atts) {
    $atts = shortcode_atts([
        'product_id' => get_the_ID(),
    ], $atts, 'normal_price');

    $product = wc_get_product($atts['product_id']);

    if (!$product) {
        return '';
    }

    
    check_and_trigger_tier_pricing_action($product);

 
 
    $sale_price = $product->get_sale_price();
    $regular_price = $product->get_regular_price();

    $price_to_show = $sale_price ? $sale_price : $regular_price;

    if (!$price_to_show) {
        return '';
    }

    // Return formatted WooCommerce price
    return '<span class="normal-price">' . wc_price($price_to_show) . '</span>';
});


// ****************Adding rating in the loop widgets**********************//
function product_star_rating_shortcode() {
    global $product;

    if ( ! $product ) return '';

    $average = $product->get_average_rating();

    if ( ! $average || $average == 0 ) {
        return ''; // No rating available
    }

    ob_start();
    echo '<div class="custom-rating">';
    echo wp_kses_post( wc_get_rating_html( $average ) );
    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'product_star_rating', 'product_star_rating_shortcode' );



// ******************************* Show only star rating in related products*******************************//


add_action( 'woocommerce_after_shop_loop_item', 'custom_rating_compare_wrapper', 5 );

function custom_rating_compare_wrapper() {
    global $product;

    // Only run on single product (e.g., related section)
    if ( ! is_product() ) return;

    // Start outer wrapper
    echo '<div class="custom-rating-compare-outer">';

        // ==== Compare Button ====
        echo '<div class="custom-rating-compare">';
        echo do_shortcode('[th_compare pid="' . esc_attr( $product->get_id() ) . '"]');
        echo '</div>';

        // ==== Star Rating ====
        echo '<div class="custom-rating">';
        echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) );
        echo '</div>';

    // Close outer wrapper
    echo '</div>';
}


// // ✅ Show regular, sale, and tiered prices in related products
// add_action('woocommerce_after_shop_loop_item', 'custom_related_products_prices', 6);

// function custom_related_products_prices() {
//     global $product;

//     // Only on single product pages (related section)
//     if ( ! is_product() ) return;

//     // 💰 Regular and Sale Price
//     $regular_price = $product->get_regular_price();
//     $sale_price    = $product->get_sale_price();

//     if ( $regular_price ) {
//         echo '<div class="related-product-prices">';

//         if ( $sale_price ) {
//             echo '<span class="price-block"><del>' . wc_price($regular_price) . '</del> ';
//             echo '<ins>' . wc_price($sale_price) . '</ins></span>';
//         } else {
//             echo '<span class="price-block">' . wc_price($regular_price) . '</span>';
//         }

//         echo '</div>';
//     }

//     // 📦 Tiered Pricing (trigger plugin function if available)
//     if ( function_exists('check_and_trigger_tier_pricing_action') ) {
//         check_and_trigger_tier_pricing_action($product);
//     } else {
//         $tiered_price = get_post_meta($product->get_id(), '_tiered_pricing_lowest_price', true);
//         if ( $tiered_price ) {
//             echo '<small class="tiered-price">As low as ' . wc_price($tiered_price) . '</small>';
//         }
//     }
// }


// *******************************Redirect gunshow category to event page*******************************//


add_action('template_redirect', function() {
    if (is_tax('product_cat', 'gunshow-schedule')) {
        wp_redirect(home_url('/events/'), 301);
        exit;
    }
});

// ******************************* fallback image******************************//

// Fallback image URL
function my_custom_fallback_image_url() {
    return get_stylesheet_directory_uri() . '/img/Frame 44.png';
}

// For products without an image
add_filter('woocommerce_placeholder_img_src', 'my_prod_placeholder_img_src');
function my_prod_placeholder_img_src($src) {
    return my_custom_fallback_image_url();
}

add_filter('woocommerce_get_product_thumbnail', 'my_prod_thumbnail_fallback', 10, 2);
function my_prod_thumbnail_fallback($html, $post_id) {
    if (empty($html)) {
        $url = my_custom_fallback_image_url();
        $html = '<img src="' . esc_url($url) . '" alt="' . esc_attr__('No image available', 'your-text-domain') . '" class="wp-post-image" />';
    }
    return $html;
}

// For product categories without a thumbnail
add_filter('woocommerce_subcategory_thumbnail', 'my_cat_thumbnail_fallback', 10, 2);
function my_cat_thumbnail_fallback($category_html, $category) {
    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
    if ( !$thumbnail_id ) {
        $url = my_custom_fallback_image_url();
        $category_html = '<img src="' . esc_url($url) . '" alt="' . esc_attr( $category->name ) . '" />';
    }
    return $category_html;
}








/**
* ===============================================================
* WooCommerce HPOS:
* Show & Search Magento Old Order ID (_magento_increment_id)
* Stored in xni_wc_orders_meta
* ===============================================================
*/
 
 
/** 1️⃣ Add "Old Order ID" column to the admin Orders list */
add_filter('manage_edit-shop_order_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ($key === 'order_number') {
            $new['old_order_id'] = __('Old Order ID', 'woocommerce');
        }
    }
    return $new;
});
 
 
/** 2️⃣ Display Old Order ID in the Orders list */
add_action('manage_shop_order_posts_custom_column', function ($column) {
    if ($column !== 'old_order_id') return;
    $order = wc_get_order(get_the_ID());
    $old   = $order->get_meta('_magento_increment_id');
    echo $old ? '<strong>#' . esc_html($old) . '</strong>' : '—';
});
 
 
/** 3️⃣ Show Old Order ID on order edit page */
add_action('woocommerce_admin_order_data_after_order_details', function ($order) {
    $old = $order->get_meta('_magento_increment_id');
    if ($old) {
        echo '<div style="margin-top:10px;font-size:14px;">
                <strong>Old Order ID:</strong> #' . esc_html($old) . '
              </div>';
    }
});
 
 
/** 4️⃣ Admin Search: Find by Old Magento ID (HPOS: xni_wc_orders_meta ONLY) */
add_filter('woocommerce_shop_order_search_results', function ($order_ids, $search, $search_fields) {
    global $wpdb;
 
    $search = trim(sanitize_text_field($search));
    if ($search === '') return $order_ids;
 
    // Only numeric old IDs need hybrrid matching
    if (!ctype_digit($search)) return $order_ids;
 
    $trimmed = ltrim($search, '0');
    $like    = '%' . $wpdb->esc_like($trimmed) . '%';
 
    $meta_table = $wpdb->prefix . 'wc_orders_meta'; // ✅ xni_wc_orders_meta
 
    // 🔥 This finds ANY order where _magento_increment_id matches in flexible formats
    $ids = $wpdb->get_col($wpdb->prepare("
        SELECT order_id
        FROM {$meta_table}
        WHERE meta_key = '_magento_increment_id'
        AND (
            meta_value = %s      -- exact e.g. 000040856
            OR meta_value = %s   -- WP-ID style, e.g. 40856
            OR meta_value LIKE %s -- partial e.g. 408
        )
    ", $search, $trimmed, $like));
 
    // Also include native WP order ID match
    $wp_order = wc_get_order((int)$search);
    if ($wp_order) {
        $ids[] = $wp_order->get_id();
    }
 
    return array_unique(array_merge($order_ids, $ids));
 
}, 10, 3);
 
 
/**
* Make Old Magento Order ID searchable in admin order search
*/
add_filter('woocommerce_shop_order_search_fields', function($fields){
    $fields[] = '_magento_increment_id';
    return $fields;
});


 
// ****************** Adding star ratinf to search page products widget***************//


// Remove rating from the default position (if already added)
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

// Add it before the Add to Cart button
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 9 );


// ****************** Adding regular prize to search page and relaated produts**********************//


add_action( 'woocommerce_after_shop_loop_item_title', 'show_regular_price_on_search_or_single', 6 );

function show_regular_price_on_search_or_single() {
    global $product;

    // Get current body classes
    $body_classes = get_body_class();

    // Show only if the page has class 'search' OR 'single'
    if ( !in_array( 'search', $body_classes ) && !in_array( 'single', $body_classes ) ) {
        return;
    }

    if ( $product && $product->get_regular_price() ) {
        echo '<div class="regular-price" style="font-size:14px; color:#777;">';
        echo wc_price( $product->get_regular_price() );
        echo '</div>';
    }
}
// *****************show stock quantity on the single product page//
function custom_product_stock_status_shortcode() {
    global $product;

    if ( ! $product ) {
        return '';
    }

    // Check stock status
    if ( $product->is_in_stock() ) {
        $stock_quantity = $product->get_stock_quantity();

        if ( $stock_quantity > 0 ) {
            return '<p class="custom-stock-status">In stock — Only ' . $stock_quantity . ' left!</p>';
        } else {
            // For products marked in-stock but with no quantity tracking
            return '<p class="custom-stock-status">In stock</p>';
        }
    } else {
        return '<p class="custom-stock-status">Out of stock</p>';
    }
}
add_shortcode( 'product_stock_status', 'custom_product_stock_status_shortcode' );


// $csvFilePath = './cpe.csv';
// $newFilePath = './modifiedCPE.csv';

// $columns = ["SKU","Old Link","New Link"];

// $file = fopen($csvFilePath,'r');
// $newFile = fopen($newFilePath,'w');

// while(($data=fgetcsv($file))!==FALSE){
//     $row = array_combine($columns,$data);
//     $link = get_permalink(wc_get_product_id_by_sku($data[0]));
//     $row['New Link'] = $link;
//     fputcsv($newFile,array_values($row));
// }

// fclose($file);
// fclose($newFile);


