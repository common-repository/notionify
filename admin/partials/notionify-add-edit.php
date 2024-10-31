<?php
/**
 * This New and Edit Notion integration view page.
 *
 * @link       https://profiles.wordpress.org/javmah
 * @since      1.0.0
 * @package    Notion
 * @subpackage Notion/admin/partials
 */
?>
<div class="wrap"  id="notionInside">
	<h1 v-if="currentPage == 'new' "> Create New Notion Integration        </h1>  
    <h1 v-if="currentPage == 'edit' ">Edit Notion Integration, ID - {{id}} </h1>
   
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h2 ><span><?php esc_attr_e( 'Main Content Header', 'notionify' ); ?></span></h2>
                        <!-- Inside div start  -->
						<div class="inside" >
                            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" >
                                <input type="hidden" name="action"       value="notionify_savePost">
                                <input type="hidden" name="requestFrom"  v-model="currentPage" >
                                <input type="hidden" name="ID"           v-model="id" >
                                <!--  Content Field Table starts -->
                                <table class="widefat">
                                    <thead>
                                        <tr>
                                            <th class="row-title"><b>NAME</b></th>
                                            <th class="row-title" style='min-width:50%;'><b> VALUE </b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="alternate">
                                            <td class="row-title">Select a Notion DATABASE or PAGE :</td>
                                            <td> 
                                                <select name='selectedDbPage' id='selectedDbPage' v-model='selectedDbPage' id='cars' style='min-width:80%;' >
                                                    <option :value="id" v-for="(details, id) in dbPages" > {{details.name}} </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="row-title">Select a WordPress or Plugin Event :</td>
                                            <td> 
                                                <select name="event" id="selectedEvent" v-model="selectedEvent"  class="regular-text" size="5" style='min-width:80%;'>
                                                    <option :value="value" v-for="(name,value, index)  in events" > {{name}} </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="alternate">
                                            <td class="row-title">Select Event's Data for Title :</td>
                                            <td> 
                                                <span v-if="selectedEvent &&  eventsAndTitles[selectedEvent]"> 
                                                    <select name="titleFields[]" multiple  v-model="titleFields" id="titleFieldsID"  class="regular-text" size="7" style='min-width:80%;'>
                                                        <option :value="value" v-for="(name,value, index)  in eventsAndTitles[selectedEvent]" > {{name}} </option>
                                                    </select>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="row-title">Select Event's Data for Body :</td>
                                            <td> 
                                                <span v-if="selectedEvent && eventsAndTitles[selectedEvent]"> 
                                                    <select name="bodyFields[]" multiple v-model="bodyFields" id="bodyFieldsID"  class="regular-text" size="15" style='min-width:80%;'>
                                                        <option :value="value" v-for="(name,value, index)  in eventsAndTitles[selectedEvent]" > {{name}} </option>
                                                    </select>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class='alternate'>
                                            <td class="row-title">Select List Style :</td>
                                            <td> 
                                                <select name="listStyle" v-model="listStyle" id="cars" style="min-width:80%;">
                                                     <option value="normal">   normal   </option>
                                                    <option value="circle">    circle   </option>
                                                    <option value="square">    square   </option>
                                                    <option value="number">    number   </option>
                                                    <option value="checkbox">  checkbox </option>
                                                    <option value="table">     table    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="row-title"> Integration Status : Enable | Disable</td>
                                            <td> 
                                                <input type="checkbox" id="activationStatus" name="activationStatus"  checked>
                                            </td>
                                        </tr>
                                        <tr class="alternate">
                                            <td class="row-title"></td>
                                            <td> 
                                                <input type="submit" class="button-primary" type="submit" name="save" value="SAVE"> &nbsp;&nbsp;
                                                <!-- cancel link  -->
                                                <a class="button-secondary" href="<?php echo admin_url('admin.php?page=notionify')?>" class="button-secondary"> CANCEL </a>        
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="row-title"> <b> NAME  </b></th>
                                            <th class="row-title"> <b> VALUE </b></th>
                                        </tr>
                                    </tfoot>
                                </table> 
                                <!-- Table ends -->
                            </form>
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

						<h2 class="hndle"><span>Information Panel</span> <span class="dashicons dashicons-info-outline" title='Read before create new Integration.'></span></h2>

						<div class="inside">
							<p v-if="! dbPages"><span style='color:red;'> * ERROR </span> : Notion API response error, Please try after few minutes. Check you internet connection. Check API access of this plugin. Reload the page again.</p>

							<p><i> If you don't select any Notion database plugin will not work & Integration will not be saved. </i></p>

							<p><i> If the page title or body fields is empty. The integration will not be saved. </i></p>

							<p><i> Dear user, Notion API has a text limit of <b>165 words or 2000 characters</b> per request on rich text, So please don't select all the event fields. </i></p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->
			</div>
			<!-- #postbox-container-1 .postbox-container -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear">
	</div>
	<!-- #poststuff -->
