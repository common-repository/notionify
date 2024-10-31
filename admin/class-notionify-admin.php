<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Notionify
 * @subpackage Notionify/admin
 * @author     javmah <jaedmah@gmail.com>
*/
class Notionify_Admin
{
    /**
     * The ID of this plugin.
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     * @since    1.0.0
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     * @access   private
     * @var      string    $version    The current version of this plugin.
     * @since    1.0.0
     */
    private  $version ;
    /**
     * Events list.
     * @access   Public
     * @var      array    $events    Events list.
     * @since    1.0.0
     */
    public  $events = array() ;
    /**
     * Events Children titles.
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     * @since    1.0.0
     */
    public  $eventsAndTitles = array() ;
    /**
     * Notion database and Pages
     * @access   Public
     * @var      array    $eventsAndTitles    Events list.
     * @since    1.0.0
     */
    public  $nationDbPages = array() ;
    /**
     * Initialize the class and set its properties.
     * @param      string    $plugin_name   The name of this plugin.
     * @param      string    $version    	The version of this plugin.
     * @since      1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        # +++++++++++++++++++++++++++++++ Below code should Fix ++++++++++++++++++++++++++++++++++++++++++++
        # There are come Default function for This, So Why Custom  Thing
        # Set date || Current Date
        $date_format = get_option( 'date_format' );
        $this->Date = ( $date_format ? current_time( $date_format ) : current_time( 'd/m/Y' ) );
        # Current Time
        $time_format = get_option( 'time_format' );
        $this->Time = ( $date_format ? current_time( $time_format ) : current_time( 'g:i a' ) );
        # Active Plugins, Checking Active And Inactive Plugin
        $this->active_plugins = get_option( 'active_plugins' );
        # ++++++++++++++++++++++++++++++ below Code also Should Change as you see Custom Order Status will not Display +++++++++++++++++++
        # WooCommerce order Statuses
        
        if ( function_exists( "wc_get_order_statuses" ) ) {
            $woo_order_statuses = wc_get_order_statuses();
            # for Woocommerce New orders;
            $this->wooCommerceOrderStatuses['wc-new_order'] = 'WooCommerce New Checkout Page Order';
            # For Default Status
            foreach ( $woo_order_statuses as $key => $value ) {
                $this->wooCommerceOrderStatuses[$key] = 'WooCommerce ' . $value;
            }
        } else {
            # If Function didn't exist do it
            $this->wooCommerceOrderStatuses = array(
                "wc-new_order"  => "WooCommerce New Checkout Page Order",
                "wc-pending"    => "WooCommerce Order Pending payment",
                "wc-processing" => "WooCommerce Order Processing",
                "wc-on-hold"    => "WooCommerce Order On-hold",
                "wc-completed"  => "WooCommerce Order Completed",
                "wc-cancelled"  => "WooCommerce Order Cancelled",
                "wc-refunded"   => "WooCommerce Order Refunded",
                "wc-failed"     => "WooCommerce Order Failed",
            );
        }
        
        # User Starts
        # wordpress user events
        $wordpressUserEvents = array(
            "wordpress_newUser"           => 'WordPress New User',
            "wordpress_UserProfileUpdate" => 'WordPress User Profile Update',
            "wordpress_deleteUser"        => 'WordPress Delete User',
            "wordpress_userLogin"         => 'WordPress User Login',
            "wordpress_userLogout"        => 'WordPress User Logout',
        );
        # Inserting User Events to All Events
        $this->events += $wordpressUserEvents;
        # New Code for User
        foreach ( $wordpressUserEvents as $key => $value ) {
            # This is For Paid User
            $this->eventsAndTitles[$key] = array(
                "userID"               => "User ID",
                "userName"             => "User Name",
                "firstName"            => "User First Name",
                "lastName"             => "User Last Name",
                "nickname"             => "User Nickname",
                "displayName"          => "User Display Name",
                "eventName"            => "Event Name",
                "description"          => "User Description",
                "userEmail"            => "User Email",
                "userRegistrationDate" => "User Registration Date",
                "userRole"             => "User Role",
                "userPassword"         => "User Password",
                "site_time"            => "Site Time",
                "site_date"            => "Site Date",
                "user_date_year"       => "Year of the Date",
                "user_date_month"      => "Month of the Date",
                "user_date_date"       => "Date of the Date",
                "user_date_time"       => "Time of the Date",
            );
            
            if ( $key == 'wordpress_userLogin' ) {
                $this->eventsAndTitles[$key]["userLogin"] = "Logged in ";
                $this->eventsAndTitles[$key]["userLoginTime"] = "Logged in Time";
                $this->eventsAndTitles[$key]["userLoginDate"] = "Logged in Date";
            }
            
            
            if ( $key == 'wordpress_userLogout' ) {
                $this->eventsAndTitles[$key]["userLogout"] = "User Logout";
                $this->eventsAndTitles[$key]["userLogoutTime"] = "Logout Time";
                $this->eventsAndTitles[$key]["userLogoutDate"] = "Logout Date";
            }
        
        }
        # Post Event array
        $wordpressPostEvents = array(
            'wordpress_newPost'    => 'WordPress New Post',
            'wordpress_editPost'   => 'WordPress Edit Post',
            'wordpress_deletePost' => 'WordPress Delete Post',
            'wordpress_page'       => 'WordPress Page',
        );
        # Inserting WP Post Events to All Events
        $this->events += $wordpressPostEvents;
        # post loop
        foreach ( $wordpressPostEvents as $key => $value ) {
            # setting wordpress_page profile update events
            if ( $key != 'wordpress_page' ) {
                # This is For Free User
                $this->eventsAndTitles[$key] = array(
                    "postID"              => "Post ID",
                    "post_authorID"       => "Post Author ID",
                    "authorUserName"      => "Post Author User name",
                    "authorDisplayName"   => "Post Author Display Name",
                    "authorEmail"         => "Post Author Email",
                    "authorRole"          => "Post Author Role",
                    "post_title"          => "Post Title",
                    "post_date"           => "Post Date",
                    "post_date_gmt"       => "Post Date GMT",
                    "site_time"           => "Site Time",
                    "site_date"           => "Site Date",
                    "post_date_year"      => "Post on Year",
                    "post_date_month"     => "Post on Month",
                    "post_date_date"      => "Post on Date",
                    "post_date_time"      => "Post on Time",
                    "post_content"        => "Post Content",
                    "post_excerpt"        => "Post Excerpt",
                    "post_status"         => "Post Status",
                    "eventName"           => "Event Name",
                    "comment_status"      => "Comment Status",
                    "ping_status"         => "Ping Status",
                    "post_password"       => "Post Password",
                    "post_name"           => "Post Name",
                    "to_ping"             => "To Ping",
                    "pinged"              => "Pinged",
                    "post_modified"       => "Post Modified Date",
                    "post_modified_gmt"   => "Post Modified GMT",
                    "post_modified_year"  => "Post modified Year",
                    "post_modified_month" => "Post modified Month",
                    "post_modified_date"  => "Post modified Date",
                    "post_modified_time"  => "Post modified Time",
                    "post_parent"         => "Post Parent",
                    "guid"                => "Guid",
                    "menu_order"          => "Menu Order",
                    "post_type"           => "Post Type",
                    "post_mime_type"      => "Post Mime Type",
                    "comment_count"       => "Comment Count",
                    "filter"              => "Filter",
                );
            }
            if ( $key == 'wordpress_page' ) {
                $this->eventsAndTitles[$key] = array(
                    "postID"              => "Page ID",
                    "post_authorID"       => "Page Author ID",
                    "authorUserName"      => "Page Author User name",
                    "authorDisplayName"   => "Page Author Display Name",
                    "authorEmail"         => "Page Author Email",
                    "authorRole"          => "Page Author Role",
                    "post_title"          => "Page Title",
                    "post_date"           => "Page Date",
                    "post_date_gmt"       => "Page Date GMT",
                    "site_time"           => "Site Time",
                    "site_date"           => "Site Date",
                    "post_date_year"      => "Page on Year",
                    "post_date_month"     => "Page on Month",
                    "post_date_date"      => "Page on Date",
                    "post_date_time"      => "Page on Time",
                    "post_content"        => "Page Content",
                    "post_excerpt"        => "Page Excerpt",
                    "post_status"         => "Page Status",
                    "eventName"           => "Event Name",
                    "comment_status"      => "Comment Status",
                    "ping_status"         => "Ping Status",
                    "post_password"       => "Page Password",
                    "post_name"           => "Page Name",
                    "to_ping"             => "To Ping",
                    "pinged"              => "Pinged",
                    "post_modified"       => "Page Modified",
                    "post_modified_gmt"   => "Page Modified GMT",
                    "post_modified_year"  => "Page modified Year",
                    "post_modified_month" => "Page modified Month",
                    "post_modified_date"  => "Page modified Date",
                    "post_modified_time"  => "Page modified Time",
                    "post_parent"         => "Page Parent",
                    "guid"                => "Guid",
                    "menu_order"          => "Menu Order",
                    "post_type"           => "Page Type",
                    "post_mime_type"      => "Page Mime Type",
                    "comment_count"       => "Comment Count",
                    "filter"              => "Filter",
                );
            }
        }
        # Loop Ends
        # Comment Starts
        $wordpressCommentEvents = array(
            'wordpress_comment'      => 'WordPress Comment',
            'wordpress_edit_comment' => 'WordPress Edit Comment',
        );
        # Inserting comment Events to All Events
        $this->events += $wordpressCommentEvents;
        # setting wordpress comments events
        foreach ( $wordpressCommentEvents as $key => $value ) {
            #
            $this->eventsAndTitles[$key] = array(
                "comment_ID"           => "Comment ID",
                "comment_post_ID"      => "Comment Post ID",
                "comment_author"       => "Comment Author",
                "comment_author_email" => "Comment Author Email",
                "comment_author_url"   => "Comment Author Url",
                "comment_content"      => "Comment Content",
                "comment_type"         => "Comment Type",
                "user_ID"              => "Comment User ID",
                "comment_author_IP"    => "Comment Author IP",
                "comment_agent"        => "Comment Agent",
                "comment_date"         => "Comment Date",
                "comment_date_gmt"     => "Comment Date GMT",
                "site_time"            => "Site Time",
                "site_date"            => "Site Date",
                "year_of_comment"      => "Year of the Comment",
                "month_of_comment"     => "Month of the Comment",
                "date_of_comment"      => "Date of the Comment",
                "time_of_comment"      => "Time of the Comment",
                "filtered"             => "Filtered",
                "comment_approved"     => "Comment Approved",
            );
        }
        # Woocommerce
        
        if ( in_array( 'woocommerce/woocommerce.php', $this->active_plugins ) ) {
            # Woo product  Starts
            # WooCommerce Product Event Array
            $wooCommerceProductEvents = array(
                'wc-new_product'    => 'WooCommerce New Product',
                'wc-edit_product'   => 'WooCommerce Update Product',
                'wc-delete_product' => 'WooCommerce Delete Product',
            );
            # Inserting WooCommerce product Events to All Events
            $this->events += $wooCommerceProductEvents;
            # WooCommerce Products
            foreach ( $wooCommerceProductEvents as $key => $value ) {
                # Default fields
                $this->eventsAndTitles[$key] = array(
                    "productID"          => "Product ID",
                    "type"               => "Product Type",
                    "post_type"          => "Post Type",
                    "name"               => "Name",
                    "slug"               => "Slug",
                    "date_created"       => "Date created",
                    "date_modified"      => "Date modified",
                    "weight"             => "Weight",
                    "length"             => "Length",
                    "width"              => "Width",
                    "height"             => "Height",
                    "attributes"         => "Attributes",
                    "default_attributes" => "Default attributes",
                    "category_ids"       => "Category ids",
                    "tag_ids"            => "Tag ids",
                    "image_id"           => "Image id",
                    "gallery_image_ids"  => "Gallery image ids",
                    "site_time"          => "Site Time",
                    "site_date"          => "Site Date",
                );
            }
            # Inserting WooCommerce Order Events to All Events
            $this->events += $this->wooCommerceOrderStatuses;
            # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            #(1) Product Meta s
            #(2) Product Info
            #(3) Product Details
            #(4) Empty Product Place Holder
            # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            # WooCommerce Orders
            foreach ( $this->wooCommerceOrderStatuses as $key => $value ) {
                # Default fields
                $this->eventsAndTitles[$key] = array(
                    "orderID"             => "Order ID",
                    "billing_first_name"  => "Billing first name",
                    "billing_last_name"   => "Billing last name",
                    "billing_company"     => "Billing company",
                    "billing_address_1"   => "Billing address 1",
                    "billing_address_2"   => "Billing address 2",
                    "billing_city"        => "Billing city",
                    "billing_state"       => "Billing state",
                    "billing_postcode"    => "Billing postcode",
                    "shipping_first_name" => "Shipping first name",
                    "shipping_last_name"  => "Shipping last name",
                    "shipping_company"    => "Shipping company",
                    "shipping_address_1"  => "Shipping address 1",
                    "shipping_address_2"  => "Shipping address 2",
                    "shipping_city"       => "Shipping city",
                    "shipping_state"      => "Shipping state",
                    "shipping_postcode"   => "Shipping postcode",
                    "site_time"           => "Site Time",
                    "site_date"           => "Site Date",
                    "status"              => "Status",
                    "eventName"           => "Event name",
                );
            }
        }
        
        # Below are Contact forms
        # Contact Form 7
        $cf7 = $this->notionify_cf7_forms_and_fields();
        
        if ( $cf7[0] ) {
            foreach ( $cf7[1] as $form_id => $form_name ) {
                $this->events[$form_id] = $form_name;
            }
            foreach ( $cf7[2] as $form_id => $fields_array ) {
                $this->eventsAndTitles[$form_id] = $fields_array;
            }
        }
    
    }
    
    /**
     * Register the stylesheets for the admin area.
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/notionify-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        # ONLY FOR NOTION PAGE
        // STARTS
        
        if ( get_current_screen()->id == 'toplevel_page_notionify' ) {
            # getting Notion api key
            $notionifyNotionAPIkey = get_option( 'notionifyNotionAPIkey' );
            #getting notion database and pages
            $dbPages = $this->notionify_notionDatabases( $notionifyNotionAPIkey );
            # inserting data to the global Notion $nationDbPages variable
            $this->nationDbPages = $dbPages;
            // wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/notionify-admin.js', array( 'jquery' ), $this->version, false );
            # including vue.js
            wp_enqueue_script(
                'vue3239',
                plugin_dir_url( __FILE__ ) . 'js/vue3239.js',
                array(),
                NULL,
                FALSE
            );
            # sending data to frontend new
            
            if ( isset( $_GET["action"], $_GET["id"] ) and $_GET["action"] == 'edit' ) {
                # get the integration from database
                $integrationData = get_post( sanitize_text_field( $_GET["id"] ) );
                # check and balance
                
                if ( $integrationData and is_object( $integrationData ) ) {
                    # getting title fields
                    $titleFields = get_post_meta( $integrationData->ID, 'titleFields', true );
                    # sensitize title vale
                    if ( !empty($titleFields) and is_array( $titleFields ) ) {
                        foreach ( $titleFields as $key => $value ) {
                            $titleFields[$key] = sanitize_text_field( $value );
                        }
                    }
                    # getting body fields
                    $bodyFields = get_post_meta( $integrationData->ID, 'bodyFields', true );
                    # sensitize body vale
                    if ( !empty($bodyFields) and is_array( $bodyFields ) ) {
                        foreach ( $bodyFields as $key => $value ) {
                            $bodyFields[$key] = sanitize_text_field( $value );
                        }
                    }
                    # localize data
                    wp_localize_script( 'vue3239', 'notionifyFrontend', array(
                        "id"              => ( isset( $integrationData->ID ) ? sanitize_text_field( $integrationData->ID ) : "" ),
                        "currentPage"     => "edit",
                        "selectedEvent"   => ( isset( $integrationData->post_title ) ? sanitize_text_field( $integrationData->post_title ) : "" ),
                        "selectedDbPage"  => ( isset( $integrationData->post_content ) ? sanitize_text_field( $integrationData->post_content ) : "" ),
                        "titleFields"     => json_encode( $titleFields ),
                        "bodyFields"      => json_encode( $bodyFields ),
                        "listStyle"       => ( isset( $integrationData->post_excerpt ) ? sanitize_text_field( $integrationData->post_excerpt ) : "" ),
                        "dbPages"         => ( $dbPages[0] ? $dbPages[1] : [] ),
                        "events"          => $this->events,
                        "eventsAndTitles" => $this->eventsAndTitles,
                    ) );
                } else {
                    # Keep the log
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "101",
                        "ERROR: action or ID  is not set."
                    );
                    # Redirect
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=false_no_integration' ) );
                }
            
            }
            
            # sending data to frontend new
            if ( isset( $_GET["action"] ) and $_GET["action"] == 'new' ) {
                wp_localize_script( 'vue3239', 'notionifyFrontend', array(
                    "currentPage"     => "new",
                    "dbPages"         => ( $dbPages[0] ? $dbPages[1] : [] ),
                    "events"          => $this->events,
                    "eventsAndTitles" => $this->eventsAndTitles,
                ) );
            }
        }
        
        // ENDS
    }
    
    #*********************************************************************************************************************************
    #***************************************************** TEST AND DEBUG ************************************************************
    #*********************************************************************************************************************************
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function notionify_notices()
    {
        
        if ( isset( get_current_screen()->base ) and get_current_screen()->base == 'toplevel_page_notionify' ) {
            # getting Notion api key
            $notionifyNotionAPIkey = get_option( 'notionifyNotionAPIkey' );
            # if API key is not set display message
            
            if ( !$notionifyNotionAPIkey ) {
                echo  "<div class='notice notice-warning inline'>" ;
                echo  "<p><b>INFORMATION</b> : Please configure <b> Notion </b> API before creating new integration. Get <code><b><a href=" . admin_url( 'admin.php?page=notionify&action=APIkeyHelp' ) . " style='text-decoration: none;'> step-by-step</a></b></code> help. This plugin will not work without <b> Notion </b> API </p>" ;
                echo  "</div>" ;
            }
        
        }
        
        echo  "<pre>" ;
        echo  "</pre>" ;
    }
    
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function notionify_admin_menu()
    {
        add_menu_page(
            __( 'Notionify', 'notionify' ),
            __( 'Notionify', 'notionify' ),
            'manage_options',
            'notionify',
            array( $this, 'notionify_requestDispatcher' ),
            'dashicons-awards',
            50
        );
    }
    
    /**
     * Adding a settings link at Plugin page after activate deactivate.
     * @since    1.0.0
     */
    public function notionify_action_link( $links_array, $plugin_file_name )
    {
        # check and balance
        if ( $plugin_file_name == 'notion/notion.php' ) {
            $links_array[] = '<a href="' . esc_url( get_admin_url( null, 'admin.php?page=notionify' ) ) . '">Settings</a>';
        }
        #
        return $links_array;
    }
    
