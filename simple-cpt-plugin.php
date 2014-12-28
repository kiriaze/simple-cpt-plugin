<?php
/**
    *
    * @package   Simple CPT Plugin
    * @author    Constantine Kiriaze, hello@kiriaze.com
    * @license   GPL-2.0+
    * @link      http://getsimple.io
    * @copyright 2013 Constantine Kiriaze
    *
	* Plugin Name:     Simple CPT Plugin
	* Plugin URI:      http://getsimple.io
	* Description:     Simple CPT Plugin Description
	* Version:         1.0
	* Author:          Constantine Kiriaze (@kiriaze)
	* Author URI:      http://getsimple.io/about
    * Text Domain:     'simple'
    * Copyright:       (c) 2013, Constantine Kiriaze
    * License:         GNU General Public License v2 or later
    * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
    Copyright (C) 2013  Constantine Kiriaze (hello@kiriaze.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Example of setting constants within theme - Note: must be outside after_setup_theme(), within functions.php
// define( 'SIMPLE_CPT_PLUGIN_NAME', 'Event' );
// define( 'SIMPLE_CPT_PLUGIN_TAX', 'Venues' );
// define( 'SIMPLE_CPT_PLUGIN_HIERARCHIAL', true );
// define( 'SIMPLE_CPT_PLUGIN_ARCHIVE', true );
// define( 'SIMPLE_CPT_PLUGIN_REWRITE_URL', 'concerts' );

// Settings
if ( class_exists('acf') ) :

    // load simple multi cpt acf settings
    // require_once( plugin_dir_path( __FILE__ ) . 'simple-cpt-acf.php' );

    // Simple Multi Custom Post Type Settings Page
    if ( function_exists('acf_add_options_sub_page') ) {

        acf_add_options_page(array(
            'page_title'    => 'SCPT Settings',
            'menu_title'    => 'SCPT Settings',
            'menu_slug'     => 'scpt-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));

    }

endif;

//  Wrapped in after_setup_theme
add_action('after_setup_theme', 'simple_CPT_Plugin_init', 12);
function simple_CPT_Plugin_init(){

    global
    $plugin_name,
    $prefix,
    $plugin_url,
    $plugin_path,
    $plugin_basename,
    $cpt_slug,
    $cpt_name,
    $cpt_plural,
    $cpt_tax,
    $heirarchial,
    $has_archive,
    $rewrite,
    $defaultStyles;

    // Set the $plugin_name and $cpt_name vars to desired names, examples below. Set the file names to reflect the updated variable values.
    $plugin_name        =   'Simple CPT Plugin';   // Update this - always prefix e.g. Simple Staff. Correlates to file class-simple-cpt-plugin.php
    $cpt_name           =   'CPT Plugin';     // Update this to desired post type singular - e.g. Event. Correlates to file simple-cpt-plugin.php

    //  Define Globals
    $plugin_name        =   preg_replace("/\W/", "-", strtolower($plugin_name) );
    $prefix             =   preg_replace("/\W/", "_", strtolower($plugin_name) );
    $plugin_url         =   plugin_dir_url( __FILE__ );
    $plugin_path        =   plugin_dir_path( __FILE__ );
    $plugin_basename    =   plugin_basename( __FILE__ );

    $rewriteUrl         =   '';
    $cpt_tax            =   '';

    //  Set globals if constants not defined
    $cpt_name           = defined( strtoupper($prefix).'_NAME' ) ? constant( strtoupper($prefix) . '_NAME' ) : $cpt_name;
    $cpt_slug           = preg_replace("/\W/", "-", strtolower($cpt_name) );

    $cpt_plural         = $cpt_name .'s';
    $cpt_tax            = defined( strtoupper($prefix).'_TAX' ) ? constant( strtoupper($prefix) . '_TAX' ) : $cpt_tax;

    $heirarchial        = true;
    $heirarchial        = defined( strtoupper($prefix).'_HIERARCHIAL' ) ? constant( strtoupper($prefix) . '_HIERARCHIAL' ) : $heirarchial;

    $has_archive        = true;

    $has_archive        = defined( strtoupper($prefix).'_ARCHIVE' ) ? constant( strtoupper($prefix) . '_ARCHIVE' ) : $has_archive;

    $rewriteUrl         = defined( strtoupper($prefix).'_REWRITE_URL' ) ? constant( strtoupper($prefix) . '_REWRITE_URL' ) : $rewriteUrl;


    //  Rewrite checking values and serializing rewrite array
    $fields             = array( 'slug' );
    $str                = "$rewriteUrl";
    $rewrite            = ( $rewriteUrl != 'false' ) ? serialize(array_combine( $fields, explode ( ", ", $str ) )) : 'false';

    //  Load class
    require_once( $plugin_path . 'class-'. $plugin_name .'.php' );
}