</div> <!-- .wrap -->

<script>
    //  My Small app 
    const notionAppComponent = {
        data(){
            return{
                id              : "",
                currentPage     : "",
                dbPages         : "",
                events          : "",
                eventsAndTitles : "",
                selectedDbPage  : "",
                selectedEvent   : "",
                titleFields     : [],
                bodyFields      : [],
                listStyle       : ""
            }
        },
        methods:{
            testFunc(){
               
            }
        },
        mounted: function(){
            //  Check to see current page exist or not 
            if(! notionifyFrontend.currentPage){
                console.log( "ERROR : currentPage is null or empty.");
            } 

            if(notionifyFrontend.currentPage == 'new'){
                // Setting current page new or edit
                this.currentPage  = notionifyFrontend.currentPage;
                // Setting notion database and Page 
                if(notionifyFrontend.dbPages && Object.keys(notionifyFrontend.dbPages).length === 0){
                    console.log( "ERROR : dbPages object is null or empty.");
                    // show a Friendly Message 
                } else {
                    this.dbPages  = notionifyFrontend.dbPages;
                }
                // Setting wordPress events 
                if(notionifyFrontend.events && Object.keys(notionifyFrontend.events).length === 0){
                    console.log( "ERROR : events object is null or empty.");
                } else {
                    this.events  = notionifyFrontend.events;
                }
                // setting eventsAndTitles
                if(notionifyFrontend.eventsAndTitles && Object.keys( notionifyFrontend.eventsAndTitles).length === 0){
                    console.log( "ERROR : eventsAndTitles object is null or empty.");
                } else {
                    this.eventsAndTitles = notionifyFrontend.eventsAndTitles;
                }
            };

            if(notionifyFrontend.currentPage == 'edit'){
                // Setting id 
                if(notionifyFrontend.id && ! isNaN(notionifyFrontend.id)){
                    this.id  = notionifyFrontend.id;
                } else {
                    console.log("ERROR : ID is null or not a number.");
                }
                // Setting current page new or edit
                if(notionifyFrontend.currentPage){
                    this.currentPage  = notionifyFrontend.currentPage;
                } else {
                    console.log("ERROR : currentPage is empty.");
                }
                // Setting database name
                if(notionifyFrontend.selectedDbPage){
                    this.selectedDbPage  = notionifyFrontend.selectedDbPage;
                } else {
                    console.log("ERROR : selectedDbPage is empty.");
                }
                // Setting event 
                if(notionifyFrontend.selectedEvent){
                    this.selectedEvent  = notionifyFrontend.selectedEvent;
                } else {
                    console.log("ERROR : selectedEvent is empty.");
                }
                // Setting list style
                if(notionifyFrontend.listStyle){
                    this.listStyle  = notionifyFrontend.listStyle;
                } else {
                    console.log("ERROR : listStyle is empty.");
                }
                // Setting selected title fields
                if(notionifyFrontend.titleFields){
                    this.titleFields  = JSON.parse(notionifyFrontend.titleFields);
                } else {
                    console.log("ERROR : titleFields is empty.");
                }
                // Setting selected body fields
                if(notionifyFrontend.bodyFields){
                    this.bodyFields  = JSON.parse(notionifyFrontend.bodyFields);
                } else {
                    console.log("ERROR : bodyFields is empty.");
                }
                // Setting notion database and Page 
                if(notionifyFrontend.dbPages && Object.keys(notionifyFrontend.dbPages).length === 0){
                    console.log("ERROR : dbPages object is null or empty." );
                } else {
                    this.dbPages  = notionifyFrontend.dbPages;
                }
                // Setting wordPress events 
                if(notionifyFrontend.events && Object.keys(notionifyFrontend.events).length === 0){
                    console.log("ERROR : events object is null or empty." );
                } else {
                    this.events  = notionifyFrontend.events;
                }
                // setting eventsAndTitles
                if(notionifyFrontend.eventsAndTitles && Object.keys(notionifyFrontend.eventsAndTitles).length === 0){
                    console.log("ERROR : eventsAndTitles object is null or empty.");
                } else {
                    this.eventsAndTitles = notionifyFrontend.eventsAndTitles;
                }
            };
        }
    };
    //  assigning the value 
    const notionApp = Vue.createApp(notionAppComponent).mount('#notionInside');
    //  The end 

</script>

