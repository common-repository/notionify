<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Notionify
 * @subpackage Notionify/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Notionify
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      Notionify_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected  $version ;
    /**
     * Define the core functionality of the plugin. Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and the public-facing side of the site.
     * @since    1.0.0
     */
    public function __construct()
    {
        
        if ( defined( 'NOTIONIFY_VERSION' ) ) {
            $this->version = NOTIONIFY_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        
        $this->plugin_name = 'notionify';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load the required dependencies for this plugin. Include the following files that make up the plugin:
     * - Notionify_Loader. Orchestrates the hooks of the plugin.
     * - Notionify_i18n. Defines internationalization functionality.
     * - Notionify_Admin. Defines all hooks for the admin area.
     * - Notionify_Public. Defines all hooks for the public side of the site.
     * Create an instance of the loader which will be used to register the hooks with WordPress.
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        # The class responsible for orchestrating the actions and filters of the core plugin.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-notionify-loader.php';
        # The class responsible for defining internationalization functionality of the plugin.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-notionify-i18n.php';
        # The class responsible for defining all actions that occur in the admin area.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-notionify-admin.php';
        # The class responsible for defining all actions that occur in the public-facing side of the site.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-notionify-public.php';
        #
        $this->loader = new Notionify_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     * Uses the Notionify_i18n class in order to set the domain and to register the hook with WordPress.
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Notionify_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        # admin class init
        $plugin_admin = new Notionify_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'init', $plugin_admin, 'notionify_customPostType' );
        # Creating Notion custom Post type
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        # enqueue style and script
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        # enqueue script
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'notionify_admin_menu' );
        # notion admin menu page
        $this->loader->add_filter(
            'plugin_action_links',
            $plugin_admin,
            'notionify_action_link',
            10,
            2
        );
        # plugin action links
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'notionify_notices' );
        # notion admin notice
        $this->loader->add_action( 'admin_post_notionify_savePost', $plugin_admin, 'notionify_savePost' );
        # notion save integrators settings
        $this->loader->add_action( 'admin_post_notionify_saveAPIkey', $plugin_admin, 'notionify_saveAPIkey' );
        # notion save API key settings
        # events
        $this->loader->add_action(
            'user_register',
            $plugin_admin,
            'notionify_wordpress_newUser',
            300,
            1
        );
        # New User Event [user_register]
        $this->loader->add_action(
            'profile_update',
            $plugin_admin,
            'notionify_wordpress_profileUpdate',
            300,
            2
        );
        # Update User Event [profile_update]
        $this->loader->add_action(
            'delete_user',
            $plugin_admin,
            'notionify_wordpress_deleteUser',
            300,
            1
        );
        # Delete User Event [delete_user]
        $this->loader->add_action(
            'wp_login',
            $plugin_admin,
            'notionify_wordpress_userLogin',
            300,
            2
        );
        # User Logged In  [wp_login]
        $this->loader->add_action(
            'clear_auth_cookie',
            $plugin_admin,
            'notionify_wordpress_userLogout',
            300,
            1
        );
        # User Logged Out [wp_logout]
        $this->loader->add_action(
            'save_post',
            $plugin_admin,
            'notionify_wordpress_post',
            300,
            3
        );
        # Wordpress Post  || Fires once a post has been saved. || 3 param 1.post_id 2.post 3.updates
        $this->loader->add_action(
            'comment_post',
            $plugin_admin,
            'notionify_wordpress_comment',
            300,
            3
        );
        # Wordpress comment_post  || Fires once a comment_post has been saved TO DB.
        $this->loader->add_action(
            'edit_comment',
            $plugin_admin,
            'notionify_wordpress_edit_comment',
            300,
            2
        );
        # Wordpress comment_post  || Fires once a comment_post has been saved TO DB.
        $this->loader->add_action(
            'transition_post_status',
            $plugin_admin,
            'notionify_woocommerce_product',
            300,
            3
        );
        # WooCommerce  Product save_post_product
        $this->loader->add_action(
            'woocommerce_order_status_changed',
            $plugin_admin,
            'notionify_woocommerce_order_status_changed',
            300,
            3
        );
        # Woocommerce Order Status Changed
        $this->loader->add_action(
            'woocommerce_new_order',
            $plugin_admin,
            'notionify_woocommerce_new_order_admin',
            300,
            1
        );
        # WooCommerce New Order
        $this->loader->add_action(
            'woocommerce_thankyou',
            $plugin_admin,
            'notionify_woocommerce_new_order_checkout',
            300,
            1
        );
        # WooCommerce New Order
        $this->loader->add_action(
            'wpcf7_before_send_mail',
            $plugin_admin,
            'notionify_cf7_submission',
            300,
            1
        );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Notionify_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     * @since     1.0.0
     * @return    Notionify_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}