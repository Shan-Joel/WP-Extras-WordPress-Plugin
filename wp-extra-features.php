<?php
/*
    Plugin Name: WP Extra Features
    Description: WordPress plugin that can help you generate CPTs automatically using a shortcode
    Version: 1.0
    Author: Shan Joel
    Author URI: https://shanjoel.com
    License: GPLv2 or later
    Text Domain: wp-extra-features
*/

if (! defined('ABSPATH')) {
    exit;
} // if direct access

function surge_projects_wp_extra_post_type()
{
    $args = array(
            'description'               => 'WP Extra Projects',
            'show_ui'                   => true,
            'menu_position'             => 2,// places menu item directly below Posts. Ex: 4,5,6..etc
            'menu_icon'                 => 'dashicons-portfolio', // menu icon
            'exclude_from_search'       => false,
            'labels'                    => array('name'=> 'WP Extra Projects','singular_name' => 'WP Extra Project','add_new' => 'Add WP Extra Project','add_new_item' => 'Add WP Extra Project','edit' => 'Edit WP Extra Project','edit_item' => 'Edit WP Extra Project','new-item' => 'New WP Extra Project','view' => 'View WP Extra Project',            	'view_item' => 'View Project','search_items' => 'Search Projects','not_found' => 'No Projects Found','not_found_in_trash' => 'No Projects Found in Trash','parent' => 'Parent Projects'
            ),
            'public'                    => true,
            'capability_type'           => 'post',
            'hierarchical'              => false,
            'rewrite'                   => true,
            'supports'                  => array('title', 'editor', 'excerpt', 'thumbnail','custom-fields'),
            'has_archive'               => true
    );
    register_post_type('wp-extra-project', $args);
}

//Call
add_action('init', 'surge_projects_wp_extra_post_type');

if (!function_exists('surge_project_wp_extra_category_taxonomy')) {
    function surge_project_wp_extra_category_taxonomy()
    {
        register_taxonomy(
            'wp-extra-project-category',
            'wp-extra-project',
            array(
                'labels'                 => array(
                    'name'               => 'WP Extra Category',
                    'singular_name'      => 'WP Extra Category',
                    'search_items'       => 'Search WP Extra Categories',
                    'popular_items'      => 'Popular WP Extra Categories',
                    'all_items'          => 'All WP Extra Categories',
                    'parent_item'        => 'Parent WP Extra Categories',
                    'parent_item_colon'  => 'Parent WP Extra Categories:',
                    'edit_item'          => 'Edit WP Extra Categories',
                    'update_item'        => 'Update WP Extra Categories',
                    'add_new_item'       => 'Add WP Extra Categories',
                    'new_item_name'      => 'New WP Extra Categories',
                ),
                    'hierarchical'       => true,
                    'show_ui'            => true,
                    'show_tagcloud'      => true,
                    'show_admin_column'  => true,
                    'rewrite'            => false,
                    'public'             => true
                ) // end array
        );
    }
}

add_action('init', 'surge_project_wp_extra_category_taxonomy');

function sh_project_settings_page()
{
    add_options_page('WP Extra Project Settings', 'WP Extra Project Settings', 'manage_options', 'sh-project-settings', 'sh_render_project_settings_page');
}
add_action('admin_menu', 'sh_project_settings_page');
  
function sh_render_project_settings_page()
{
    ?>
<div class="wp-extra-main-wrapper">
    <h2>WP Extra Features Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('sh_projects_options_group'); ?>
        <div class="details-container">
            <div class="wp-extra-meta">
                <h3>User Details</h3>

                <?php $user_info = get_userdata(1);?>
                <div class="wp__extra__meta">
                    <?php echo 'Name: ' . $user_info->user_login;?>
                </div>
                <div class="wp__extra__meta">
                    <?php echo 'Email: ' . $user_info->user_email;?>
                </div>
            </div>
            <div class="wp-extra-container">
                <h3>Shortcodes</h3>
                <p>[wp-extra-feature]</p>
            </div>
        </div>
</div>
<?php
}

function wp_extra_admin_css()
{
    $plugin_dir = '/wp-content/plugins/wp-extra-features/assets/css/wp-extra-features-admin.css';
    wp_enqueue_style('wp_extra_admin_css', $plugin_dir, false, '1.0.0');
}
add_action('admin_enqueue_scripts', 'wp_extra_admin_css');

function wp_extra_frontend_css()
{
    $plugin_dir2 = '/wp-content/plugins/wp-extra-features/assets/css/wp-extra-features-frontend.css';
    wp_enqueue_style('wp_extra_frontend_css', $plugin_dir2, false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'wp_extra_frontend_css');

function wp_extra_frontend_js()
{
    $plugin_dir2 = '/wp-content/plugins/wp-extra-features/assets/js/wp-extra-features-frontend.js';
    wp_enqueue_script('wp_extra_frontend_js', $plugin_dir2, false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'wp_extra_frontend_js');

function wp_extra_fontawesome_css()
{
    wp_enqueue_style('wp_extra_fontawesome_css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css', false, '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'wp_extra_fontawesome_css');


function surge_wp_extra_feature_shortcode()
{
    ?>
<div class="wp-extra-toggle">
    <button class="listview"><i class="fa fa-bars"></i> List</button>
    <button class="gridview"><i class="fa fa-th-large"></i> Grid</button>
</div>
<div class="wp-extra-projects-wrapper display-grid">
    <?php
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    $latests = new WP_Query(array(
    'post_type'         => 'wp-extra-project',
    'order'             => 'DESC',
    'posts_per_page'    => 20,
    'paged'             => $paged,
    ));

    while($latests->have_posts()): $latests->the_post();
        ?>
    <div class="surge_extra-features_post">
        <div class="flex-item">
            <a href="<?php the_permalink(); ?>">
                <div class="surge-extra-features-post-featured-image"> <?php echo get_the_post_thumbnail(); ?>
                </div>
            </a>
            <div>
                <span class="surge-extra-features-post-date-and-time">
                    <?php
                    echo the_time('d M Y');
        ?>
                </span>
                <a href="<?php the_permalink(); ?>">
                    <div class="surge-extra-features-post-title">
                        <h1> <?php the_title(); ?>
                        </h1>
                    </div>
                </a>
                <?php
        echo '<p class="latest-post-author"> Written by <a " href="'. get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author().' </a> </p>';
        ?>
                <div class="surge-extra-features-post-tags">
                    <?php
                $categories = get_the_category();
        $separator = ' ';
        $output = '';
        if (! empty($categories)) {
            foreach($categories as $category) {
                $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'textdomain'), $category->name)) . '">' . esc_html($category->name) . '</a>' . $separator;
            }
            echo trim($output, $separator);
        }
        ?>
                </div>
                <a href="<?php the_permalink(); ?>">
                    <div class="surge-extra-features-post-excerpt"> <?php the_excerpt(); ?>
                    </div>
                </a>
                <div class="surge-extra-features-read-more"> <a
                        href="<?php the_permalink(); ?>"> Read more
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    endwhile;
    ?>
</div>
<?php
   wp_reset_query();
}
add_shortcode('wp-extra-feature', 'surge_wp_extra_feature_shortcode');
