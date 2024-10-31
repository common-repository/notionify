<?php
/**
 * Provide a admin area view for the plugin This file is used to markup the admin-facing aspects of the plugin.
 * @link       https://profiles.wordpress.org/javmah
 * @since      1.0.0
 * @package    Notion
 * @subpackage Notion/admin/partials
 */
?>
<div class="wrap"  id="notionInside">
	<h1>
        <?php esc_attr_e('Hello, Notion Integration.', 'notionify'); ?>
        <!-- CREATE  NEW INTEGRATION -->
        <a class="button-secondary" href="<?php echo admin_url('admin.php?page=notionify&action=new')?>" class="button-secondary"> + Create New Integration </a>        
    </h1>
    
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h2><span><?php esc_attr_e( 'Notion Integration list', 'notionify' ); ?></span></h2>
                        <!-- Inside div start  -->
						<div class="inside" >
                            <table class="widefat">
                                <thead>
                                    <tr>
                                        <th> Notion Database Name       </th>
                                        <th> Data Source                </th>
                                        <th> Selected Fields for Title  </th>
                                        <th> Selected Fields for Body   </th>
                                        <th> Status                     </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $integrationList  = get_posts(array(
                                                                'post_type'   	 	=> 'notionify',
                                                                'post_status' 		=> array('publish', 'pending'),
                                                                'posts_per_page' 	=> -1
                                                            ));
                                        # looping the thing
                                        $i = 0; 
                                        if( empty(  $integrationList  ) ){
                                            echo"<tr><td> <b> &nbsp;&nbsp;&nbsp;  NO INTEGRATION ... </b> <a href=" . admin_url('admin.php?page=notionify&action=new') . ">  <span class='dashicons dashicons-plus-alt2'> </span> create new notion integration </a> </td></tr>";
                                        } else {
                                            foreach ($integrationList as $key => $integration) {
                                                echo ($i % 2 == 0)? "<tr class='alternate' >" : "<tr class=''>";
                                                    echo"<td>";
                                                        //  Integration id
                                                        echo "<b title='integrations id'> ID " . esc_html($integration->ID)  . "</b>";
                                                        echo"<br>";
                                                        if($this->nationDbPages[0] AND isset($this->nationDbPages[1][$integration->post_content]['object'])){
                                                            echo "<b title='Notion database name'>". esc_html($this->nationDbPages[1][$integration->post_content]['object']) . "</b> : ";
                                                        } else {
                                                            echo"<b title='ERROR : message.'> <span style='color:red;' > ERROR : </span> no database or page on this id. </b>";
                                                            //  NOW DISABLE THE INTEGRATION
                                                            wp_update_post(array('ID' => $integration->ID, 'post_status'=>'pending'));
                                                            // LOG 
                                                            $this->notionifyLog(get_class( $this ), __METHOD__,"105", "ERROR: no Notion database this ID. integrations is disabled. ID : " . $integration->ID );
                                                        }
                                                        //  Printing Notion database ID
                                                        if($this->nationDbPages[0] AND  isset($this->nationDbPages[1][$integration->post_content]['name'])){
                                                            echo "<b title='integrations id'>". esc_html($this->nationDbPages[1][$integration->post_content]['name']) . "</b> : ";
                                                        }
                                                        echo"<br>";
                                                        echo "<b title='Notion database id.'>" . esc_html($integration->post_content) . "</b> : ";
                                                        echo"<br>";
                                                        echo"<a class='notionEditLink'    href=" . admin_url('admin.php?page=notionify&action=edit&id=' . esc_attr($integration->ID)) . " > EDIT </a> &nbsp;&nbsp;";
                                                        echo"<a class='notionDeleteLink'  title='click here to delete this integration.'  href=" . admin_url('admin.php?page=notionify&action=delete&id=' . esc_attr($integration->ID)) . " > DELETE </a> ";
                                                    echo"</td>";
    
                                                    echo"<td>";
                                                        if(isset($this->events, $this->events[$integration->post_title])){
                                                            echo esc_html($this->events[$integration->post_title]) ;
                                                        }
                                                        echo"<br>";
                                                        echo esc_html($integration->post_title);
                                                    echo"</td>";
    
                                                    echo"<td>";
                                                        $titleFields  =  isset($integration->ID) ? get_post_meta($integration->ID, 'titleFields', true) : NULL ;
                                                        # check and balance 
                                                        if(! empty($titleFields)){
                                                            if(empty($titleFields) OR ! is_array($titleFields)){
                                                                echo"ERROR : page title fields are empty.";
                                                            }else{
                                                                foreach ($titleFields as $key => $value){
                                                                    if(isset($this->eventsAndTitles[$integration->post_title][$value])){
                                                                        echo esc_html($this->eventsAndTitles[$integration->post_title][$value]);
                                                                    } else {
                                                                        echo esc_html($value);
                                                                    }
                                                                    echo"<br>";
                                                                }
                                                            }
                                                        } else {
                                                            echo"ERROR : title fields are empty.";
                                                        }
                                                    echo"</td>";
    
                                                    echo"<td>";
                                                        $bodyFields = isset($integration->ID) ? get_post_meta( $integration->ID, 'bodyFields', true ) : NULL;
                                                        # check and Balance 
                                                        if(! empty($bodyFields)){
                                                            if(empty($bodyFields) OR ! is_array($bodyFields)){
                                                                echo"ERROR : page body fields are empty.";
                                                            }else{
                                                                foreach($bodyFields as $key => $value){
                                                                    if(isset($this->eventsAndTitles[$integration->post_title][$value])){
                                                                        echo esc_html($this->eventsAndTitles[$integration->post_title][$value]);
                                                                    } else {
                                                                        echo esc_html($value);
                                                                    }
                                                                    echo"<br>";
                                                                }
                                                            }
                                                        } else {
                                                            echo"ERROR : body fields are empty.";
                                                        }
                                                    echo"</td>";
    
                                                    echo"<td>";
                                                        echo"<br>";
                                                        # Integration status 
                                                        if(isset($integration->post_status) AND $integration->post_status == 'publish'){
                                                            echo"<span title='Enable or disable the integrations'  onclick='window.location=\"" . ('admin.php?page=notionify&action=status&id=' . $integration->ID) . "\"'  class='a_activation_checkbox'> <input type='checkbox' name='status' checked=checked > </span>";
                                                        } else {
                                                            echo"<span title='Enable or disable the integrations'  onclick='window.location=\"" . admin_url('admin.php?page=notionify&action=status&id=' . $integration->ID) . "\"'  class='a_activation_checkbox'> <input type='checkbox' name='status' > </span>";
                                                        }
                                                        echo"<br> <br>";
                                                        echo"<span class='dashicons dashicons-cloud-upload' title='Test run the integration, create a page & send data to Notion.' style='cursor: pointer;'  onclick='window.location=\"" . admin_url('admin.php?page=notionify&action=testRun&id=' . $integration->ID) . "\"' >  </span>";
                                                    echo"</td>";
    
                                                echo"</tr>";
                                                $i ++ ;
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th> Notion Database Name </th>
                                        <th> Data Source </th>
                                        <th> Selected Fields for Title </th>
                                        <th> Selected Fields for Body</th>
                                        <th> Status </th>
                                    </tr>
                                </tfoot>
                            </table>
						</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables .ui-sortable -->
			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">
					<div class="postbox">
						<h2 class="hndle"><span>Notion API key</span></h2>
						<div class="inside">
                            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" >
                                <input type="hidden" name="action" value="notionify_saveAPIkey">
                                <input type="hidden" name="requestFrom" value="notionifyNotionAPIkey">
                                <!-- API input FIelds -->
                                <?php
                                    $notionifyNotionAPIkey = get_option('notionifyNotionAPIkey');
                                    if( $notionifyNotionAPIkey ){
                                        echo"<input type='text' name='notionifyNotionAPIkey' placeholder='Past your Notion API key here.' value='".esc_attr($notionifyNotionAPIkey)."' class='large-text' disabled />";
                                        echo"<br><b><i><a href='".admin_url('admin.php?page=notionify&action=deleteAPIkey')."' style='color:#FFB52E;'>Remove this API key</a></i></b>";
                                    } else {
                                        echo"<input type='text' name='notionifyNotionAPIkey' placeholder='Past your Notion API key here.' value='' class='large-text' />";
                                        echo"<br><br>";
                                        echo"<input class='button-secondary' type='submit' value='SAVE' />";
                                        echo"<b><i><a href='".admin_url('admin.php?page=notionify&action=APIkeyHelp')."' style='color:#00A300; float: right;'> Get Notion API key</a></i></b>";
                                    }
                                ?>
                                <!-- submit button -->
                            </form>
						</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables -->

                <?php  echo"<br><b><i><a href='".admin_url('admin.php?page=notionify&action=log')."' style='color:#5B84B1FF;'>log for good! this plugin's log page.</a></i></b>"; ?>

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->


<style>
    a.notionDeleteLink:hover{
        color: red;
        font-weight: bold;
    }
</style>

