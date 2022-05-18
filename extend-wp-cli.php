<?php
/*
* @package extend_wp_cli
*/
/**
 * Plugin Name:       Pantheon System's Extend WP-CLI Plugin
 * Plugin URI:        
 * Description:       Extend WP-CLI with custom package installs
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pantheon System's Inc.
 * Author URI:        
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       extendwpcli
 **/



defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class extendWPCLI {

    function __construct() {
        add_action( 'init', array($this, 'register_style'));
        add_action( 'init', array($this, 'enqueue_style'));
    } 
    
    //add menu options
    function ewpc_menu() {
        add_submenu_page( 'tools.php', 'Pantheon Systems Extend WP-CLI', 'Extend WP-CLI Options', 'manage_options', 'extendWPCLI', array($this,'ewpc_page') );
    }

    function ewpc_page() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        echo '<div class="wrap">';
        echo '<p> <h1 align="center">=^_^= <br> Pantheon Systems Extend WP-CLI /h1><br><h2>Info Page</h2></p>';
        echo '</div>';
    }

  //register stylesheet on plugin initialization
  function register_style(){
      wp_register_style( 'test_style', plugins_url('/css/test_style.css', __FILE__), false, '1.0.0', 'all'); 
    }
  //enqueue stylesheet on initialization 
  function enqueue_style(){
      wp_enqueue_style( 'test_style' ); #todo, make this for plugin admin and posts only 
    }


  //activation
  function activate() {
      
      //include plugin stylesheet
      //Ensure the $wp_rewrite global is loaded
      global $wp_rewrite;
      //Call flush_rules() as a method of the $wp_rewrite object
      $wp_rewrite->flush_rules( false );
  }

  //deactivation
  function deactivate() {
      //Ensure the $wp_rewrite global is loaded
      global $wp_rewrite;
      //Call flush_rules() as a method of the $wp_rewrite object
      $wp_rewrite->flush_rules( false );
  }


   public static function ewpc_add_package( $attributes ){
    if ( ! class_exists( 'WP_CLI' ) ) {
        return;
    }
    
    $wpcli_profile_autoloader = dirname( __FILE__ ) . '/vendor/autoload.php';
    if ( file_exists( $wpcli_profile_autoloader ) ) {
        require_once $wpcli_profile_autoloader;
    }
    
    WP_CLI::add_command( 'profile', 'WP_CLI\Profile\Command' );
    }
}

if ( class_exists( 'extendWPCLI' )) {
  $extendWPCLI = new extendWPCLI();
}

register_activation_hook( __FILE__, array( $extendWPCLI, 'activate'));
register_deactivation_hook( __FILE__, array( $extendWPCLI, 'deactivate'));
add_action( 'admin_menu', array($extendWPCLI, 'ewpc_menu'));