<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Notionify
 * Plugin URI:        https://https://wordpress.org/plugins/notionify
 * Description:       Notion + WordPress + WooCommerce
 * Version:           1.0.0
 * Author:            javmah
 * Author URI:        https://https://profiles.wordpress.org/javmah/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       notionify
 * Domain Path:       /languages
*/
# If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
# freemius Starts

if ( function_exists( 'notionify_fs' ) ) {
    notionify_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'notionify_fs' ) ) {
        // Create a helper function for easy SDK access.
        function notionify_fs()
        {
            global  $notionify_fs ;
            #
            
            if ( !isset( $notionify_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                $notionify_fs = fs_dynamic_init( array(
                    'id'             => '12095',
                    'slug'           => 'notionify',
                    'premium_slug'   => 'notionify-professional',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_36f5976d0d0a60c2f0911c0c7f161',
                    'is_premium'     => false,
                    'premium_suffix' => 'Professional',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'menu'           => array(
                    'slug'       => 'notionify',
                    'first-path' => 'admin.php?page=notionify',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $notionify_fs;
        }
        
        // Init Freemius.
        notionify_fs();
        // Signal that SDK was initiated.
        do_action( 'notionify_fs_loaded' );
    }
    
    # My code starts from here.
    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define( 'NOTIONIFY_VERSION', '1.0.0' );
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-notionify-activator.php
     */
    function activate_notionify()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-notionify-activator.php';
        Notionify_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-notionify-deactivator.php
     */
    function deactivate_notionify()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-notionify-deactivator.php';
        Notionify_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'activate_notionify' );
    register_deactivation_hook( __FILE__, 'deactivate_notionify' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-notionify.php';
    /**
     * Begins execution of the plugin.
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     * @since    1.0.0
     */
    function run_notionify()
    {
        $plugin = new Notionify();
        $plugin->run();
    }
    
    run_notionify();
}
