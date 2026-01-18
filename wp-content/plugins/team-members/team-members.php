<?php
/**
 * Plugin Name:       Team Members
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       team-members
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function create_block_team_members_block_init() {
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'create_block_team_members_block_init' );

add_action('add_meta_boxes', function () {
    remove_meta_box('genrediv', 'team_member', 'side');
});


/**
 * Register CPT & Taxonomy
 */
add_action('init', function () {

    register_post_type('team_member', [
        'label' => 'Team Members',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'thumbnail'],
    ]);

    register_taxonomy('genre', 'team_member', [
        'label' => 'Genre',
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    ]);
});

add_action('acf/init', function () {

    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key' => 'group_team_member',
        'title' => 'Team Member Details',
        'fields' => [
            [
                'key' => 'field_position',
                'label' => 'Position',
                'name' => 'position',
                'type' => 'text',
                'required' => true,
            ],
            [
                'key' => 'field_genre',
                'label' => 'Genre',
                'name' => 'genre',
                'type' => 'taxonomy',
                'taxonomy' => 'genre',
                'field_type' => 'radio', // <--- make it single select
                'allow_null' => 0,
                'add_term' => 1,
                'load_save_terms' => 1,
                'return_format' => 'id',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'team_member',
                ],
            ],
        ],
        'show_in_rest' => true,
    ]);
});


/**
 * REST API Endpoint
 */
add_action('rest_api_init', function () {

    register_rest_route('team/v1', '/members', [
        'methods' => 'GET',
        'callback' => 'team_members_rest',
        'permission_callback' => '__return_true',
    ]);
});

function team_members_rest(WP_REST_Request $request) {

    $genre = sanitize_text_field($request->get_param('genre'));

    $args = [
        'post_type' => 'team_member',
        'posts_per_page' => -1,
    ];

    if ($genre) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'genre',
                'field' => 'slug',
                'terms' => $genre,
            ],
        ];
    }

    $query = new WP_Query($args);
    $data = [];

    while ($query->have_posts()) {
        $query->the_post();

        $data[] = [
            'id' => get_the_ID(),
            'name' => get_the_title(),
            'position' => get_field('position'),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
            'genres' => wp_get_post_terms(get_the_ID(), 'genre', ['fields' => 'names']),
        ];
    }

    wp_reset_postdata();

    return rest_ensure_response($data);
}

add_filter('enter_title_here', function ($title, $post) {
    if ($post->post_type === 'team_member') {
        return 'Add Member Name';
    }
    return $title;
}, 10, 2);




