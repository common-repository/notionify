<?php

/**
 * this is API documentation file
 *
 * @link       https://profiles.wordpress.org/javmah
 * @since      1.0.0
 * @package    Notion
 * @subpackage Notion/admin/partials
 */
?>

<div class="wrap"  id="notionInside">
	<h1><?php esc_attr_e( 'Get Notion API key & Configure . ', 'notion' ); ?> </h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<!-- main content -->
			<div id="post-body-content">
				<!-- STARTS -->
					<div id='step_1'>
						<h3> Step 1 : </h3>
						<p>
							<b>1.</b> Click this link <code> <b><a href="https://www.notion.so/my-integrations">My integrations</a></b></code> it will open Notion's My integrations page in new tab. like below screenshot. ( Don't forget to Log in to the Notion before clicking the link 🥳 )
							<br><br>
							<b>2.</b> Click to the <code><b> + New integration</b></code> or <code><b> + Create new integration</b></code> button.
							<br><br>
							<!-- Loading Starts -->
							<img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-1.png', __FILE__  ); ?>" alt="loading...">
							<!-- Loading Ends -->
						</p>
					</div>

					<div id='step_2'>
						<h3> Step 2 : </h3>
						<p>
							<b>1.</b> Please fill in the <code><b>Name</b></code> field. Gave you integration a name like <i><b>WordPress notion integration</b></i>.
							<br><br>
							<b>2.</b> Now select <code><b>Associated workspace</b></code> from the dropdown.
							<br><br>
							<b>3.</b> Now save AKA <code><b>Submit &#10140;</b></code> the integration.
							<br><br>
							<!-- Loading Starts -->
							<img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-2.png', __FILE__  ); ?>" alt="loading...">
							<!-- Loading Ends -->
						</p>
					</div>
        
					<div id='step_3'>
						<h3> Step 3 : </h3>
						<p>
							<b>1.</b> Click on the <code><b>Show</b></code> button and Copy the API token ( Internal Integration Token field value ).
							<br><br>
							<b>2.</b> Now click the <code><b>Save Changes</b></code> button and save the integration.
							<br><br>
							<!-- Loading Starts -->
							<img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-3.png', __FILE__  ); ?>" alt="loading...">
							<!-- Loading Ends -->
							<br><br>
							<b>3.</b> Now go to the Plugin on your WordPress site and paste the copied API key & <code><b>SAVE</b></code>. See the below screenshot.
							<br><br>
							<!-- Loading Starts -->
							<img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-5.png', __FILE__  ); ?>" alt="loading...">
							<!-- Loading Ends -->
						</p>
					</div>

					<div id='step_4'>
						<h3> Step 4 : </h3>
						<p>
							<b>1.</b> Open you database that you wish to integrate. In our case, it's <b> New Database </b>
							<br><br>
							<b>2.</b> Click on the <code><b> ...</b></code> icon on the top right corner a menu will appear.
							<br><br>
							<b>3.</b> Now scroll down to the <code><b> Connections </b></code> section. And you will see <code><b> + Add connections </b></code>.
							<br><br>
							<b>4.</b> Go there another menu will appear, search your integration here that you created a few minutes back. click and confirm that.   
									  Congratulation job is well done, Now go to the plugin and create a new connection with the event. enjoy
							<br><br>
							<!-- Loading Starts -->
							<img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-4.png', __FILE__  ); ?>" alt="loading...">
							<!-- Loading Ends -->
						</p>
					</div>
				<!-- ENDS -->
			</div>
			<!-- post-body-content -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->

