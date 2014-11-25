<?php
/**
* Simple CPT Plugin.
*
* @package   Simple_Cpt_Plugin_Class
* @author    Constantine Kiriaze, hello@kiriaze.com
* @license   GPL-2.0+
* @link      http://getsimple.io
* @copyright 2013 Constantine Kiriaze
*
*
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Simple_Cpt_Plugin_Class' ) ) :

class Simple_Cpt_Plugin_Class {

    function __construct() {

        //  Grab globals passed from init
        global $cpt_slug, $cpt_name, $cpt_plural, $cpt_tax, $heirarchial, $has_archive, $rewrite, $defaultStyles;

        //  Set them relative to function
        $this->cpt_slug     = $cpt_slug;
        $this->cpt_name     = $cpt_name;
        $this->cpt_plural   = $cpt_plural;
        $this->cpt_tax      = $cpt_tax;
        $this->heirarchial  = $heirarchial;
        $this->has_archive  = $has_archive;
        $this->rewrite      = $rewrite;
        $this->defaultStyles = $defaultStyles;


        //  Plugin Activation
        register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

        //  Translation
        load_plugin_textdomain( 'simple', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        //  Thumbnails
        add_theme_support( 'post-thumbnails' );

        //  CPT/Tax & Meta
        add_action( 'init', array( &$this, 'cpt_init' ) );

        //  Columns
        add_filter( 'manage_edit-'.$this->cpt_slug.'_columns', array( &$this, 'add_cpt_columns'), 10, 1 );
        add_action( 'manage_'.$this->cpt_slug.'_posts_custom_column', array( &$this, 'display_cpt_columns' ), 10, 1 );
        add_filter( 'manage_edit-'.$this->cpt_slug.'_sortable_columns', array( &$this, 'cpt_columns_register_sortable' ) );


        add_action( 'right_now_content_table_end', array( &$this, 'add_cpt_counts' ) );
        add_action( 'admin_head', array( &$this, 'cpt_icon' ) );

        if( $this->defaultStyles ) {
            //  Load template styles
            add_action( 'wp_enqueue_scripts', array( &$this, 'load_styles' ), 101 );
        }

    }

    //  FLUSH REWRITE RULES
    function plugin_activation() {
        flush_rewrite_rules();
    }

    function cpt_init() {

        //  Register cpt / tax
        $post_types = array(

            $this->cpt_slug =>  array(
                'labels'                    => array(
                    'name'                      => __( $this->cpt_plural ),
                    'singular_name'             => __( $this->cpt_name ),
                    'add_new'                   => __( 'Add New ' . $this->cpt_name ),
                    'add_new_item'              => __( 'Add New ' . $this->cpt_name ),
                    'edit_item'                 => __( 'Edit ' . $this->cpt_name ),
                    'new_item'                  => __( 'Add New ' . $this->cpt_name ),
                    'view_item'                 => __( 'View ' . $this->cpt_name ),
                    'search_items'              => __( 'Search ' . $this->cpt_plural ),
                    'not_found'                 => __( 'No '. $this->cpt_plural .' found' ),
                    'not_found_in_trash'        => __( 'No '. $this->cpt_plural .' found in trash' )
                ),
                'public'                    => true,
                'supports'                  => array( 'title', 'editor','thumbnail'),
                'capability_type'           => 'post',
                'menu_position'             => '15',
                'hierarchical'              => $this->heirarchial,
                'has_archive'               => $this->has_archive,
                'rewrite'                   => unserialize($this->rewrite)
            )
        );

        foreach( $post_types as $post_type => $args ) {
            register_post_type( $post_type, $args );
        };

        global $taxonomies;

        $taxonomies = array(
            $this->cpt_slug . '_tag_labels'         => array(
                'object_type'                   => $this->cpt_slug,
                'label'                         => $this->cpt_name. ' Tags',
                'labels'                        => array(
                        'name'                      => $this->cpt_name. ' Tags',
                        'singluar_name'             => substr_replace( $this->cpt_name. ' Tags', "", -1 ),
                    ),
                'public'                        => true,
                'show_in_nav_menus'             => false,
                'show_ui'                       => true,
                'show_tagcloud'                 => false,
                'hierarchical'                  => true,
                'rewrite'                       => array('slug' => $this->cpt_slug . '_tag'),
                'link_to_post_type'             => false,
                'post_type_link'                => null,
                'has_archive'                   => true
            ),
            $this->cpt_slug . '_category_labels'    => array(
                'object_type'                   => $this->cpt_slug,
                'label'                         => $this->cpt_name. ' Categories',
                'labels'                        => array(
                        'name'                      => $this->cpt_name. ' Categories',
                        'singluar_name'             => substr_replace( $this->cpt_name. ' Categories', "", -1 ),
                    ),
                'public'                        => true,
                'show_in_nav_menus'             => false,
                'show_ui'                       => true,
                'show_tagcloud'                 => false,
                'hierarchical'                  => true,
                'rewrite'                       => array('slug' => $this->cpt_slug . '_category'),
                'link_to_post_type'             => false,
                'post_type_link'                => null,
                'has_archive'                   => true
            ),

        );

        // conditional check if custom tax set
        if( $this->cpt_tax ) :

            $custom_tax = array(
                preg_replace("/\W/", "_", strtolower($this->cpt_tax) )    => array(
                    'object_type'                   => $this->cpt_slug,
                    'label'                         => $this->cpt_tax,
                    'labels'                        => array(
                            'name'                      => $this->cpt_tax,
                            'singluar_name'             => substr_replace( $this->cpt_tax.'s', "", -1 ),
                        ),
                    'public'                        => true,
                    'show_in_nav_menus'             => false,
                    'show_ui'                       => true,
                    'show_tagcloud'                 => false,
                    'hierarchical'                  => true,
                    'rewrite'                       => array('slug' => preg_replace("/\W/", "-", strtolower($this->cpt_tax) ) ),
                    'link_to_post_type'             => false,
                    'post_type_link'                => null,
                    'has_archive'                   => true
                )
            );

            $taxonomies = array_merge($taxonomies, $custom_tax);

        endif;

        global $association_array;
        $association_array = array();

        foreach( $taxonomies as $taxonmy => $args ) {

            register_taxonomy( $taxonmy, $args['object_type'], $args );

            if( $args['link_to_post_type'] )
            $association_array[$taxonmy] = $args['post_type_link'];

        }

    }

    //  Add Columns
    function add_cpt_columns( $columns ) {

        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Name' ),
            $this->cpt_slug . '_id'      => __( $this->cpt_name . ' ID' ),
            $this->cpt_slug . '_value'  => __( $this->cpt_name . ' Value' ),
            'date'          => __( 'Date' )
        );

        return $columns;
    }

    //  Add data to columns
    function display_cpt_columns( $column ) {

        global $post;

        switch ( $column ) {

            case $this->cpt_slug . '_id':
            echo get_field($this->cpt_slug . '_id');
                break;

            case $this->cpt_slug . '_value':
            $field = get_field_object($this->cpt_slug . '_value');
            $value = get_field($this->cpt_slug . '_value');
            $label = $field['choices'][ $value ];
            echo $label;
                break;


            // Just break out of the switch statement for everything else.
            default :
                break;
        }

    }

    //  Register the columns as sortable
    function cpt_columns_register_sortable( $columns ) {
        $columns[$this->cpt_slug . '_id'] = $this->cpt_slug . '_id';
        $columns[$this->cpt_slug . '_value'] = $this->cpt_slug . '_value';

        return $columns;
    }

    //  Add count to dashboard widget
    function add_cpt_counts() {

        if ( ! post_type_exists( $this->cpt_slug ) ) {
            return;
        }

        $num_posts = wp_count_posts( $this->cpt_slug );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $this->cpt_name . '', $this->cpt_name . 's', intval($num_posts->publish) );
        if ( current_user_can( 'edit_posts' ) ) {
            $num = "<a href='edit.php?post_type=".$this->cpt_slug."'>$num</a>";
            $text = "<a href='edit.php?post_type=".$this->cpt_slug."'>$text</a>";
        }
        echo '<td class="first b b-'.$this->cpt_slug.'">' . $num . '</td>';
        echo '<td class="t '.$this->cpt_slug.'">' . $text . '</td>';
        echo '</tr>';

        if ($num_posts->pending > 0) {
            $num = number_format_i18n( $num_posts->pending );
            $text = _n( $this->cpt_name . ' Pending', $this->cpt_name . 's Pending', intval($num_posts->pending) );
            if ( current_user_can( 'edit_posts' ) ) {
                $num = "<a href='edit.php?post_status=pending&post_type=".$this->cpt_slug.">$num</a>";
                $text = "<a href='edit.php?post_status=pending&post_type=".$this->cpt_slug.">$text</a>";
            }
            echo '<td class="first b b-'.$this->cpt_slug.'">' . $num . '</td>';
            echo '<td class="t '.$this->cpt_slug.'">' . $text . '</td>';

            echo '</tr>';
        }
    }

    //  CUSTOM ICON FOR POST TYPE
    function cpt_icon() {
        // wp_enqueue_style( 'admin-cpt-plugin-css', plugins_url( 'assets/css/admin.css', __FILE__ ) );
    }

    //  DEFAULT STYLES
    function load_styles() {
        // wp_enqueue_style( 'cpt-plugin-css', plugins_url( 'assets/css/cpt-plugin.css', __FILE__ ) );
    }

    // Load scripts
    function load_scripts() {
        wp_enqueue_script( 'admin-simple-cpt-plugin-js', plugins_url( 'assets/js/admin.js', __FILE__ ) );
    }

}

new Simple_Cpt_Plugin_Class;

endif;