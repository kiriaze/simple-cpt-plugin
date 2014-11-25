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



// Example of setting constants within theme - Note: must be outside after_setup_theme()
// define( 'SIMPLE_EXAMPLE_NAME', 'Events' );
// define( 'SIMPLE_EXAMPLE_HIERARCHIAL', true );
// define( 'SIMPLE_EXAMPLE_ARCHIVE', true );
// define( 'SIMPLE_EXAMPLE_REWRITE_URL', 'concerts' );



// Setup ACF
// Check if plugin is activated, if not set up lite version included with simple or fallback to plugin acf
if( ! class_exists('Acf') ) {
    define( 'ACF_LITE' , true );
    if ( file_exists( get_template_directory() . '/lib/functions/advanced-custom-fields/acf.php' ) ) {
        include_once( get_template_directory() . '/lib/functions/advanced-custom-fields/acf.php' );
    } else {
        include_once( plugin_dir_path(__DIR__) . '/advanced-custom-fields/acf.php' );
    }
}

//  Wrapped in after_setup_theme
add_action('after_setup_theme', 'simple_CPT Plugin_init', 12);
function simple_CPT Plugin_init(){

    global $plugin_name, $prefix, $plugin_url, $plugin_path, $plugin_basename, $cpt_slug, $cpt_name, $cpt_plural, $cpt_tax, $heirarchial, $has_archive, $rewrite, $defaultStyles;

    //  Define Globals
    $plugin_name        =   'Simple CPT Plugin';   // change this - always prefix e.g. Simple Staff
    $cpt_name           =   'CPT Plugin';     // change this to post type singular - e.g. Event
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