    /**
     * This Function will create Custom post type for saving notionify integration and  save notionify log
     * @since    1.0.0
     */
    public function notionify_customPostType()
    {
        register_post_type( 'notionify' );
        // for notionify integration
        register_post_type( 'notionifylog' );
        // for notionify log
    }
    
    /**
     * Register the JavaScript for the admin area.
     * @since  1.0.0
     */
    public function notionify_requestDispatcher()
    {
        # getting url variables
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list' );
        $id = ( isset( $_GET['id'] ) ? intval( sanitize_text_field( $_GET['id'] ) ) : 0 );
        # routing to the Pages
        switch ( $action ) {
            case 'new':
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notionify-add-edit.php';
                break;
            case 'edit':
                
                if ( empty($id) or !is_numeric( $id ) ) {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "ERROR: no edit ID."
                    );
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=edit_fail' ) );
                } else {
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notionify-add-edit.php';
                }
                
                break;
            case 'delete':
                
                if ( empty($id) or !is_numeric( $id ) ) {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "ERROR: no delete ID."
                    );
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=delete_fail' ) );
                } else {
                    # Delete the Integration
                    wp_delete_post( $id );
                    # Deleting Post Meta
                    delete_post_meta( $id, "titleFields" );
                    delete_post_meta( $id, "bodyFields" );
                    #log
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: integration is deleted. ID : " . $id
                    );
                    # Redirect
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=delete_success' ) );
                }
                
                break;
            case 'deleteAPIkey':
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: API key is deleted."
                );
                # Delete site option
                delete_option( 'notionifyNotionAPIkey' );
                # Redirect
                wp_redirect( admin_url( '/admin.php?page=notionify&rms=deleteAPIkey_success' ) );
                break;
            case 'status':
                
                if ( empty($id) or !is_numeric( $id ) ) {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "102",
                        "ERROR: status didn't changed no ID."
                    );
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=status_fail' ) );
                } else {
                    
                    if ( get_post( $id )->post_status == 'publish' ) {
                        $custom_post = array(
                            'ID'          => $id,
                            'post_status' => 'pending',
                        );
                    } else {
                        $custom_post = array(
                            'ID'          => $id,
                            'post_status' => 'publish',
                        );
                    }
                    
                    # log
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: status changed ID : " . $id
                    );
                    # Redirect
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=status_success' ) );
                }
                
                # redirect
                ( wp_update_post( $custom_post ) ? wp_redirect( admin_url( '/admin.php?page=notionify&rms=status_success' ) ) : wp_redirect( admin_url( '/admin.php?page=notionify&rms=status_fail' ) ) );
                break;
            case 'testRun':
                
                if ( isset( $id ) and !empty($id) ) {
                    $this->notionify_createTestPageInNotion( $id );
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=success' ) );
                } else {
                    wp_redirect( admin_url( '/admin.php?page=notionify&rms=failed' ) );
                }
                
                # redirect
                break;
            case 'APIkeyHelp':
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notionify-admin-api-key.php';
                break;
            case 'log':
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notionify-admin-log.php';
                break;
            case 'logStatus':
                $notionifyLogStatus = get_option( 'notionifyLogStatus' );
                
                if ( $notionifyLogStatus ) {
                    # enable log || ON
                    delete_option( "notionifyLogStatus" );
                } else {
                    # keeping log is Off  || OFF
                    update_option( "notionifyLogStatus", TRUE );
                }
                
                #
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: log status is changed."
                );
                # redirect
                wp_redirect( admin_url( '/admin.php?page=notionify&action=log&rms=success' ) );
                break;
            case 'deleteLog':
                # Delete the logs
                $notionifyLogs = get_posts( array(
                    'post_type'      => 'notionifylog',
                    'posts_per_page' => -1,
                ) );
                # Counting Current log
                foreach ( $notionifyLogs as $key => $log ) {
                    wp_delete_post( $log->ID, TRUE );
                }
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: all log are deleted."
                );
                # Then redirect to the Log page Admin with Different URL
                wp_redirect( admin_url( 'admin.php?page=notionify&action=log' ) );
                break;
            default:
                # including default view page AKA main view Page
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notionify-admin-display.php';
                # updating Integration List Transient; IMPORTANT
                $this->notionify_updateIntegrationListTransient();
                # DELETING LOGS AFTER 100
                $notionifyLog = get_posts( array(
                    'post_type'      => 'notionifylog',
                    'posts_per_page' => -1,
                ) );
                # Counting notionify log
                if ( count( $notionifyLog ) > 100 ) {
                    foreach ( $notionifyLog as $key => $log ) {
                        if ( $key > 100 ) {
                            wp_delete_post( $log->ID, TRUE );
                        }
                    }
                }
                unset( $notionifyLog );
                # Delete log after 100; ends
                break;
        }
    }
    
    //  'Bearer secret_wztYiGpdAKwukxY9H1Gz74XYClNBUnTLOopuqTQWm3S'
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function notionify_savePost()
    {
        
        if ( !isset( $_POST['requestFrom'] ) or empty($_POST['requestFrom']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "103",
                "ERROR: requestFrom not set or empty."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_status' ) );
        }
        
        # if notionify database or page section is empty then create one
        
        if ( !isset( $_POST['selectedDbPage'] ) or empty($_POST['selectedDbPage']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "104",
                "ERROR: notion database not set or empty."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_dbPages' ) );
        }
        
        # checking notionify database  validity
        
        if ( !isset( $this->nationDbPages[1][$_POST['selectedDbPage']], $this->nationDbPages[0] ) or $this->nationDbPages[0] != TRUE ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "105",
                "ERROR: notion database id is not valid or API is not working."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=invalid_notionify_database_id' ) );
        }
        
        
        if ( !isset( $_POST['event'] ) or empty($_POST['event']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "106",
                "ERROR: event not set or empty."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_event' ) );
        }
        
        
        if ( !isset( $_POST['titleFields'] ) or empty($_POST['titleFields']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "107",
                "ERROR: titleFields not set or empty."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_titleFields' ) );
        } else {
            # sanitize and escape post array
            foreach ( $_POST['titleFields'] as $key => $value ) {
                $titleFields[sanitize_text_field( $key )] = sanitize_text_field( $value );
            }
        }
        
        
        if ( !isset( $_POST['bodyFields'] ) or empty($_POST['bodyFields']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "108",
                "ERROR: bodyFields is not set or empty."
            );
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_bodyFields' ) );
        } else {
            # sanitize and escape post array
            foreach ( $_POST['bodyFields'] as $key => $value ) {
                $bodyFields[sanitize_text_field( $key )] = sanitize_text_field( $value );
            }
        }
        
        
        if ( !isset( $_POST['listStyle'] ) or empty($_POST['listStyle']) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "109",
                "ERROR: listStyle is not set or empty."
            );
            # setting default list style
            $_POST['listStyle'] = 'normal';
        }
        
        if ( !isset( $_POST['activationStatus'] ) ) {
            wp_redirect( admin_url( '/admin.php?page=notionify&action=new&rms=fail_activationStatus' ) );
        }
        #=======================================================================================================================
        # empty post ID  holder
        $post_id = '';
        # Save new integration
        
        if ( isset(
            $_POST['ID'],
            $_POST['selectedDbPage'],
            $_POST['event'],
            $_POST['listStyle']
        ) and $_POST['requestFrom'] == "new" ) {
            # Preparing Post array for DB insert
            $customPost = array(
                'ID'           => "",
                'post_content' => sanitize_text_field( $_POST['selectedDbPage'] ),
                'post_title'   => sanitize_text_field( $_POST['event'] ),
                'post_status'  => ( $_POST['activationStatus'] ? 'publish' : 'pending' ),
                'post_excerpt' => sanitize_text_field( $_POST['listStyle'] ),
                'post_name'    => '',
                'post_type'    => 'notionify',
                'menu_order'   => '',
                'post_parent'  => '',
                'meta_input'   => array(
                'titleFields' => $titleFields,
                'bodyFields'  => $bodyFields,
            ),
            );
            # Inserting New integration custom Post type
            $post_id = wp_insert_post( $customPost );
        }
        
        # Save edited Integration
        
        if ( isset(
            $_POST['ID'],
            $_POST['selectedDbPage'],
            $_POST['event'],
            $_POST['listStyle']
        ) and ($_POST['requestFrom'] == "edit" and !empty($_POST['ID'])) ) {
            # Preparing Post array for status Change
            $customPost = array(
                'ID'           => sanitize_text_field( $_POST['ID'] ),
                'post_content' => sanitize_text_field( $_POST['selectedDbPage'] ),
                'post_title'   => sanitize_text_field( $_POST['event'] ),
                'post_status'  => ( $_POST['activationStatus'] ? 'publish' : 'pending' ),
                'post_excerpt' => sanitize_text_field( $_POST['listStyle'] ),
                'post_name'    => '',
                'post_type'    => 'notionify',
                'menu_order'   => '',
                'post_parent'  => '',
                'meta_input'   => array(
                'titleFields' => $titleFields,
                'bodyFields'  => $bodyFields,
            ),
            );
            # Updating Custom Post Type
            $post_id = wp_update_post( $customPost );
        }
        
        # unseating post array
        unset( $_POST );
        unset( $customPost );
        # updating Integration List Transient
        $this->notionify_updateIntegrationListTransient();
        # if There is a Post Id , That Means Post is success fully saved
        
        if ( $post_id ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: new Notion integration is saved."
            );
            # Redirecting
            wp_redirect( admin_url( '/admin.php?page=notionify&rms=success' ) );
        } else {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "110",
                "ERROR: notion integration is not saved."
            );
            # redirecting
            wp_redirect( admin_url( '/admin.php?page=notionify&rms=failed' ) );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     * @since    1.0.0
     */
    public function notionify_saveAPIkey()
    {
        
        if ( !isset( $_POST['notionifyNotionAPIkey'] ) or empty($_POST['notionifyNotionAPIkey']) ) {
            # keep log
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "111",
                "ERROR: notionAPIkey is not set or empty."
            );
            # redirect
            wp_redirect( admin_url( '/admin.php?page=notionify&rms=fail_save_apiKey' ) );
        }
        
        # test Api key by sample request to see validity
        update_option( 'notionifyNotionAPIkey', sanitize_text_field( $_POST['notionifyNotionAPIkey'] ) );
        # log
        $this->notionifyLog(
            get_class( $this ),
            __METHOD__,
            "200",
            "SUCCESS: Notion API key saved."
        );
        # redirect as Good
        wp_redirect( admin_url( '/admin.php?page=notionify&rms=success' ) );
    }
    
    /**
     * This Function will return [wordPress Pages] Meta keys.
     * @return     array   This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_pages_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT DISTINCT( {$wpdb->postmeta}.meta_key ) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'page' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post type page.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Posts] Meta keys.
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_posts_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT  DISTINCT( {$wpdb->postmeta}.meta_key ) \n\t\t\t\t  \tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'post' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_users_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT  DISTINCT( {$wpdb->usermeta}.meta_key ) FROM {$wpdb->usermeta} ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of users.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [wordPress Users] Meta keys.
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_comments_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT  DISTINCT( {$wpdb->commentmeta}.meta_key ) FROM {$wpdb->commentmeta} ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist on comment meta.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [WooCommerce Order] Meta keys.
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_wooCommerce_order_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT  DISTINCT( {$wpdb->postmeta}.meta_key ) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'shop_order' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the post type WooCommerce Order.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     * This Function will return [WooCommerce product] Meta keys.
     * @return     array    This array has two vale First one is Bool and Second one is meta key array.
     * @since      1.0.0
     */
    public function notionify_wooCommerce_product_metaKeys()
    {
        # Global Db object
        global  $wpdb ;
        # Query
        $query = "SELECT  DISTINCT( {$wpdb->postmeta}.meta_key ) \n\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\tWHERE {$wpdb->posts}.post_type = 'product' \n\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
        # execute Query
        $meta_keys = $wpdb->get_col( $query );
        # return Depend on the Query result
        
        if ( empty($meta_keys) ) {
            return array( FALSE, 'ERROR: Empty! No Meta key exist of the Post type WooCommerce Product.' );
        } else {
            return array( TRUE, $meta_keys );
        }
    
    }
    
    /**
     *  Contact form 7,  form  fields 
     *  @since    1.0.0
     */
    public function notionify_cf7_forms_and_fields()
    {
        # is there CF7
        if ( !in_array( 'contact-form-7/wp-contact-form-7.php', get_option( "active_plugins" ) ) or !$this->notionify_dbTableExists( 'posts' ) ) {
            return array( FALSE, "ERROR:  Contact form 7 is Not installed or DB Table is Not Exist." );
        }
        $cf7forms = array();
        $fieldsArray = array();
        global  $wpdb ;
        $cf7Forms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE {$wpdb->posts}.post_type = 'wpcf7_contact_form' AND {$wpdb->postmeta}.meta_key = '_form'" );
        # Looping the Forms
        foreach ( $cf7Forms as $form ) {
            # Inserting Fields 																			# Loop the Custom Post ;
            $cf7forms["cf7_" . $form->ID] = "Cf7 - " . $form->post_title;
            # Getting Fields Meta
            $formFieldsMeta = get_post_meta( $form->ID, '_form', true );
            # Replacing Quoted string
            $formFieldsMeta = preg_replace( '/"((?:""|[^"])*)"/', "", $formFieldsMeta );
            # Removing : txt
            $formFieldsMeta = preg_replace( '/\\w+:\\w+/', "", $formFieldsMeta );
            # Removing submit
            $formFieldsMeta = preg_replace( '/\\bsubmit\\b/', "", $formFieldsMeta );
            # if txt is Not empty
            
            if ( !empty($formFieldsMeta) ) {
                # Getting Only [] txt
                $bracketTxt = array();
                # Separating bracketed txt and inserting theme to  $bracketTxt array
                preg_match_all( '/\\[(.*?)\\]/', $formFieldsMeta, $bracketTxt );
                # Check is set & not empty
                if ( isset( $bracketTxt[1] ) && !empty($bracketTxt[1]) ) {
                    # Field Loop
                    foreach ( $bracketTxt[1] as $txt ) {
                        # Divide the TXT after every space
                        $tmpArr = explode( ' ', $txt );
                        # taking Only the second Element of every array || first one is Field type || Second One is Field key
                        $singleItem = array_slice( $tmpArr, 1, 1 );
                        # Remove Submit Empty Array || important i am removing submit
                        if ( isset( $singleItem[0] ) && !empty($singleItem[0]) ) {
                            $fieldsArray["cf7_" . $form->ID][$singleItem[0]] = $singleItem[0];
                        }
                    }
                }
            }
        
        }
        # Loop ends
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['notionify_submitted_time'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_time'] = "notion Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['notionify_submitted_date'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_date'] = "notion Form submitted date";
                }
            }
        }
        return array( TRUE, $cf7forms, $fieldsArray );
    }
    
    /**
     * CF7 Form Submission Event || its a HOOK  callback function of Contact form 7 form
     * Contact form 7 is a Disgusting Code || Noting is good of this Plugin || 
     * @param    array     $form_data     dataArray
     * @since    1.0.0
     */
    public function notionify_cf7_submission( $contact_form )
    {
        $id = $contact_form->id();
        $submission = WPCF7_Submission::get_instance();
        $eventData = $submission->get_posted_data();
        # if There is a integration on this Form Submission
        
        if ( !empty($id) and $this->notionify_inTheIntegrations( 'cf7_' . $id ) ) {
            # extra fields values
            // Freemius
            # Site date and time
            $eventData['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            $eventData['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            
            if ( isset( $id ) && !empty($id) ) {
                # Calling Event Boss
                $r = $this->notionify_createNotionPage( 'cf7_' . $id, $eventData );
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "716",
                    "ERROR: Contact form 7 Form Submitted But No Form ID !"
                );
            }
        
        }
    
    }
    
    /**
     *  Ninja  form  fields 
     *  @param     int     $user_id          userID
     *  @param     int     $old_user_data    username
     *  @since     1.0.0
     */
    public function notionify_ninja_forms_and_fields()
    {
        # if ninja form is installed
        if ( !in_array( 'ninja-forms/ninja-forms.php', get_option( "active_plugins" ) ) or !$this->notionify_dbTableExists( 'nf3_forms' ) ) {
            return array( FALSE, "ERROR: Ninja form 7 is Not Installed " );
        }
        global  $wpdb ;
        $FormArray = array();
        # Empty Array for Value Holder
        $fieldsArray = array();
        $ninjaForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nf3_forms", ARRAY_A );
        foreach ( $ninjaForms as $form ) {
            $FormArray["ninja_" . $form["id"]] = "Ninja - " . $form["title"];
            $ninjaFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nf3_fields where parent_id = '" . $form["id"] . "'", ARRAY_A );
            foreach ( $ninjaFields as $field ) {
                $field_list = array( "textbox", "textarea", "number" );
                // same variable
                $field_list = array(
                    "textbox",
                    "textarea",
                    "email",
                    "phone",
                    "number",
                    "checkbox",
                    "date",
                    "listmultiselect",
                    "listradio",
                    "listselect",
                    "liststate",
                    "starrating",
                    "hidden",
                    "address",
                    "city",
                    "zip"
                );
                if ( in_array( $field["type"], $field_list ) ) {
                    $fieldsArray["ninja_" . $form["id"]][$field["key"]] = $field["label"];
                }
            }
        }
        # Adding extra fields || like Date and Time || Add more in future
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['notionify_submitted_time'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_time'] = "notion Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['notionify_submitted_date'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_date'] = "notion Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     * ninja after saved entry to DB || its a HOOK  callback function of ninja form
     * @param    array     $form_data     dataArray
     * @since    1.0.0
     */
    public function notionify_ninja_forms_after_submission( $form_data )
    {
        # if There is a integration on this Form Submission
        
        if ( isset( $form_data["form_id"] ) and $this->notionify_inTheIntegrations( 'ninja_' . $form_data["form_id"] ) ) {
            # Empty array holder
            $data = array();
            # Looping the Fields
            foreach ( $form_data["fields"] as $field ) {
                $data[$field["key"]] = $field["value"];
            }
            # extra fields value
            # Site date and time
            $data['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            $data['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            # Check And Balance
            
            if ( !empty($form_data) and isset( $form_data["form_id"] ) ) {
                # Action
                $r = $this->notionify_createNotionPage( 'ninja_' . $form_data["form_id"], $data );
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "717",
                    "ERROR: ninja Form entries are empty Or form_id is empty!"
                );
            }
        
        }
    
    }
    
    /**
     *  formidable form  fields 
     *  @since    1.0.0
     */
    public function notionify_formidable_forms_and_fields()
    {
        # check and balance
        if ( !in_array( 'formidable/formidable.php', get_option( "active_plugins" ) ) or !$this->notionify_dbTableExists( 'frm_forms' ) ) {
            return array( FALSE, "ERROR: formidable form  is Not Installed OR DB table is Not Exist." );
        }
        # Global database object
        global  $wpdb ;
        $FormArray = array();
        # Empty Array for Value Holder
        $fieldsArray = array();
        # Empty Array for Holder
        $frmForms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_forms" );
        # Getting  Forms Database
        foreach ( $frmForms as $form ) {
            $FormArray["frm_" . $form->id] = "Formidable - " . $form->name;
            # Inserting ARRAY title
            # Getting Meta Fields || maybe i don't Know ;-D
            $fields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_fields WHERE form_id = " . $form->id . " ORDER BY field_order" );
            foreach ( $fields as $field ) {
                # Default fields
                $field_list = array( "text", "textarea", "number" );
                //  Same thing just for FM
                $field_list = array(
                    "text",
                    "textarea",
                    "number",
                    "email",
                    "phone",
                    "hidden",
                    "url",
                    "user_id",
                    "select",
                    "radio",
                    "checkbox",
                    "rte",
                    "date",
                    "time",
                    "star",
                    "range",
                    "password",
                    "address"
                );
                if ( in_array( $field->type, $field_list ) ) {
                    $fieldsArray["frm_" . $form->id][$field->id] = $field->name;
                }
            }
        }
        # Adding extra fields || like Date and Time || Add more in future
        //  FM Block
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['notionify_submitted_time'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_time'] = "notion Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['notionify_submitted_date'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_date'] = "notion Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     * formidable after saved entry to DB || its a HOOK  callback function of formidable form
     * @param    array    $entry_id    Which platform call this function 
     * @param    array    $form_id     eventName 
     * @since    1.0.0
     */
    public function notionify_formidable_after_save( $entry_id, $form_id )
    {
        # if There is a integration on this Form Submission
        if ( !empty($form_id) and $this->notionify_inTheIntegrations( 'frm_' . $form_id ) ) {
            # Check to see database table exist or not
            
            if ( $this->notionify_dbTableExists( "frm_item_metas" ) ) {
                # Code
                $eventData = array();
                global  $wpdb ;
                $entrees = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}frm_item_metas WHERE item_id = " . $entry_id . " ORDER BY field_id" );
                foreach ( $entrees as $entre ) {
                    # Convert serialize string to array; So that options are comma separated;
                    $rt = @unserialize( $entre->meta_value );
                    
                    if ( $rt ) {
                        $eventData[$entre->field_id] = $rt;
                    } else {
                        $eventData[$entre->field_id] = $entre->meta_value;
                    }
                
                }
                # extra fields value
                # Site date and time
                $eventData['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
                $eventData['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
                # Check And Balance
                
                if ( !empty($entry_id) ) {
                    # Action
                    $r = $this->notionify_createNotionPage( 'frm_' . $form_id, $eventData );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "718",
                        "ERROR: formidable Form entries ID is empty!"
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "719",
                    "ERROR: formidable frm_item_metas table is Not Exist!"
                );
            }
        
        }
    }
    
    /**
     *  wpforms fields 
     *  @since    1.0.0
     */
    public function notionify_wpforms_forms_and_fields()
    {
        # Check and Balance
        if ( !count( array_intersect( get_option( "active_plugins" ), array( 'wpforms-lite/wpforms.php', 'wpforms/wpforms.php' ) ) ) or !$this->notionify_dbTableExists( 'posts' ) ) {
            return array( FALSE, "ERROR: wp form is Not Installed OR DB Table is Not Exist." );
        }
        # Empty holder
        $FormArray = array();
        $fieldsArray = array();
        # Getting Data from Database
        global  $wpdb ;
        $wpforms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpforms'  " );
        foreach ( $wpforms as $wpform ) {
            $FormArray["wpforms_" . $wpform->ID] = "WPforms - " . $wpform->post_title;
            $post_content = json_decode( $wpform->post_content );
            foreach ( $post_content->fields as $field ) {
                # Default fields
                $field_list = array( "name", "text", "textarea" );
                # freemius
                $field_list = array(
                    "name",
                    "text",
                    "email",
                    "textarea",
                    "number",
                    "number-slider",
                    "phone",
                    "address",
                    "date-time",
                    "url",
                    "password",
                    "hidden",
                    "rating",
                    "checkbox",
                    "radio",
                    "select",
                    "payment-single",
                    "payment-checkbox",
                    "payment-total",
                    "stripe-credit-card"
                );
                if ( in_array( $field->type, $field_list ) ) {
                    $fieldsArray["wpforms_" . $wpform->ID][$field->id] = $field->label;
                }
            }
        }
        // FM block
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['notionify_submitted_time'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_time'] = "notion Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['notionify_submitted_date'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_date'] = "notion Form submitted date";
                }
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     * wpforms Submit Action Handler, its a HOOK  callback function of WP form
     * @param      array    $fields    		Which platform call this function 
     * @param      array    $entry     		eventName 
     * @param      array    $form_data     	dataArray
     * @since      1.0.0
     */
    public function notionify_wpforms_process( $fields, $eventData, $form_data )
    {
        # if There is a integration on this Form Submission
        
        if ( isset( $form_data["id"] ) and $this->notionify_inTheIntegrations( 'wpforms_' . $form_data["id"] ) ) {
            # extra fields value
            # Site date and time
            $eventData["fields"]['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            $eventData["fields"]['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            # Check And Balance
            
            if ( !empty($eventData) and !empty($form_data["id"]) ) {
                # Action
                $r = $this->notionify_createNotionPage( 'wpforms_' . $form_data["id"], $eventData["fields"] );
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "720",
                    "ERROR: wpforms Form entries are empty Or form_id is empty!"
                );
            }
        
        }
    
    }
    
    /**
     * weforms forms_after_submission 
     * @param    string   $entry_id   		entry_id;
     * @param    string   $form_id   		form_id;
     * @param    string   $page_id     		page_id;
     * @param    array    $form_settings    form_data;
     * @since    1.0.0
     */
    public function notionify_weforms_entry_submission(
        $entry_id,
        $form_id,
        $page_id,
        $form_settings
    )
    {
        # if There is a integration on this Form Submission
        if ( !empty($form_id) and $this->notionify_inTheIntegrations( 'we_' . $form_id ) ) {
            # Check if frm_item_metas table exists or not
            
            if ( $this->notionify_dbTableExists( "frm_item_metas" ) ) {
                # code
                $dataArray = array();
                global  $wpdb ;
                $entrees = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}weforms_entrymeta WHERE weforms_entry_id = " . $entry_id . " ORDER BY meta_id DESC" );
                foreach ( $entrees as $entre ) {
                    $dataArray[$entre->meta_key] = $entre->meta_value;
                }
                # extra fields value
                # Site date and time
                $dataArray['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
                $dataArray['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
                # Check And Balance
                
                if ( !empty($entry_id) and !empty($form_id) ) {
                    # Action
                    $r = $this->notionify_createNotionPage( 'we_' . $form_id, $dataArray );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "721",
                        "ERROR: weforms Form entries are empty Or form_id is empty!"
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "722",
                    "ERROR: weform frm_item_metas db table is Not Exist!"
                );
            }
        
        }
    }
    
    /**
     *  WE forms fields 
     *  @since    1.0.0
     */
    public function notionify_weforms_forms_and_fields()
    {
        # check and balance
        if ( !in_array( 'weforms/weforms.php', get_option( "active_plugins" ) ) or !$this->notionify_dbTableExists( 'posts' ) ) {
            return array( FALSE, "ERROR: weForms  is Not Active  OR DB is not exist." );
        }
        # empty holders
        $FormArray = array();
        $fieldsArray = array();
        $fieldTypeArray = array();
        # Global database object
        global  $wpdb ;
        $weforms = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_contact_form' " );
        $weFields = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_type = 'wpuf_input' " );
        # create the list
        foreach ( $weforms as $weform ) {
            if ( isset( $weform->ID, $weform->post_title ) ) {
                $FormArray["we_" . $weform->ID] = 'weForms - ' . $weform->post_title;
            }
        }
        foreach ( $weFields as $Field ) {
            foreach ( $FormArray as $weformID => $weformTitle ) {
                
                if ( isset( $Field->post_parent ) and $weformID == "we_" . $Field->post_parent ) {
                    $content_arr = unserialize( $Field->post_content );
                    
                    if ( isset( $content_arr['name'], $content_arr['label'], $content_arr['template'] ) ) {
                        $fieldsArray[$weformID][$content_arr['name']] = $content_arr['label'];
                        $fieldTypeArray[$weformID][$content_arr['name']] = $content_arr['template'];
                    }
                
                }
            
            }
        }
        # Adding extra fields || like Date and Time || Add more in future
        // FM block
        if ( !empty($fieldsArray) ) {
            foreach ( $fieldsArray as $formID => $formFieldsArray ) {
                # For Time
                if ( !isset( $formFieldsArray['notionify_submitted_time'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_time'] = "notion Form submitted  time";
                }
                # for Date
                if ( !isset( $formFieldsArray['notionify_submitted_date'] ) ) {
                    $fieldsArray[$formID]['notionify_submitted_date'] = "notion Form submitted date";
                }
            }
        }
        return array(
            TRUE,
            $FormArray,
            $fieldsArray,
            $fieldTypeArray
        );
    }
    
    /**
     * 	Under Construction 
     *  gravity forms fields 
     *  @since    1.0.0
     */
    public function notionify_gravity_forms_and_fields()
    {
        # check to see active
        if ( !in_array( 'gravityforms/gravityforms.php', get_option( "active_plugins" ) ) ) {
            return array( FALSE, "ERROR: gravity forms  is Not Active  OR DB is not exist." );
        }
        #
        if ( !class_exists( 'GFAPI' ) ) {
            return array( FALSE, "ERROR: gravityForms class GFAPI is not exist." );
        }
        $gravityForms = GFAPI::get_forms();
        #check and Test
        
        if ( !empty($gravityForms) ) {
            # Empty array holder Declared
            $FormArray = array();
            $fieldsArray = array();
            $fieldTypeArray = array();
            # New Code Loop
            foreach ( $gravityForms as $form ) {
                $FormArray["gravity_" . $form["id"]] = "Gravity - " . $form["title"];
                # Form Fields || Check fields are set or Not
                if ( isset( $form['fields'] ) and is_array( $form['fields'] ) ) {
                    foreach ( $form['fields'] as $field ) {
                        
                        if ( empty($field['inputs']) ) {
                            # if there is no subfields
                            $fieldsArray["gravity_" . $form["id"]][$field["id"]] = $field["label"];
                            $fieldTypeArray["gravity_" . $form["id"]][$field["id"]] = $field["type"];
                        } else {
                            # Looping Subfields
                            foreach ( $field["inputs"] as $subField ) {
                                $fieldsArray["gravity_" . $form["id"]][$subField["id"]] = $field["label"] . ' (' . $subField["label"] . ')';
                                $fieldTypeArray["gravity_" . $form["id"]][$subField["id"]] = $field["type"];
                            }
                        }
                    
                    }
                }
            }
        } else {
            return array(
                FALSE,
                array(),
                array(),
                array()
            );
        }
        
        return array(
            TRUE,
            $FormArray,
            $fieldsArray,
            $fieldTypeArray
        );
    }
    
    /**
     * gravityForms gform_after_submission 
     * @param    array   $entry     All the Entries with Some Extra;
     * @param    array   $formObj   Submitted form Object ;
     * @since    1.0.0
     */
    public function notionify_gravityForms_after_submission( $entry, $formObj )
    {
        # if There is a integration on this Form Submission
        if ( isset( $entry['form_id'] ) and $this->notionify_inTheIntegrations( 'gravity_' . $entry['form_id'] ) ) {
            # extra fields value
            # Calling the Event Boss
            
            if ( !empty($entry) and isset( $entry['form_id'] ) ) {
                # Site date and time
                $entry['notionify_submitted_date'] = ( isset( $this->Date ) ? $this->Date : '' );
                $entry['notionify_submitted_time'] = ( isset( $this->Time ) ? $this->Time : '' );
                # Action
                $r = $this->notionify_createNotionPage( 'gravity_' . $entry['form_id'], $entry );
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "723",
                    "ERROR: gravity Form entries are empty Or form_id is empty!"
                );
            }
        
        }
    }
    
    /**
     * fluent forms fields 
     * @since      1.0.0
     * @return     array   First one is CPS and Second one is CPT's Field source.
     */
    public function notionify_fluent_forms_and_fields()
    {
        # check to see plugin is active or not
        if ( !in_array( 'fluentform/fluentform.php', get_option( "active_plugins" ) ) ) {
            return array( FALSE, "ERROR: fluentform form  is Not Installed OR no integration Exist" );
        }
        $FormArray = array();
        $fieldsArray = array();
        $fluentForms = fluentFormApi( 'forms' )->forms( array(
            'sort_by' => 'DESC',
        ), TRUE );
        # Check and Balance
        if ( isset( $fluentForms['data'] ) and !empty($fluentForms['data']) ) {
            foreach ( $fluentForms['data'] as $form ) {
                
                if ( isset( $form->id, $form->title, $form->form_fields ) ) {
                    $FormArray["fluent_" . $form->id] = "fluent - " . $form->title;
                    # getting Fields
                    $fields = fluentFormApi( 'forms' )->form( $formId = $form->id )->fields();
                    # Check and Balance
                    if ( !empty($fields) and isset( $fields['fields'] ) ) {
                        foreach ( $fields['fields'] as $field ) {
                            if ( isset( $field['index'], $field['attributes']['name'] ) ) {
                                $fieldsArray["fluent_" . $form->id][$field['attributes']['name']] = ( (isset( $field['attributes']['placeholder'] ) and !empty($field['attributes']['placeholder'])) ? $field['attributes']['placeholder'] : $field['attributes']['name'] );
                            }
                        }
                    }
                }
                
                # Date And Time
                $fieldsArray["fluent_" . $form->id]['notionify_submitted_time'] = "notionify Form submitted  time";
                $fieldsArray["fluent_" . $form->id]['notionify_submitted_date'] = "notionify Form submitted date";
            }
        }
        return array( TRUE, $FormArray, $fieldsArray );
    }
    
    /**
     * This Function will All Custom Post types wit associative  data 
     * This function will check global $wp_post_types;  OR  get_post_types() if not found or not exist then it will return false and error message  
     * @return     array   First one is CPS and Second one is CPT's Field source.
     * @since      1.0.0
     */
    public function notionify_allCptEvents()
    {
        # declaring global post type
        global  $wp_post_types ;
        # Custom post type empty holder
        $customPostTypes = array();
        # remove array
        $removeArray = array(
            "post",
            "page",
            "wpforms",
            "acf-field-group",
            "acf-field",
            "product",
            "product_variation",
            "shop_order",
            "shop_order_refund"
        );
        # if global $wp_post_types; is set and not empty
        
        if ( isset( $wp_post_types ) and !empty($wp_post_types) ) {
            foreach ( $wp_post_types as $postKey => $PostValue ) {
                # if Post type is Not Default
                if ( isset( $PostValue->_builtin ) and !$PostValue->_builtin ) {
                    # Look is it on remove list, if not insert
                    if ( !in_array( $postKey, $removeArray ) ) {
                        # Pre populate $cpts array
                        
                        if ( isset( $PostValue->label ) and !empty($PostValue->label) ) {
                            $customPostTypes[$postKey] = $PostValue->label . " (" . $postKey . ")";
                        } else {
                            $customPostTypes[$postKey] = $postKey;
                        }
                    
                    }
                }
            }
            #  if get_post_types() function is exist and not empty
        } elseif ( function_exists( 'get_post_types' ) and !empty(get_post_types( array(
            '_builtin' => false,
        ), 'names', 'and' )) ) {
            foreach ( get_post_types( array(
                '_builtin' => false,
            ), 'names', 'and' ) as $key => $value ) {
                if ( !in_array( $key, $removeArray ) ) {
                    $customPostTypes[$key] = $value;
                }
            }
        } else {
            # Keeping Log
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "112",
                'ERROR: global wp_post_types or  get_post_types() are empty.'
            );
            # return
            return array( FALSE, "ERROR: global wp_post_types or  get_post_types() are empty." );
        }
        
        # check $ customPostTypes is empty or not
        
        if ( !empty($customPostTypes) ) {
            # Looping for Creating Extra Events Like Update and Delete
            foreach ( $customPostTypes as $key => $value ) {
                $cptEvents['cpt_new_' . $key] = 'CPT New ' . $value;
                $cptEvents['cpt_update_' . $key] = 'CPT Update ' . $value;
                $cptEvents['cpt_delete_' . $key] = 'CPT Delete ' . $value;
            }
            # Now setting default Event data Source Fields; Those events data source  are common in all WordPress Post type
            $eventDataFields = array(
                "postID"            => "ID",
                "post_authorID"     => "post author_ID",
                "authorUserName"    => "author User Name",
                "authorDisplayName" => "author Display Name",
                "authorEmail"       => "author Email",
                "authorRole"        => "author Role",
                "post_title"        => "post title",
                "post_date"         => "post date",
                "post_date_gmt"     => "post date gmt",
                "site_time"         => "Site Time",
                "site_date"         => "Site Date",
                "post_content"      => "post content",
                "post_excerpt"      => "post excerpt",
                "post_status"       => "post status",
                "comment_status"    => "comment status",
                "ping_status"       => "ping status",
                "post_password"     => "post password",
                "post_name"         => "post name",
                "to_ping"           => "to ping",
                "pinged"            => "pinged",
                "post_modified"     => "post modified date",
                "post_modified_gmt" => "post modified date GMT",
                "post_parent"       => "post parent",
                "guid"              => "guid",
                "menu_order"        => "menu order",
                "post_type"         => "post type",
                "post_mime_type"    => "post mime type",
                "comment_count"     => "comment count",
                "filter"            => "filter",
            );
            # Global Db object
            global  $wpdb ;
            # Query for getting Meta keys
            $query = "SELECT  DISTINCT( {$wpdb->postmeta}.meta_key ) \n\t\t\t\t\t\tFROM {$wpdb->posts} \n\t\t\t\t\t\tLEFT JOIN {$wpdb->postmeta} \n\t\t\t\t\t\tON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id \n\t\t\t\t\t\tWHERE {$wpdb->posts}.post_type != 'post' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'page' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'shop_order_refund' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type   != 'product_variation' \n\t\t\t\t\t\tAND {$wpdb->posts}.post_type \t != 'wpforms' \n\t\t\t\t\t\tAND {$wpdb->postmeta}.meta_key != '' ";
            # execute Query for getting the Post meta key it will use for event data source
            $meta_keys = $wpdb->get_col( $query );
            # Inserting Meta keys to Main $eventDataFields Array;
            
            if ( !empty($meta_keys) and is_array( $meta_keys ) ) {
                foreach ( $meta_keys as $value ) {
                    if ( !isset( $eventDataFields[$value] ) ) {
                        $eventDataFields[$value] = "CPT Meta " . $value;
                    }
                }
            } else {
                # insert to the log but don't return
                # ERROR:  Meta keys  are empty;
            }
            
            # Everything seems ok, Now send the CPT events and Related Data source;
            return array(
                TRUE,
                $customPostTypes,
                $cptEvents,
                $eventDataFields,
                $meta_keys
            );
        } else {
            return array( FALSE, "ERROR: custom Post type Array is Empty." );
        }
    
    }
    
    /**
     * database table and columns 
     * @return     array   First one is CPS and Second one is CPT's Field source.
     * @since      1.0.0
     */
    public function notionify_database_tables_and_columns()
    {
        # Empty holder
        $tables = array();
        $tableColumn = array();
        # Global database instance
        global  $wpdb ;
        # Database Query
        $result = $wpdb->get_results( "SELECT DISTINCT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '" . $wpdb->dbname . "'", ARRAY_A );
        # if not empty
        
        if ( isset( $result ) and !empty($result) ) {
            # Looping the Table and Columns
            foreach ( $result as $row_array ) {
                $tables[$row_array['TABLE_NAME']] = "Database Table " . $row_array['TABLE_NAME'];
                $tableColumn[$row_array['TABLE_NAME']][$row_array['COLUMN_NAME']] = "Column " . $row_array['COLUMN_NAME'];
            }
            # return true and data
            return array( TRUE, $tables, $tableColumn );
        } else {
            # return false and empty array()
            return array( FALSE, array(), array() );
        }
    
    }
    
    /**
     * Database new row integration, When New row append it will send that to Google Sheet 
     * @since    1.0.0
     */
    public function notionify_database_data_update()
    {
        # DB delta
        global  $wpdb ;
        # getting database integrations
        $dbIntegrations = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts, {$wpdb->prefix}postmeta WHERE {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id AND {$wpdb->prefix}posts.post_type = 'notionify' AND {$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}postmeta.meta_value = 'database'", ARRAY_A );
        # looping integration platforms
        foreach ( $dbIntegrations as $integrationID => $integrationArray ) {
            # getting database table name
            $databaseTable = get_post_meta( $integrationArray['ID'], 'DataSourceID', TRUE );
            # database table name
            
            if ( empty($databaseTable) ) {
                # keeping log
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "726",
                    "ERROR: database table name is empty."
                );
                # done
                return array( FALSE, "ERROR: database table name is empty." );
            }
            
            #getting inserted table Primary key Name
            $table_info = $wpdb->get_results( "SHOW KEYS FROM " . $databaseTable . " WHERE Key_name = 'PRIMARY' ", ARRAY_A );
            # getting last Primary key
            $last_row_primary_key = get_post_meta( $integrationArray['ID'], 'last_row_primary_key', TRUE );
            #  getting the database table last row
            $lastRow = $wpdb->get_results( " SELECT * FROM " . $databaseTable . " ORDER BY " . $table_info[0]['Column_name'] . " DESC LIMIT 1 ", ARRAY_A );
            # primary key name
            $primaryKeyValue = ( isset( $table_info[0]['Column_name'], $lastRow[0][$table_info[0]['Column_name']] ) ? $lastRow[0][$table_info[0]['Column_name']] : "" );
            # primary key value
            
            if ( empty($primaryKeyValue) ) {
                # error Primary key is not present in last row
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "727",
                    "ERROR: Primary key is not present in last row"
                );
                # done
                return array( FALSE, "ERROR: Primary key is not present in last row." );
            }
            
            # last row is sent or not
            
            if ( $primaryKeyValue != $last_row_primary_key ) {
                # Send data to the G sheet
                $r = $this->notionify_createNotionPage( $databaseTable, $lastRow[0] );
                # set last row primary key so that it won't send again
                update_post_meta( $integrationArray['ID'], 'last_row_primary_key', $primaryKeyValue );
            }
        
        }
    }
    
    /**
     * This is a Helper function to check Table is Exist or Not 
     * If DB table Exist it will return True if Not it will return False
     * @param     string    $data_source    Which platform call this function s
     * @since     1.0.0
     */
    public function notionify_dbTableExists( $tableName = NULL, $prefix = FALSE )
    {
        if ( empty($tableName) ) {
            return FALSE;
        }
        # database Global object
        global  $wpdb ;
        # testing Prefix
        
        if ( $prefix ) {
            $r = $wpdb->get_results( "SHOW TABLES LIKE '" . $tableName . "'" );
        } else {
            $r = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $tableName . "'" );
        }
        
        
        if ( $r ) {
            return TRUE;
        } else {
            return FALSE;
        }
    
    }
    
    /**
     * this will get all the database From Notion 
     * @since    1.0.0
     */
    public function notionify_notionDatabases( $APItoken = "" )
    {
        # Checking api Token
        if ( empty($APItoken) ) {
            return array( FALSE, "ERROR: notion API token is empty." );
        }
        # Requesting to the notion server
        $r = wp_remote_request( 'https://api.notion.com/v1/search', array(
            'method'  => 'POST',
            'headers' => array(
            'Authorization'  => 'Bearer ' . $APItoken,
            'Content-Type'   => 'application/json',
            'Notion-Version' => '2022-06-28',
        ),
            'body'    => '{
									"query": "",
									"sort" : {
										"direction" : "ascending",
										"timestamp" : "last_edited_time"
									}
								}',
        ) );
        # remote request check and balance || error handling
        
        if ( !is_wp_error( $r ) and isset( $r['response'], $r['response']['code'], $r['body'] ) and $r['response']['code'] == 200 ) {
            # converting JSON data to ARRAY
            $dataArray = @json_decode( $r['body'], TRUE );
            # Check and Balance
            
            if ( isset( $dataArray['results'] ) and !empty($dataArray['results']) ) {
                
                if ( empty($dataArray['results']) ) {
                    return array( FALSE, "ERROR: data array result is empty." );
                } else {
                    # Empty holders
                    $databases = array();
                    # Looping the returns AND Getting only databases
                    foreach ( $dataArray['results'] as $key => $value ) {
                        
                        if ( isset( $value['object'], $value['id'], $value['title'][0]['plain_text'] ) and $value['object'] == 'database' ) {
                            $databases[$value['id']]['object'] = $value['object'];
                            $databases[$value['id']]['id'] = $value['id'];
                            $databases[$value['id']]['name'] = $value['title'][0]['plain_text'];
                        }
                    
                    }
                    # enjoy
                    return array( TRUE, $databases );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "113",
                    "ERROR : no result set or json_decode error."
                );
                return array( FALSE, "ERROR: no result set or json_decode error." );
            }
        
        } else {
            return array( FALSE, "ERROR: You didn't give API access to you notion database." );
        }
    
    }
    
    /**
     * this will get all the database From Notion 
     * @since    1.0.0
     */
    public function notionify_createTestPageInNotion( $ID = "" )
    {
        # Id check
        if ( empty($ID) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "114",
                "ERROR: notion integration ID is empty."
            );
        }
        # getting the notion Integrations from wp transient
        $integrations = get_transient( "notionifyIntegrations" );
        # integrations list check
        
        if ( empty($integrations) or !is_array( $integrations ) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "115",
                "ERROR: notion integrations is empty or not array."
            );
            return array( FALSE, "ERROR: notion integrations is empty or not array." );
        }
        
        # getting Notion api key from wp option
        $APItoken = get_option( 'notionifyNotionAPIkey' );
        # token check
        
        if ( empty($APItoken) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "116",
                "ERROR: notion API token is empty or not array."
            );
            return array( FALSE, "ERROR: notion API token is empty or not array." );
        }
        
        # looping the integrations
        foreach ( $integrations as $integrationArray ) {
            
            if ( isset( $integrationArray['ID'], $integrationArray['selectedDbPage'], $this->events[$integrationArray['event']] ) and $integrationArray['ID'] == $ID ) {
                # now send the request
                $r = wp_remote_request( 'https://api.notion.com/v1/pages', array(
                    'method'  => 'POST',
                    'headers' => array(
                    'Authorization'  => 'Bearer ' . $APItoken,
                    'Content-Type'   => 'application/json',
                    'Notion-Version' => '2022-06-28',
                ),
                    'body'    => '{
									"parent": { "database_id": "' . $integrationArray['selectedDbPage'] . '" },
									"properties": {
										"Name": {
											"title": [
												{
													"text": {
														"content":"' . $integrationArray['ID'] . ". " . $this->events[$integrationArray['event']] . ' integration. " 
													}
												}
											]
										}
									},
									"children": [
										{
											"object": "block",
											"type": "heading_2",
											"heading_2": {
												"rich_text": [{ "type": "text", "text": { "content": "Welcome to WordPress & Notion Integration Test run." }}]
											}
										},
										{
											"object": "block",
											"type": "paragraph",
											"paragraph": {
												"rich_text": [
													{
														"type": "text",
														"text": {
															"content": "Now you can easily connect your WordPress and its Plugin with Notion. Enjoy."
														},
														"annotations": {
															"bold": true,
															"italic": false,
															"strikethrough": false,
															"underline": false,
															"code": false,
															"color": "default"
														}
													}
												]
											}
										}
									]
								}',
                ) );
                # remote request check and balance || error handling
                
                if ( !is_wp_error( $r ) and isset( $r['response'], $r['response']['code'], $r['body'] ) and $r['response']['code'] == 200 ) {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: notion page test created. REQUEST BODY :::> " . $r['body']
                    );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "117",
                        "ERROR: notion test page is not created E:::> " . json_encode( $r )
                    );
                }
            
            }
        
        }
    }
    
    /**
     *  This Function will update WP notion Plugin Transient
     *  @param     int     $user_id     	  username
     *  @param     int     $old_user_data     username
     *  @since     1.0.0
     */
    public function notionify_updateIntegrationListTransient()
    {
        # Setting Empty Array
        $integrationsArray = array();
        # get all integration from database
        $listOfConnections = get_posts( array(
            'post_type'      => 'notionify',
            'post_status'    => array( 'publish', 'pending' ),
            'posts_per_page' => -1,
        ) );
        # integration loop starts
        foreach ( $listOfConnections as $key => $value ) {
            $integrationsArray[$key]["ID"] = $value->ID;
            $integrationsArray[$key]["selectedDbPage"] = $value->post_content;
            $integrationsArray[$key]["event"] = $value->post_title;
            $integrationsArray[$key]["titleFields"] = get_post_meta( $value->ID, 'titleFields', true );
            $integrationsArray[$key]["bodyFields"] = get_post_meta( $value->ID, 'bodyFields', true );
            $integrationsArray[$key]["listStyle"] = $value->post_excerpt;
            $integrationsArray[$key]["activationStatus"] = $value->post_status;
        }
        # updating the options cache
        set_transient( 'notionifyIntegrations', $integrationsArray );
    }
    
    /**
     * log function of this Plugin  
     * $this->notionifyLog(get_class( $this ), __METHOD__,"105", "ERROR: DataSourceID is empty.");
     * @return     array   First one is CPS and Second one is CPT's Field source.
     * @since      1.0.0
     */
    public function notionifyLog(
        $fileName = '',
        $functionName = '',
        $statusCode = '',
        $statusMessage = ''
    )
    {
        # getting notion log status
        $notionifyLogStatus = get_option( 'notionifyLogStatus' );
        # Acting on the status.
        if ( $notionifyLogStatus ) {
            return array( FALSE, "SUCCESS: Notion log is disabled." );
        }
        # Check and Balance
        if ( empty($statusCode) or empty($statusMessage) ) {
            return array( FALSE, "ERROR: status_code OR status_message is Empty." );
        }
        # Post Excerpt
        $postExcerpt = json_encode( array(
            "file_name"     => esc_sql( $fileName ),
            "function_name" => esc_sql( $functionName ),
        ) );
        # Inserting into the DB
        global  $wpdb ;
        $sql = "INSERT INTO {$wpdb->prefix}posts (post_content, post_title, post_excerpt, post_type) VALUES ( '" . esc_sql( $statusMessage ) . "','" . esc_sql( $statusCode ) . "','" . esc_sql( $postExcerpt ) . "', 'notionifylog' )";
        $results = $wpdb->get_results( $sql );
        # return coin
        return array( TRUE, "SUCCESS: Successfully inserted to the Log." );
    }
    
    #*********************************************************************************************************************************
    #**************************************************** EVENT CLASS STARTS *********************************************************
    #*********************************************************************************************************************************
    /**
     *  WordPress new User Registered  HOOK's callback function
     *  @param     int     $user_id     	  username
     *  @param     int     $old_user_data     username
     *  @since     1.0.0
     */
    public function notionify_wordpress_newUser( $user_id )
    {
        # if There is a integration on  new user
        if ( $this->notionify_inTheIntegrations( 'wordpress_newUser' ) ) {
            # if get_userdata() and get_user_meta() Functions are exist;
            
            if ( function_exists( 'get_userdata' ) and function_exists( 'get_user_meta' ) ) {
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "New User";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                #
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id ) {
                    $r = $this->notionify_createNotionPage( 'wordpress_newUser', $user_data );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "700",
                        "ERROR: wordpress_newUser fired but no User ID . " . json_encode( array( $user_id, $user_data ) )
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "701",
                    "notionifyLog: get_userdata or get_user_meta is not Exist"
                );
            }
        
        }
    }
    
    /**
     *  WordPress new User Profile Update HOOK's callback function
     *  @param     int     $user_id     		user ID
     *  @param     int     $old_user_data     	user Data
     *  @since     1.0.0
     */
    public function notionify_wordpress_profileUpdate( $user_id, $old_user_data )
    {
        # if There is a integration on User profile update
        if ( $this->notionify_inTheIntegrations( 'wordpress_UserProfileUpdate' ) ) {
            # if get_userdata() and get_user_meta() Functions are exist
            
            if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && !empty($user_id) ) {
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "Profile Update";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id && $user->ID ) {
                    $r = $this->notionify_createNotionPage( 'wordpress_UserProfileUpdate', $user_data );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "702",
                        "ERROR: wordpress_UserProfileUpdate fired but no User ID . " . json_encode( array( $user_id, $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "703",
                    "ERROR: get_userdata or get_user_meta or User id is not Exist"
                );
            }
        
        }
    }
    
    /**
     *  WordPress Delete User HOOK's callback function
     *  @param    int     $user_id     user ID
     *  @since    1.0.0
     */
    public function notionify_wordpress_deleteUser( $user_id )
    {
        # if There is a integration on Delete user
        if ( $this->notionify_inTheIntegrations( 'wordpress_deleteUser' ) ) {
            # if get_userdata() and get_user_meta() Functions are exist
            
            if ( function_exists( 'get_userdata' ) && function_exists( 'get_user_meta' ) && !empty($user_id) ) {
                # Empty Holder
                $user_data = array();
                $user = get_userdata( $user_id );
                $userMeta = get_user_meta( $user_id );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "Delete User";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                $user_data['userPassword'] = ( isset( $user->user_pass ) && !empty($user->user_pass) ? $user->user_pass : "" );
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                #
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user_id && $user->ID ) {
                    $r = $this->notionify_createNotionPage( 'wordpress_deleteUser', $user_data );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "704",
                        "ERROR: wordpress_deleteUser fired but no User ID . " . json_encode( array( $user_id, $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "705",
                    "ERROR: get_userdata or get_user_meta or user_id is not Exist"
                );
            }
        
        }
    }
    
    /**
     * User Logged in  HOOK's callback function
     * @param     int     $username     username
     * @param     int     $user     	user
     * @since     1.0.0
     */
    public function notionify_wordpress_userLogin( $username, $user )
    {
        # if There is a integration on user login
        if ( $this->notionify_inTheIntegrations( 'wordpress_userLogin' ) ) {
            # if get_user_meta() function and $user->ID exist
            
            if ( function_exists( 'get_user_meta' ) and !empty($user->ID) ) {
                # Pre-populating User Data
                $user_data = array();
                $userMeta = get_user_meta( $user->ID );
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "User Login";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                #
                $user_data['userLoginTime'] = $this->Time;
                $user_data['userLoginDate'] = $this->Date;
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action,  Sending Data to Event Boss
                $r = $this->notionify_createNotionPage( 'wordpress_userLogin', $user_data );
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "706",
                    "ERROR: user->ID Not Exist OR get_user_meta is not Exist"
                );
            }
        
        }
    }
    
    /**
     * User wp_logout  HOOK's callback function
     * @since   1.0.0
     */
    public function notionify_wordpress_userLogout( $userInfo )
    {
        # if There is a integration on user logout
        if ( $this->notionify_inTheIntegrations( 'wordpress_userLogout' ) ) {
            # if wp_get_current_user() function and wp_get_current_user()->ID exist
            
            if ( function_exists( 'wp_get_current_user' ) && !empty(wp_get_current_user()->ID) ) {
                # Pre-populating User Data
                $user = wp_get_current_user();
                $user_data = array();
                #
                $user_data['userID'] = ( isset( $user->ID ) && !empty($user->ID) ? $user->ID : "" );
                $user_data['userName'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['firstName'] = ( isset( $user->first_name ) && !empty($user->first_name) ? $user->first_name : "" );
                $user_data['lastName'] = ( isset( $user->last_name ) && !empty($user->last_name) ? $user->last_name : "" );
                $user_data['nickname'] = ( isset( $user->nickname ) && !empty($user->nickname) ? $user->nickname : "" );
                $user_data['displayName'] = ( isset( $user->display_name ) && !empty($user->display_name) ? $user->display_name : "" );
                $user_data['eventName'] = "User Logout";
                $user_data['description'] = ( isset( $userMeta['description'] ) && is_array( $userMeta['description'] ) ? implode( ", ", $userMeta['description'] ) : "" );
                $user_data['userEmail'] = ( isset( $user->user_email ) && !empty($user->user_email) ? $user->user_email : "" );
                $user_data['userUrl'] = ( isset( $user->user_url ) && !empty($user->user_url) ? $user->user_url : "" );
                $user_data['userLogin'] = ( isset( $user->user_login ) && !empty($user->user_login) ? $user->user_login : "" );
                $user_data['userRegistrationDate'] = ( isset( $user->user_registered ) && !empty($user->user_registered) ? $user->user_registered : "" );
                $user_data['userRole'] = ( isset( $user->roles ) && is_array( $user->roles ) ? implode( ", ", $user->roles ) : "" );
                #
                $user_data['userLogoutTime'] = $this->Time;
                $user_data['userLogoutDate'] = $this->Date;
                #
                # site Current Time
                $user_data['site_time'] = ( isset( $this->Time ) ? $this->Time : "" );
                $user_data['site_date'] = ( isset( $this->Date ) ? $this->Date : "" );
                # New Code Starts From Here
                $user_data["user_date_year"] = date( 'Y', current_time( 'timestamp', 0 ) );
                $user_data["user_date_month"] = date( 'm', current_time( 'timestamp', 0 ) );
                $user_data["user_date_date"] = date( 'd', current_time( 'timestamp', 0 ) );
                $user_data["user_date_time"] = date( 'H:i', current_time( 'timestamp', 0 ) );
                # Action
                
                if ( $user->ID ) {
                    $r = $this->notionify_createNotionPage( 'wordpress_userLogout', $user_data );
                } else {
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "707",
                        "ERROR: wordpress_userLogout fired but no User ID . " . json_encode( array( $user->ID, $user_data ) )
                    );
                }
            
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "708",
                    "ERROR: User ID OR  Function  wp_get_current_user() is not exists."
                );
            }
        
        }
    }
    
    /**
     * WordPress Post HOOK's callback function [NEW ONE]
     * @param     int     $post_id      Order ID
     * @param     int     $post    		Order ID
     * @param     int     $update     	Product Post 
     * @since     1.0.0   
     */
    public function notionify_wordpress_post( $post_id, $post, $update )
    {
        # Check Empty Post Id or Post
        if ( empty($post_id) or empty($post) ) {
            return;
        }
        # Default Post type array
        $postType = array(
            'post' => 'Post',
            'page' => "Page",
        );
        # getting CPTs
        $cpts = $this->notionify_allCptEvents();
        if ( $cpts[0] ) {
            $postType = array_merge( $postType, $cpts[1] );
        }
        # If Free and Post type is Not Post or Page return
        if ( !isset( $postType[$post->post_type] ) ) {
            return;
        }
        # Setting the Values
        $post_data = array();
        $userData = get_userdata( $post->post_author );
        $post_data['postID'] = $post->ID;
        #
        $post_data['post_authorID'] = ( isset( $post->post_author ) ? $post->post_author : '' );
        // property_exists // isset
        $post_data['authorUserName'] = ( isset( $userData->user_login ) ? $userData->user_login : '' );
        //
        $post_data['authorDisplayName'] = ( isset( $userData->display_name ) ? $userData->display_name : '' );
        $post_data['authorEmail'] = ( isset( $userData->user_email ) ? $userData->user_email : '' );
        $post_data['authorRole'] = ( isset( $userData->roles ) && is_array( $userData->roles ) ? implode( ", ", $userData->roles ) : "" );
        #
        $post_data['post_title'] = ( isset( $post->post_title ) ? $post->post_title : '' );
        $post_data['post_date'] = ( isset( $post->post_date ) ? $post->post_date : '' );
        $post_data['post_date_gmt'] = ( isset( $post->post_date_gmt ) ? $post->post_date_gmt : '' );
        # site Current Time
        $post_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $post_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # New Code Starts From Here
        # date of the Post Creation
        $post_data["post_date_year"] = ( (isset( $post->ID ) and !empty(get_the_date( 'Y', $post->ID ))) ? date( 'Y', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_month"] = ( (isset( $post->ID ) and !empty(get_the_date( 'm', $post->ID ))) ? date( 'm', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_date"] = ( (isset( $post->ID ) and !empty(get_the_date( 'd', $post->ID ))) ? date( 'd', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_date_time"] = ( (isset( $post->ID ) and !empty(get_the_date( 'H:i', $post->ID ))) ? date( 'H:i', strtotime( "{$post->post_modified}" ) ) : '' );
        # date of Post Modification
        $post_data["post_modified_year"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'Y', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_month"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'm', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_date"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'd', strtotime( "{$post->post_modified}" ) ) : '' );
        $post_data["post_modified_time"] = ( (isset( $post->post_modified ) and !empty($post->post_modified)) ? date( 'H:i', strtotime( "{$post->post_modified}" ) ) : '' );
        # New Code Ends Here
        $post_data['post_content'] = ( isset( $post->post_content ) ? $post->post_content : '' );
        $post_data['post_excerpt'] = ( isset( $post->post_excerpt ) ? $post->post_excerpt : '' );
        $post_data['post_status'] = ( isset( $post->post_status ) ? $post->post_status : '' );
        $post_data['comment_status'] = ( isset( $post->comment_status ) ? $post->comment_status : '' );
        $post_data['ping_status'] = ( isset( $post->ping_status ) ? $post->ping_status : '' );
        $post_data['post_password'] = ( isset( $post->post_password ) ? $post->post_password : '' );
        $post_data['post_name'] = ( isset( $post->post_name ) ? $post->post_name : '' );
        $post_data['to_ping'] = ( isset( $post->to_ping ) ? $post->to_ping : '' );
        $post_data['pinged'] = ( isset( $post->pinged ) ? $post->pinged : '' );
        $post_data['post_modified'] = ( isset( $post->post_modified ) ? $post->post_modified : '' );
        $post_data['post_modified_gmt'] = ( isset( $post->post_modified_gmt ) ? $post->post_modified_gmt : '' );
        $post_data['post_parent'] = ( isset( $post->post_parent ) ? $post->post_parent : '' );
        $post_data['guid'] = ( isset( $post->guid ) ? $post->guid : '' );
        $post_data['menu_order'] = ( isset( $post->menu_order ) ? $post->menu_order : '' );
        $post_data['post_type'] = ( isset( $post->post_type ) ? $post->post_type : '' );
        $post_data['post_mime_type'] = ( isset( $post->post_mime_type ) ? $post->post_mime_type : '' );
        $post_data['comment_count'] = ( isset( $post->comment_count ) ? $post->comment_count : '' );
        $post_data['filter'] = ( isset( $post->filter ) ? $post->filter : '' );
        # if Post type is Post
        
        if ( $post->post_type == 'post' ) {
            # getting Time Difference
            if ( !empty($post->post_date) and !empty($post->post_modified) ) {
                $post_time_diff = strtotime( $post->post_modified ) - strtotime( $post->post_date );
            }
            # New Post,
            
            if ( $post->post_status == 'publish' and $post_time_diff <= 1 ) {
                $post_data['eventName'] = "New Post";
                # Action
                $r = $this->notionify_createNotionPage( 'wordpress_newPost', $post_data );
                # event Log for Trash
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post from new post. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
            
            # Updated post
            
            if ( $post->post_status == 'publish' and $post_time_diff > 1 ) {
                $post_data['eventName'] = "Posts Edited";
                # Action
                $r = $this->notionify_createNotionPage( 'wordpress_editPost', $post_data );
                # event Log for Trash
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post edited publish. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
            
            # Post Is trash  || If Post is Trashed This Will fired
            
            if ( $post->post_status == 'trash' ) {
                $post_data['eventName'] = "Trash";
                $r = $this->notionify_createNotionPage( 'wordpress_deletePost', $post_data );
                # event Log for Trash
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post from trash. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
        
        }
        
        # if Post type is Page
        
        if ( $post->post_type == 'page' ) {
            $post_data['eventName'] = "New Page";
            # Action
            $r = $this->notionify_createNotionPage( 'wordpress_page', $post_data );
            # event Log for Trash
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "200",
                "SUCCESS: testing page. " . json_encode( array(
                $post_id,
                $post,
                $update,
                $post_data
            ) )
            );
        }
        
        # For Custom Post Type  [CPT]
        
        if ( $post->post_type != 'post' and $post->post_type != 'page' ) {
            # For Status Not Trash ;-D
            
            if ( !in_array( $post->post_status, array( 'auto-draft', 'draft', 'trash' ) ) ) {
                # getting Time Difference
                if ( !empty($post->post_date) and !empty($post->post_modified) ) {
                    $post_time_diff = strtotime( $post->post_modified ) - strtotime( $post->post_date );
                }
                # if Difference is Lager its Edit Or Its New
                
                if ( $post_time_diff < 5 ) {
                    $post_data['eventName'] = 'cpt_new_' . $post->post_type;
                    # Action
                    $r = $this->notionify_createNotionPage( 'cpt_new_' . $post->post_type, $post_data );
                    # event Log for Trash
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: testing the post edited publish. " . json_encode( array(
                        $post_id,
                        $post,
                        $update,
                        $post_data
                    ) )
                    );
                } else {
                    $post_data['eventName'] = 'cpt_update_' . $post->post_type;
                    # Action
                    $r = $this->notionify_createNotionPage( 'cpt_update_' . $post->post_type, $post_data );
                    # event Log for Trash
                    $this->notionifyLog(
                        get_class( $this ),
                        __METHOD__,
                        "200",
                        "SUCCESS: testing the post edited publish. " . json_encode( array(
                        $post_id,
                        $post,
                        $update,
                        $post_data
                    ) )
                    );
                }
            
            }
            
            # For Post status Trash
            
            if ( $post->post_status == 'trash' ) {
                $post_data['eventName'] = 'cpt_delete_' . $post->post_type;
                # Action
                $r = $this->notionify_createNotionPage( 'cpt_delete_' . $post->post_type, $post_data );
                # event Log for Trash
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: testing the post edited publish. " . json_encode( array(
                    $post_id,
                    $post,
                    $update,
                    $post_data
                ) )
                );
            }
        
        }
    
    }
    
    /**
     * WordPress New Comment HOOK's callback function
     * @param     int     $commentID     			Order ID
     * @param     int     $commentApprovedStatus    Order ID
     * @param     int     $commentData     	  		Product Post 
     * @since     1.0.0
     */
    public function notionify_wordpress_comment( $commentID, $commentApprovedStatus, $commentData )
    {
        # if There is a integration on  Comment
        
        if ( $this->notionify_inTheIntegrations( 'wordpress_comment' ) ) {
            # Check Comment ID is exist
            if ( empty($commentID) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "709",
                    "ERROR:  Comment ID is Empty! "
                );
            }
            # Setting Data
            $Data = array();
            $Data["comment_ID"] = $commentID;
            $Data["comment_post_ID"] = ( isset( $commentData["comment_post_ID"] ) ? $commentData["comment_post_ID"] : '' );
            $Data["comment_author"] = ( isset( $commentData["comment_author"] ) ? $commentData["comment_author"] : '' );
            $Data["comment_author_email"] = ( isset( $commentData["comment_author_email"] ) ? $commentData["comment_author_email"] : '' );
            $Data["comment_author_url"] = ( isset( $commentData["comment_author_url"] ) ? $commentData["comment_author_url"] : '' );
            $Data["comment_content"] = ( isset( $commentData["comment_content"] ) ? $commentData["comment_content"] : '' );
            $Data["comment_type"] = ( isset( $commentData["comment_type"] ) ? $commentData["comment_type"] : '' );
            $Data["user_ID"] = ( isset( $commentData["user_ID"] ) ? $commentData["user_ID"] : '' );
            $Data["comment_author_IP"] = ( isset( $commentData["comment_author_IP"] ) ? $commentData["comment_author_IP"] : '' );
            $Data["comment_agent"] = ( isset( $commentData["comment_agent"] ) ? $commentData["comment_agent"] : '' );
            $Data["comment_date"] = ( isset( $commentData["comment_date"] ) ? $commentData["comment_date"] : '' );
            $Data["comment_date_gmt"] = ( isset( $commentData["comment_date_gmt"] ) ? $commentData["comment_date_gmt"] : '' );
            #
            $Data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            $Data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            # New Code Starts From Here
            $Data["year_of_comment"] = get_comment_date( "Y", $commentID );
            $Data["month_of_comment"] = get_comment_date( "m", $commentID );
            $Data["date_of_comment"] = get_comment_date( "d", $commentID );
            $Data["time_of_comment"] = get_comment_date( "H:t", $commentID );
            # New Code Ends Here
            $Data["filtered"] = ( isset( $commentData["filtered"] ) ? $commentData["filtered"] : '' );
            $Data["comment_approved"] = ( isset( $commentData["comment_approved"] ) && $commentData["comment_approved"] ? "True" : "False" );
            # Action
            
            if ( empty($commentID) or empty($commentData) or empty($Data) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "710",
                    "ERROR:  commentID or commentData is empty !"
                );
            } else {
                $r = $this->notionify_createNotionPage( 'wordpress_comment', $Data );
            }
        
        }
    
    }
    
    # There should be an Edit Comment Hook Function in Here !
    # Create the Function and The Code for Edit product
    /**
     * WordPress Edit Comment HOOK's callback function
     * @param     int     $commentID     	Order ID
     * @param     int     $commentData     	Product Post 
     * @since     1.0.0
     */
    public function notionify_wordpress_edit_comment( $commentID, $commentData )
    {
        # if There is a integration on edit Comment
        
        if ( $this->notionify_inTheIntegrations( 'wordpress_edit_comment' ) ) {
            # Check Comment ID is exist
            if ( empty($commentID) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "711",
                    "ERROR: Comment ID is Empty !"
                );
            }
            $Data = array();
            $Data["comment_ID"] = $commentID;
            $Data["comment_post_ID"] = ( isset( $commentData["comment_post_ID"] ) ? $commentData["comment_post_ID"] : '' );
            $Data["comment_author"] = ( isset( $commentData["comment_author"] ) ? $commentData["comment_author"] : '' );
            $Data["comment_author_email"] = ( isset( $commentData["comment_author_email"] ) ? $commentData["comment_author_email"] : '' );
            $Data["comment_author_url"] = ( isset( $commentData["comment_author_url"] ) ? $commentData["comment_author_url"] : '' );
            $Data["comment_content"] = ( isset( $commentData["comment_content"] ) ? $commentData["comment_content"] : '' );
            $Data["comment_type"] = ( isset( $commentData["comment_type"] ) ? $commentData["comment_type"] : '' );
            $Data["user_ID"] = ( isset( $commentData["user_ID"] ) ? $commentData["user_ID"] : '' );
            $Data["comment_author_IP"] = ( isset( $commentData["comment_author_IP"] ) ? $commentData["comment_author_IP"] : '' );
            $Data["comment_agent"] = ( isset( $commentData["comment_agent"] ) ? $commentData["comment_agent"] : '' );
            $Data["comment_date"] = ( isset( $commentData["comment_date"] ) ? $commentData["comment_date"] : '' );
            $Data["comment_date_gmt"] = ( isset( $commentData["comment_date_gmt"] ) ? $commentData["comment_date_gmt"] : '' );
            #
            $Data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
            $Data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
            # New Code Starts From Here
            $Data["year_of_comment"] = get_comment_date( "Y", $commentID );
            $Data["month_of_comment"] = get_comment_date( "m", $commentID );
            $Data["date_of_comment"] = get_comment_date( "d", $commentID );
            $Data["time_of_comment"] = get_comment_date( "H:t", $commentID );
            # New Code Ends Here
            $Data["filtered"] = ( isset( $commentData["filtered"] ) ? $commentData["filtered"] : '' );
            $Data["comment_approved"] = ( isset( $commentData["comment_approved"] ) && $commentData["comment_approved"] ? "True" : "False" );
            # Action
            
            if ( empty($commentID) or empty($commentData) or empty($Data) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "712",
                    "ERROR: commentID or commentData is empty !"
                );
            } else {
                $r = $this->notionify_createNotionPage( 'wordpress_edit_comment', $Data );
            }
        
        }
    
    }
    
    /**
     * Woocommerce  Products  HOOK's callback function
     * @param     int     $new_status     Order ID
     * @param     int     $old_status     Order ID
     * @param     int     $post     	  Product Post 
     * @since     1.0.0
     */
    public function notionify_woocommerce_product( $new_status, $old_status, $post )
    {
        # If Post type is Not product
        if ( $post->post_type !== 'product' ) {
            return;
        }
        # getting Product information
        $product = wc_get_product( $post->ID );
        $product_data = array();
        # Get Product General Info
        $product_data['productID'] = $post->ID;
        $product_data['type'] = ( method_exists( $product, 'get_type' ) && is_string( $product->get_type() ) ? $product->get_type() : "--" );
        $product_data['post_type'] = ( isset( $post->post_type ) ? $post->post_type : '' );
        $product_data['name'] = ( method_exists( $product, 'get_name' ) && is_string( $product->get_name() ) ? $product->get_name() : "--" );
        $product_data['slug'] = ( method_exists( $product, 'get_slug' ) && is_string( $product->get_slug() ) ? $product->get_slug() : "--" );
        $product_data['date_created'] = ( method_exists( $product, 'get_date_created' ) && is_object( $product->get_date_created() ) ? $product->get_date_created()->date( "F j, Y, g:i:s A T" ) : "--" );
        $product_data['date_modified'] = ( method_exists( $product, 'get_date_modified' ) && is_object( $product->get_date_modified() ) ? $product->get_date_modified()->date( "F j, Y, g:i:s A T" ) : "--" );
        # site Current Time
        $product_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $product_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Get Product Dimensions
        $product_data['weight'] = ( method_exists( $product, 'get_weight' ) && is_string( $product->get_weight() ) ? $product->get_weight() : "--" );
        $product_data['length'] = ( method_exists( $product, 'get_length' ) && is_string( $product->get_length() ) ? $product->get_length() : "--" );
        $product_data['width'] = ( method_exists( $product, 'get_width' ) && is_string( $product->get_width() ) ? $product->get_width() : "--" );
        $product_data['height'] = ( method_exists( $product, 'get_height' ) && is_string( $product->get_height() ) ? $product->get_height() : "--" );
        # Get Product Variations
        $product_data['attributes'] = ( method_exists( $product, 'get_variation_attributes' ) && is_array( $product->get_variation_attributes() ) ? json_encode( $product->get_variation_attributes() ) : "--" );
        $product_data['default_attributes'] = ( method_exists( $product, 'get_default_attributes' ) && is_array( $product->get_default_attributes() ) ? json_encode( $product->get_default_attributes() ) : "--" );
        # Get Product Taxonomies
        $product_data['category_ids'] = ( method_exists( $product, 'get_category_ids' ) && is_array( $product->get_category_ids() ) ? implode( ", ", $product->get_category_ids() ) : "--" );
        $product_data['tag_ids'] = ( method_exists( $product, 'get_tag_ids' ) && is_array( $product->get_tag_ids() ) ? implode( ", ", $product->get_gallery_image_ids() ) : "--" );
        # Get Product Images
        $product_data['image_id'] = ( method_exists( $product, 'get_image_id' ) && is_string( $product->get_image_id() ) ? $product->get_image_id() : "--" );
        $product_data['gallery_image_ids'] = ( method_exists( $product, 'get_gallery_image_ids' ) && is_array( $product->get_gallery_image_ids() ) ? implode( ", ", $product->get_gallery_image_ids() ) : "--" );
        $product_data['get_attachment_image_url'] = ( (method_exists( $product, 'get_image_id' ) and function_exists( 'wp_get_attachment_image_url' ) and !empty($product->get_image_id())) ? wp_get_attachment_image_url( $product->get_image_id() ) : "--" );
        # Post Meta Data portion Ends
        
        if ( $new_status == 'publish' && $old_status !== 'publish' ) {
            # New Product Insert
            $product_data['price'] = sanitize_text_field( $_POST['_sale_price'] );
            $product_data['regular_price'] = sanitize_text_field( $_POST['_regular_price'] );
            $product_data['sale_price'] = sanitize_text_field( $_POST['_sale_price'] );
            $product_data['eventName'] = "New Product";
            # Action
            $r = $this->notionify_createNotionPage( 'wc-new_product', $product_data );
        } elseif ( $new_status == 'trash' ) {
            # Delete  Product ;
            $product_data['eventName'] = "Trash";
            # Action
            $r = $this->notionify_createNotionPage( 'wc-delete_product', $product_data );
        } else {
            # Update
            $product_data['eventName'] = "Update Product";
            # Action
            $r = $this->notionify_createNotionPage( 'wc-edit_product', $product_data );
        }
    
    }
    
    /**
     * WooCommerce Order  HOOK's callback function
     * @param    int     $order_id     Order ID
     * @since    1.0.0
     */
    public function notionify_woocommerce_order_status_changed( $order_id, $this_status_transition_from, $this_status_transition_to )
    {
        # check to see is there any integration on this order change Status.
        if ( !$this->notionify_inTheIntegrations( 'wc-' . $this_status_transition_to ) ) {
            return;
        }
        # getting order data
        $order = wc_get_order( $order_id );
        $order_data = array();
        #  ++++++++++++ This below of Code Is Not Working | change the Code ++++++++++++
        # New system For Stopping Dabble Submission # If Order Created Date Is Less than 3 mints and Order is from checkout
        $orderDateTimeStamp = strtotime( $order->get_date_created() );
        $currentDateTimeStamp = strtotime( current_time( "Y-m-d H:i:s" ) );
        $timDiffMin = round( ($currentDateTimeStamp - $orderDateTimeStamp) / 60 );
        # check the arguments || if time difference is less than 5 mints stop
        
        if ( $order->get_created_via() == 'checkout' and $timDiffMin < 5 ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "400",
                "ERROR: Dabble Submission Stopped!"
            );
            return;
        }
        
        # ++++++++++++ This above of Code Is Not Working | change the Code  ++++++++++++
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        #
        $order_data['eventName'] = $order->get_status();
        //'wc-new_order'
        $order_data['status'] = "wc-" . $order->get_status();
        # Action
        
        if ( empty($order_id) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "714",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->notionify_createNotionPage( $order_data['status'], $order_data );
        }
    
    }
    
    /**
     * woocommerce_new_orders New Order  HOOK's callback function
     * @param     int     $order_id     Order ID
     * @since     1.0.0
     */
    public function notionify_woocommerce_new_order_admin( $order_id )
    {
        $order_data = array();
        # getting order information
        $order = wc_get_order( $order_id );
        # if not admin returns
        if ( empty($order_id) && $order->get_created_via() != 'admin' ) {
            return;
        }
        # check to see is there any integration on this order change Status.
        if ( !$this->notionify_inTheIntegrations( "wc-" . $order->get_status() ) ) {
            return;
        }
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        # Start
        # freemius
        $order_data['shipping_country'] = ( method_exists( $order, 'get_shipping_country' ) && is_string( $order->get_shipping_country() ) ? $order->get_shipping_country() : "" );
        $order_data['address'] = ( method_exists( $order, 'get_address' ) && is_array( $order->get_address() ) ? json_encode( $order->get_address() ) : "" );
        $order_data['shipping_address_map_url'] = ( method_exists( $order, 'get_shipping_address_map_url' ) && is_string( $order->get_shipping_address_map_url() ) ? $order->get_shipping_address_map_url() : "" );
        $order_data['formatted_billing_full_name'] = ( method_exists( $order, 'get_formatted_billing_full_name' ) && is_string( $order->get_formatted_billing_full_name() ) ? $order->get_formatted_billing_full_name() : "" );
        $order_data['formatted_shipping_full_name'] = ( method_exists( $order, 'get_formatted_shipping_full_name' ) && is_string( $order->get_formatted_shipping_full_name() ) ? $order->get_formatted_shipping_full_name() : "" );
        $order_data['formatted_billing_address'] = ( method_exists( $order, 'get_formatted_billing_address' ) && is_string( $order->get_formatted_billing_address() ) ? $order->get_formatted_billing_address() : "" );
        $order_data['formatted_shipping_address'] = ( method_exists( $order, 'get_formatted_shipping_address' ) && is_string( $order->get_formatted_shipping_address() ) ? $order->get_formatted_shipping_address() : "" );
        #
        $order_data['payment_method'] = ( method_exists( $order, 'get_payment_method' ) && is_string( $order->get_payment_method() ) ? $order->get_payment_method() : "" );
        $order_data['payment_method_title'] = ( method_exists( $order, 'get_payment_method_title' ) && is_string( $order->get_payment_method_title() ) ? $order->get_payment_method_title() : "" );
        $order_data['transaction_id'] = ( method_exists( $order, 'get_transaction_id' ) && is_string( $order->get_transaction_id() ) ? $order->get_transaction_id() : "" );
        #
        $order_data['checkout_payment_url'] = ( method_exists( $order, 'get_checkout_payment_url' ) && is_string( $order->get_checkout_payment_url() ) ? $order->get_checkout_payment_url() : "" );
        $order_data['checkout_order_received_url'] = ( method_exists( $order, 'get_checkout_order_received_url' ) && is_string( $order->get_checkout_order_received_url() ) ? $order->get_checkout_order_received_url() : "" );
        $order_data['cancel_order_url'] = ( method_exists( $order, 'get_cancel_order_url' ) && is_string( $order->get_cancel_order_url() ) ? $order->get_cancel_order_url() : "" );
        $order_data['cancel_order_url_raw'] = ( method_exists( $order, 'get_cancel_order_url_raw' ) && is_string( $order->get_cancel_order_url_raw() ) ? $order->get_cancel_order_url_raw() : "" );
        $order_data['cancel_endpoint'] = ( method_exists( $order, 'get_cancel_endpoint' ) && is_string( $order->get_cancel_endpoint() ) ? $order->get_cancel_endpoint() : "" );
        $order_data['view_order_url'] = ( method_exists( $order, 'get_view_order_url' ) && is_string( $order->get_view_order_url() ) ? $order->get_view_order_url() : "" );
        $order_data['edit_order_url'] = ( method_exists( $order, 'get_edit_order_url' ) && is_string( $order->get_edit_order_url() ) ? $order->get_edit_order_url() : "" );
        $order_data['eventName'] = $order->get_status();
        //'wc-new_order'
        $order_data['status'] = "wc-" . $order->get_status();
        # freemius
        # Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Starts
        $woo_checkout_field_editor = $this->notionify_woo_checkout_field_editor_pro_fields();
        
        if ( $woo_checkout_field_editor[0] ) {
            $woo_checkout_field_editor_options = get_option( "woo_checkout_field_editor" );
            foreach ( $woo_checkout_field_editor_options as $key => $value ) {
                $order_data[$key] = ( isset( $woo_checkout_field_editor[1][$key], $order_data["orderID"] ) && !empty(get_post_meta( $order_data["orderID"], $key )[0]) ? get_post_meta( $order_data["orderID"], $key )[0] : "" );
            }
        }
        
        # Checkout Field Editor (Checkout Manager) for WooCommerce By ThemeHigh  || Ends
        # freemius
        # Order Meta Data Starts
        # Empty Holder array
        $metaOutPut = array();
        # Global Db object
        global  $wpdb ;
        # execute Query
        $orderMetaKeyValue = $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id = " . $order->get_id(), ARRAY_A );
        # get Distinct Keys;
        $metaKeys = $this->notionify_wooCommerce_order_metaKeys();
        # Check and Balance for all the Meta keys
        
        if ( $metaKeys[0] && !empty($orderMetaKeyValue) ) {
            # populating Output array in revers with  empty value
            foreach ( $metaKeys[1] as $key => $value ) {
                $metaOutPut[$value] = "--";
            }
            # Looping the Meta key & value of Certain Comment And Populating the $metaOutPut Key array with Value
            foreach ( $orderMetaKeyValue as $oneArray ) {
                
                if ( is_array( $oneArray ) && isset( $oneArray['meta_key'], $metaOutPut[$oneArray['meta_key']], $oneArray['meta_value'] ) ) {
                    # Convert text to  an array then JSON for reducing the String
                    $isArrayTest = @unserialize( $oneArray['meta_value'] );
                    
                    if ( $isArrayTest == null ) {
                        $metaOutPut[$oneArray['meta_key']] = $oneArray['meta_value'];
                    } else {
                        $metaOutPut[$oneArray['meta_key']] = $isArrayTest;
                    }
                
                }
            
            }
        }
        
        # Append New metaOutPut array to $commentData data array;
        $order_data = array_merge( $order_data, $metaOutPut );
        # Order Meta Data Ends
        # Action
        
        if ( empty($order_id) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "714",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->notionify_createNotionPage( $order_data['status'], $order_data );
        }
    
    }
    
    /**
     * WooCommerce Checkout PAge Order CallBack Function 
     * @param     int     $order_id     Order ID
     * @since     1.0.0
     */
    public function notionify_woocommerce_new_order_checkout( $order_id )
    {
        $order_data = array();
        $order = wc_get_order( $order_id );
        # if not checkout returns
        if ( empty($order_id) && $order->get_created_via() != 'checkout' ) {
            return;
        }
        # check to see is there any integration on this order change Status.
        if ( !$this->notionify_inTheIntegrations( 'wc-new_order' ) ) {
            return;
        }
        #
        $order_data['orderID'] = ( method_exists( $order, 'get_id' ) && is_int( $order->get_id() ) ? $order->get_id() : "" );
        $order_data['billing_first_name'] = ( method_exists( $order, 'get_billing_first_name' ) && is_string( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : "" );
        $order_data['billing_last_name'] = ( method_exists( $order, 'get_billing_last_name' ) && is_string( $order->get_billing_last_name() ) ? $order->get_billing_last_name() : "" );
        $order_data['billing_company'] = ( method_exists( $order, 'get_billing_company' ) && is_string( $order->get_billing_company() ) ? $order->get_billing_company() : "" );
        $order_data['billing_address_1'] = ( method_exists( $order, 'get_billing_address_1' ) && is_string( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : "" );
        $order_data['billing_address_2'] = ( method_exists( $order, 'get_billing_address_2' ) && is_string( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : "" );
        $order_data['billing_city'] = ( method_exists( $order, 'get_billing_city' ) && is_string( $order->get_billing_city() ) ? $order->get_billing_city() : "" );
        $order_data['billing_state'] = ( method_exists( $order, 'get_billing_state' ) && is_string( $order->get_billing_state() ) ? $order->get_billing_state() : "" );
        $order_data['billing_postcode'] = ( method_exists( $order, 'get_billing_postcode' ) && is_string( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : "" );
        # site Current Time
        $order_data['site_time'] = ( isset( $this->Time ) ? $this->Time : '' );
        $order_data['site_date'] = ( isset( $this->Date ) ? $this->Date : '' );
        # Start
        $order_data['shipping_first_name'] = ( method_exists( $order, 'get_shipping_first_name' ) && is_string( $order->get_shipping_first_name() ) ? $order->get_shipping_first_name() : "" );
        $order_data['shipping_last_name'] = ( method_exists( $order, 'get_shipping_last_name' ) && is_string( $order->get_shipping_last_name() ) ? $order->get_shipping_last_name() : "" );
        $order_data['shipping_company'] = ( method_exists( $order, 'get_shipping_company' ) && is_string( $order->get_shipping_company() ) ? $order->get_shipping_company() : "" );
        $order_data['shipping_address_1'] = ( method_exists( $order, 'get_shipping_address_1' ) && is_string( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : "" );
        $order_data['shipping_address_2'] = ( method_exists( $order, 'get_shipping_address_2' ) && is_string( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : "" );
        $order_data['shipping_city'] = ( method_exists( $order, 'get_shipping_city' ) && is_string( $order->get_shipping_city() ) ? $order->get_shipping_city() : "" );
        $order_data['shipping_state'] = ( method_exists( $order, 'get_shipping_state' ) && is_string( $order->get_shipping_state() ) ? $order->get_shipping_state() : "" );
        $order_data['shipping_postcode'] = ( method_exists( $order, 'get_shipping_postcode' ) && is_string( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : "" );
        #
        $order_data['eventName'] = "New order";
        $order_data['status'] = "wc-" . $order->get_status();
        # Action
        
        if ( empty($order_id) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "715",
                "ERROR: Order is empty !"
            );
        } else {
            $r = $this->notionify_createNotionPage( 'wc-new_order', $order_data );
        }
    
    }
    
    /**
     * Third party plugin :
     * Checkout Field Editor ( Checkout Manager ) for WooCommerce
     * BETA testing;
     * @since    1.0.0
     */
    public function notionify_woo_checkout_field_editor_pro_fields()
    {
        # getting The Active Plugin list
        $active_plugins = get_option( 'active_plugins' );
        # Empty Holder
        $woo_checkout_field_editor_pro = array();
        
        if ( in_array( 'woo-checkout-field-editor-pro/checkout-form-designer.php', $active_plugins ) ) {
            # Getting data from wp options
            $a = get_option( "wc_fields_billing" );
            $b = get_option( "wc_fields_shipping" );
            $c = get_option( "wc_fields_additional" );
            if ( $a ) {
                foreach ( $a as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            if ( $b ) {
                foreach ( $b as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            if ( $c ) {
                foreach ( $c as $key => $field ) {
                    
                    if ( isset( $field['custom'] ) && $field['custom'] == 1 ) {
                        $woo_checkout_field_editor_pro[$key]['type'] = $field['type'];
                        $woo_checkout_field_editor_pro[$key]['name'] = $field['name'];
                        $woo_checkout_field_editor_pro[$key]['label'] = $field['label'];
                    }
                
                }
            }
            
            if ( empty($woo_checkout_field_editor_pro) ) {
                return array( FALSE, "ERROR: Checkout Field Editor aka Checkout Manager for WooCommerce is EMPTY no Custom Field. " );
            } else {
                return array( TRUE, $woo_checkout_field_editor_pro );
            }
        
        } elseif ( in_array( 'woocommerce-checkout-field-editor-pro/woocommerce-checkout-field-editor-pro.php', $active_plugins ) ) {
            # this part is for professional Version of that Plugin;
            # if Check to see class is exists or not
            if ( class_exists( 'For_WCFE_Checkout_Fields_Utils' ) and class_exists( 'WCFE_Checkout_Fields_Utils' ) ) {
                # it declared in the Below of this Class
                For_WCFE_Checkout_Fields_Utils::fields();
            }
        } else {
            return array( FALSE, "ERROR: Checkout Field Editor aka Checkout Manager for WooCommerce is not installed " );
        }
    
    }
    
    /**
     *  This function will create a notion page Given database 
     *  @param     string    $eventID     	 Event ID
     *  @param     array     $eventData      Event generated data
     *  @since     1.0.0
     */
    public function notionify_createNotionPage( $eventID = "", $eventData = "" )
    {
        # for event ID check.
        
        if ( empty($eventID) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "730",
                "ERROR: event id is empty."
            );
            return array( FALSE, "ERROR: event id is empty." );
        }
        
        # event data check.
        
        if ( empty($eventData) or !is_array( $eventData ) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "731",
                "ERROR: event data is empty or not array."
            );
            return array( FALSE, "ERROR: event data is empty or not array." );
        }
        
        # getting the notion Integrations from wp transient.
        $integrations = get_transient( "notionifyIntegrations" );
        # integrations list check
        
        if ( empty($integrations) or !is_array( $integrations ) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "732",
                "ERROR: notion integrations is empty or not array."
            );
            return array( FALSE, "ERROR: notion integrations is empty or not array." );
        }
        
        # getting Notion api key from wp option.
        $APItoken = get_option( 'notionifyNotionAPIkey' );
        # token check.
        
        if ( empty($APItoken) or !is_string( $APItoken ) ) {
            $this->notionifyLog(
                get_class( $this ),
                __METHOD__,
                "733",
                "ERROR: notion API token is empty or not array."
            );
            return array( FALSE, "ERROR: notion API token is empty or not array." );
        }
        
        # looping the saved Integrations from Transients.
        foreach ( $integrations as $integrationArray ) {
            # is event is publish and event is as event id ||  this false should not be logged as many event will check it
            
            if ( !isset( $integrationArray['activationStatus'], $integrationArray['event'] ) or $integrationArray['activationStatus'] != 'publish' or $integrationArray['event'] != $eventID ) {
                continue;
                //  skips current one;
            }
            
            # check vital parameter are set and not empty
            
            if ( !isset(
                $integrationArray['event'],
                $integrationArray['selectedDbPage'],
                $integrationArray['titleFields'],
                $integrationArray['bodyFields']
            ) or empty($integrationArray['selectedDbPage']) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "735",
                    "ERROR: event, selectedDbPage, titleFields, bodyFields, selectedDbPage is not set."
                );
                continue;
                //  skips current one;
            }
            
            # Check event is set
            
            if ( !isset( $this->events[$integrationArray['event']], $this->eventsAndTitles[$integrationArray['event']] ) ) {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "734",
                    "ERROR: events or eventsAndTitles is not set."
                );
                continue;
                //  skips current one;
            }
            
            # save the timestamp to the integration timestamp  || preventing Dual Submission || saving last Fired time
            $notionifyThisEvtLastFired = (int) get_post_meta( $integrationArray['ID'], 'notionifyThisEvtLastFired', TRUE );
            // notionifyThisEvtLastFired
            # check and balance
            
            if ( $notionifyThisEvtLastFired and time() - $notionifyThisEvtLastFired < 27 ) {
                # keeping log
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "735",
                    "ERROR: Dual submission Prevented of Integration : <b>" . $integrationArray['ID'] . "</b>"
                );
                continue;
                //  skips current looped item;
            } else {
                # saving last Fired time v
                update_post_meta( $integrationArray['ID'], 'notionifyThisEvtLastFired', time() );
            }
            
            # Processing the data & inserting necessary data to $eventData
            foreach ( $eventData as $firstKey => $firstValue ) {
                # empty holder
                $data = "";
                # if value is string
                if ( is_string( $firstValue ) or is_numeric( $firstValue ) ) {
                    $data .= strip_tags( $firstValue );
                }
                # if value is array or object
                if ( is_array( $firstValue ) or is_object( $firstValue ) ) {
                    # loop the first stage value
                    foreach ( $firstValue as $secondKey => $secondValue ) {
                        # if  first stage value is string
                        if ( is_string( $secondValue ) ) {
                            $data .= $secondValue . ", ";
                        }
                        # if second stage value is array or object
                        if ( is_array( $secondValue ) or is_object( $secondValue ) ) {
                            # loop the second stage value
                            foreach ( $secondValue as $thirdKey => $thirdValue ) {
                                
                                if ( is_string( $thirdValue ) ) {
                                    $data .= $thirdValue . ", ";
                                } else {
                                    $data .= ( empty($thirdValue) ? "  " : json_encode( $thirdValue ) );
                                }
                            
                            }
                        }
                    }
                }
                # inserting value
                $eventData[$firstKey] = $data;
            }
            # Getting data fields title
            $eventsAndTitles = $this->eventsAndTitles[$eventID];
            # Empty Holder
            $title = array();
            $body = array();
            # Looping title Fields
            foreach ( $integrationArray['titleFields'] as $titleKey ) {
                
                if ( isset( $eventsAndTitles[$titleKey] ) and !empty($eventsAndTitles[$titleKey]) ) {
                    $title[$eventsAndTitles[$titleKey]] = ( isset( $eventData[$titleKey] ) ? $eventData[$titleKey] : "" );
                } else {
                    $title[$titleKey] = ( isset( $eventData[$titleKey] ) ? $eventData[$titleKey] : "" );
                }
            
            }
            # Looping Body fields.
            foreach ( $integrationArray['bodyFields'] as $bodyKey ) {
                
                if ( isset( $eventsAndTitles[$bodyKey] ) and !empty($eventsAndTitles[$bodyKey]) ) {
                    $body[$eventsAndTitles[$bodyKey]] = ( isset( $eventData[$bodyKey] ) ? $eventData[$bodyKey] : "" );
                } else {
                    $body[$bodyKey] = ( isset( $eventData[$bodyKey] ) ? $eventData[$bodyKey] : "" );
                }
            
            }
            # Array to String convection empty holder
            $titleString = "";
            $bodyString = [];
            # counter for add and remove last (,)
            $i = 0;
            # creating title string
            foreach ( $title as $key => $value ) {
                $i++;
                
                if ( is_string( $value ) ) {
                    $titleString .= ( (count( $title ) > 1 and count( $title ) != $i) ? $value . ", " : $value );
                } else {
                    $titleString .= ( (count( $title ) > 1 and count( $title ) != $i) ? json_encode( $value ) . ", " : json_encode( $value ) );
                }
            
            }
            #Json encode title string
            $titleString = json_encode( array(
                "content" => $titleString,
            ) );
            # this is for normal list
            foreach ( $body as $key => $value ) {
                $itemKey = array();
                $itemKey["type"] = "text";
                $itemKey["text"]["content"] = $i . ". " . $key . " : ";
                $itemKey["annotations"] = array(
                    "bold"          => true,
                    "italic"        => false,
                    "strikethrough" => false,
                    "code"          => false,
                    "color"         => "default",
                );
                $bodyString[] = $itemKey;
                $itemValue = array();
                $itemValue["type"] = "text";
                $itemValue["text"]["content"] = $value . "\n";
                $itemValue["annotations"] = array(
                    "bold"          => false,
                    "italic"        => false,
                    "strikethrough" => false,
                    "code"          => false,
                    "color"         => "default",
                );
                $bodyString[] = $itemValue;
                $i++;
            }
            # This is for number list
            # This is for checklist
            # body title string
            $bodyTitleString = json_encode( array(
                "content" => "ID " . $integrationArray['ID'] . " : " . $this->events[$integrationArray['event']],
            ) );
            # if database id is set and not empty
            $r = wp_remote_request( 'https://api.notion.com/v1/pages', array(
                'method'  => 'POST',
                'headers' => array(
                'Authorization'  => 'Bearer ' . $APItoken,
                'Content-Type'   => 'application/json',
                'Notion-Version' => '2022-06-28',
            ),
                'body'    => '{
								"parent": { "database_id": "' . $integrationArray['selectedDbPage'] . '" },
								"properties": {
									"Name": {
										"title": [
											{
												"text": ' . $titleString . '
											}
										]
									}
								},
								"children": [
									{
										"object": "block",
										"type": "heading_2",
										"heading_2": {
											"rich_text": [{ "type": "text", "text": ' . $bodyTitleString . ' }]
										}
									},
									{
										"object": "block",
										"type": "paragraph",
										"paragraph": {
											"rich_text": ' . json_encode( $bodyString ) . '
										}
									}
								]
							}',
            ) );
            # remote request check and balance || error handling
            
            if ( !is_wp_error( $r ) and isset( $r['response'], $r['response']['code'], $r['body'] ) and $r['response']['code'] == 200 ) {
                # if success keep the log
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "200",
                    "SUCCESS: notion page created. REQUEST BODY :::> " . $r['body']
                );
                # also save the last firing time in this integration log;
            } else {
                $this->notionifyLog(
                    get_class( $this ),
                    __METHOD__,
                    "790",
                    "ERROR: page is not created E:::> " . json_encode( $r )
                );
            }
        
        }
    }
    
    /**
     * This is a Helper function to check is There Any integration saved. Also set the transient cache
     * @param      string   $data_source   Which platform call this function 
     * @since      1.0.0
     */
    public function notionify_inTheIntegrations( $eventID = '' )
    {
        # CHECK AND BALANCE if no id retene false
        if ( empty($eventID) ) {
            return false;
        }
        # default status
        $notionIntegrationList = "";
        # getting the Options
        $integrations = get_transient( "notionifyIntegrations" );
        
        if ( $integrations ) {
            # Loop that value and insert that  to the  $notionIntegrationList list array
            foreach ( $integrations as $key => $value ) {
                if ( isset( $value['event'] ) and $value['event'] == $eventID and $value['activationStatus'] == 'publish' ) {
                    $notionIntegrationList++;
                }
            }
        } else {
            # no Transient value so create and set value
            $this->notionify_updateIntegrationListTransient();
            # now get that value
            $integrations = get_transient( "notionifyIntegrations" );
            # Loop that value and insert that  to the  $notionIntegrationList list array
            foreach ( $integrations as $key => $value ) {
                if ( isset( $value['event'] ) and $value['event'] == $eventID and $value['activationStatus'] == 'publish' ) {
                    $notionIntegrationList++;
                }
            }
            # done bro
        }
        
        #
        return $notionIntegrationList;
    }

}