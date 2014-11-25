<?php

add_filter('of_options', function($options) {

    // Pull all the pages into an array
    $plugin_pages = array();
    $plugin_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $plugin_pages[''] = 'Select a page:';
    foreach ($plugin_pages_obj as $page) {
        $plugin_pages[$page->ID] = $page->post_title;
    }

    //  Needed for customizer since customizer is front end
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if ( is_plugin_active('simple-cpt-plugin/simple-plugin-name.php') ) :

        $options[] = array(
            "name"  => "Simple CPT",
            "type"  => "heading",
            "desc"  => "Simple CPT settings."
        );

            // Post Type Name
            $options['simple_plugin_name_name'] = array(
                'name'  => __('Post Type Name', SIMPLE_THEME_SLUG),
                'desc'  => __('Overrides Portfolio name, defaults to Example. Note: Changing this will register a new post type. Recommended to set this from get-go. Your custom posts might appear lost, but you can revert by setting this back to example.', SIMPLE_THEME_SLUG),
                'id'    => 'simple_plugin_name_name',
                'std'   => 'Example',
                'type'  => 'text'
            );

            // Post Type Options
            $options['simple_plugin_name_options'] = array(
                'name'      => __('Set Custom Post Type Options', SIMPLE_THEME_SLUG),
                'desc'      => __('Select which options your post type should support.', SIMPLE_THEME_SLUG),
                'id'        => 'simple_plugin_name_options',
                'type'      => 'multicheck',
                'options'   => array(
                    'heirarchial'   =>  'Heirarchial',
                    'has_archive'   =>  'Has Archive',
                    'rewrite'       =>  'Rewrite',
                    'taxonomy'      =>  'Custom Taxonomy',
                ),
                'std'       => array(
                    'heirarchial'   => '1',
                    'has_archive'   => '1',
                    'rewrite'       => '0',
                    'taxonomy'      => '0'
                )
            );

                // Rewrite
                $options['simple_plugin_name_options_rewrite'] = array(
                    'name'  => __('Custom Post Type Rewrite', SIMPLE_THEME_SLUG),
                    'desc'  => __('Set the custom post type rewrite slug.', SIMPLE_THEME_SLUG),
                    'id'    => 'simple_plugin_name_options_rewrite',
                    'std'   => '',
                    'type'  => 'text',
                    'class' => 'hidden'
                );

                // Taxonomy
                $options['simple_plugin_name_tax'] = array(
                    'name'  => __('Custom Post Type Taxonomy', SIMPLE_THEME_SLUG),
                    'desc'  => __('Set the custom post type tax.', SIMPLE_THEME_SLUG),
                    'id'    => 'simple_plugin_name_tax',
                    'std'   => '',
                    'type'  => 'text',
                    'class' => 'hidden'
                );

            // plugin_name Default Styles
            // $options['simple_plugin_name_default_styles'] = array(
            //     'name'  => __('Enable plugin_name Default Styles', SIMPLE_THEME_SLUG),
            //     'desc'  => __('Enables plugin_name default styles, defaults to true.', SIMPLE_THEME_SLUG),
            //     'id'    => 'simple_plugin_name_default_styles',
            //     'std'   => '1',
            //     'type'  => 'checkbox'
            // );

    endif;

    return $options;

});