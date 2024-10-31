<?php
/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 * @link       https://profiles.wordpress.org/javmah
 * @since      1.0.0
 * @package    Notion
 * @subpackage Notion/admin/partials
 */
?>

<div class="wrap" id="notionify_Log">
	<h1 class="wp-heading-inline"> Notionify Log Page 
		<code>last 100 log </code> 
		<?php
			$notionifyLogStatus =   get_option('notionifyifyLog');
			if($notionifyLogStatus){
				echo" <code><a href='". admin_url('admin.php?page=notionify&action=logStatus')."' style='opacity: 0.5; color: green;'>Enable log</a></code> ";
			} else {
				echo" <code><a href='". admin_url('admin.php?page=notionify&action=logStatus')."' style='opacity: 0.5; color: red;'  >Disable log!</a></code> ";
			}
		?>
		<code><a style="opacity: 0.5; color: red;" href="<?php echo admin_url('admin.php?page=notionify&action=deleteLog'); ?>">remove logs</a></code> 
	</h1>
	<?php
		if($notionifyLogStatus){
			echo"<h3 style='color:red;' > <span class='dashicons dashicons-dismiss'></span> Log is Disabled ! </h3>";
		}

		$notionLog = get_posts(array('post_type' => 'notionifyLog', 'order' => 'DESC', 'posts_per_page' => -1));
		$i = 1 ;
		foreach( $notionLog as $key =>  $log ){
			if( $log->post_title == 200 ){
				echo"<div class='notice notice-success inline'>";
			} else {
				echo"<div class='notice notice-error inline'>";
			}
			echo"<p><span class='automail-circle'>" . esc_html($log->ID) ;
			echo" .</span>";
			echo "<code>" . esc_html($log->post_title) . "</code>";
			echo "<code>";
			if(isset($log->post_excerpt)){
				echo esc_html($log->post_excerpt) ;
			}
			echo "</code>";
			echo esc_html($log->post_content);
			echo" <code>" . esc_html($log->post_date)  .  "</code>";
			echo"</p>";
			echo"</div>";
			$i++ ;
		}
	?>
</div>

