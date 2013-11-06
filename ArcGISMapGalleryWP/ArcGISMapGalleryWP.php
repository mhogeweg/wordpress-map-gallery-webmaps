<?php

/*
Plugin Name: MapGallery
Plugin URI: http://www.esri.com/
Description: ArcGIS Map Gallery for WordPress
Version: 0.1
Author: Marten Hogeweg
Author URI: http://martenhogeweg.blogspot.com/
License: Apache 2.0
*/

function agolMapGallery_activation() {
}

register_activation_hook(__FILE__, 'agolMapGallery_activation');

function agolMapGallery_deactivation() {
}

register_deactivation_hook(__FILE__, 'agolMapGallery_deactivation');

add_action('wp_enqueue_scripts', 'agolMapGallery_scripts');

function agolMapGallery_scripts() {
    global $post;
	
    wp_enqueue_script('agsapi_js', 'http://serverapi.arcgisonline.com/jsapi/arcgis/?v=2.0');
    wp_enqueue_script('layout_js', plugins_url('js/ArcGISMapGalleryWP.js', __FILE__));
}

add_action('wp_enqueue_scripts', 'agolMapGallery_styles');

function agolMapGallery_styles() {
    wp_enqueue_style('ArcGISMapGalleryWP_css', plugins_url('css/ArcGISMapGalleryWP.css', __FILE__));
}
/*
add_filter("the_content", "displayAgolMapGallery");

function displayAgolMapGallery($content) {
	$postid = get_the_ID();
	
	// get the settings for this gallery
	//
	$portalbaseurl = get_post_meta($postid, "portalbaseurl", true);
	$restbaseurl = get_post_meta($postid, "restbaseurl", true);
	$groupid = get_post_meta($postid, "groupid", true);
    $plugins_url = plugins_url();

	// create the piece of HTML to insert into the page where the user indicated the gallery should appear
	// separator for the gallery location is |gallery|
	//
    $snippet = '<div id="featuredMaps" class="soria esri clearfix eoe-wp-wrapper">
		<div dojotype="dijit.layout.ContentPane" class="scrollPaneParent rounded" style="overflow-x:hide; overflow-y:hide;">
			<div id="scrollPaneRecent" align="center" style="overflow: hidden; position: relative; margin-left: 10px;">
				<table class="scrollContent" cellpadding="0" cellspacing="0">
					<tr id="mapsAndApps" height="120px;" valign="top">

					</tr>
				</table>
			</div>
			<div id="scrollPaneLeft" style="display: block" class="scroll scrollPrev scrollDisabled"></div>
			<div id="scrollPaneRight" style="display: block" class="scroll scrollNext"></div>
		</div>
	</div>
	<input type="text" size="100" name="portalbaseurl" id="portalbaseurl" value=' . $portalbaseurl . ' />
	<input type="text" size="100" name="restbaseurl" id="restbaseurl" value=' . $restbaseurl . ' />
	<input type="text" size="100" name="groupid" id="groupid" value=' . $groupid . ' />
	<input type="text" size="100" name="plugins_url" id="plugins_url" value="' . $plugins_url . '" />
<script type="text/javascript>	
window.esriGeowConfig = {
	groupId: "' . $groupid . '",
	baseUrl: "' . $portalbaseurl . '",
	restBaseUrl: "' . $restbaseurl . '",
	pluginPath: "' . $plugins_url . '"
};
</script>';
	
	// replace the separator in the content with the HTML
	//
	$html = str_replace("|gallery|",$snippet,$content);
	
    return $html;
}

add_action('init', 'registerAgolMapGallery');

function registerAgolMapGallery() {
    $labels = array(
        'menu_name' => _x('Map Galleries', 'agolMapGallery'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'ArcGIS Map Galleries',
        'supports' => array('title', 'editor'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('arcgis_map_galleries', $args);
}
*/

/*
add_action('add_meta_boxes', 'agolMapGallery_meta_box');

function agolMapGallery_meta_box() {

    add_meta_box("agol_map_gallery", "Configure Gallery", 'agolMapGallery_configuration', "arcgis_map_galleries", "normal");
}

function agolMapGallery_configuration() {
    $postid = get_the_ID();
	
	// get the portal base URL
    $portalbaseurl = get_post_meta($postid, "portalbaseurl", true);
    $portalbaseurl = ($portalbaseurl != '') ? json_decode($portalbaseurl) : 'http://www.arcgis.com/home';

	// get the rest API base URL
    $restbaseurl = get_post_meta($postid, "restbaseurl", true);
    $restbaseurl = ($restbaseurl != '') ? json_decode($restbaseurl) : 'http://www.arcgis.com/sharing';

	// get the group id for this gallery
	$groupid = get_post_meta($postid, "groupid", true);
    $groupid = ($groupid != '') ? json_decode($groupid) : 'ce7a94a2f30441d3b5f4b23741e5f291';

    // Use nonce for verification
    $html = '<input type="hidden" name="agolMapGallery_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

    $html .= "<table class='form-table'>
		  <tr valign='top'>
            <td><label for='portalurl'>Portal Home URL <br/>(e.g. http://www.arcgis.com/home)</label></td>
            <td><input name='portalbaseurl' id='portalbaseurl' type='text' size='100' value='" . $portalbaseurl . "'  /></td>
          </tr>
          <tr valign='top'>
            <td><label for='restbaseurl'>Portal Home URL <br/>(e.g. http://www.arcgis.com/sharing)</label></td>
            <td><input name='restbaseurl' id='restbaseurl' type='text' size='100' value='" . $restbaseurl . "'  /></td>
          </tr>
          <tr valign='top'>
            <th style=''><label for='groupid'>GroupID</label></th>
            <td><input name='groupid' id='groupid' type='text' size='100' value='" . $groupid . "'  /></td>
          </tr>
        </table>";

    echo $html;
}
*/
/* Save Map Gallery Settings to database */
/*add_action('save_post', 'agolMapGallery_save_info');

function agolMapGallery_save_info($post_id) {


    // verify nonce
    if (!wp_verify_nonce($_POST['agolMapGallery_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('arcgis_map_galleries' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

        $portalbaseurl = (isset($_POST['portalbaseurl']) ? $_POST['portalbaseurl'] : '');
        $portalbaseurl = strip_tags(json_encode($portalbaseurl));
        update_post_meta($post_id, "portalbaseurl", $portalbaseurl);

        $restbaseurl = (isset($_POST['restbaseurl']) ? $_POST['restbaseurl'] : '');
        $restbaseurl = strip_tags(json_encode($restbaseurl));
        update_post_meta($post_id, "restbaseurl", $restbaseurl);

        $groupid = (isset($_POST['groupid']) ? $_POST['groupid'] : '');
        $groupid = strip_tags(json_encode($groupid));
        update_post_meta($post_id, "groupid", $groupid);

       
    } else {
        return $post_id;
    }
}
*/
add_shortcode('arcgismapgallery', 'renderArcGISMapGalleryShortCode');

function renderArcGISMapGalleryShortCode($atts) {

	// get the settings for this gallery
	//
	extract( shortcode_atts( array(
	      'groupid' => 'ce7a94a2f30441d3b5f4b23741e5f291',
	      'restbaseurl' => 'http://www.arcgis.com/sharing/',
		  'portalbaseurl' => 'http://geoss.maps.arcgis.com/home/'
     ), $atts ) );
    $default_thumbnail = plugins_url( 'images/defaultThumb.png' , __FILE__ );
	$postid = get_the_ID();
	
	// create the piece of HTML to insert into the page where the user indicated the gallery should appear
	// separator for the gallery location is |gallery|
	//
    $html = '
	<script type="text/javascript">	
	window.esriGeowConfig = {
		groupId: "' . $groupid . '",
		baseUrl: "' . $portalbaseurl . '",
		restBaseUrl: "' . $restbaseurl . '",
		default_thumbnail: "' . $default_thumbnail . '"
	};
	initFeaturedMapsAndApps();

	</script>
	<div id="featuredMaps" class="soria esri clearfix eoe-wp-wrapper">
		<div dojotype="dijit.layout.ContentPane" class="scrollPaneParent rounded" style="overflow-x:hide; overflow-y:hide;">
			<div id="scrollPaneRecent" align="center" style="overflow: hidden; position: relative; margin-left: 10px;">
				<table class="scrollContent" cellpadding="0" cellspacing="0">
					<tr id="mapsAndApps" height="120px;" valign="top">

					</tr>
				</table>
			</div>
			<div id="scrollPaneLeft" style="display: block" class="scroll scrollPrev scrollDisabled"></div>
			<div id="scrollPaneRight" style="display: block" class="scroll scrollNext"></div>
		</div>
	</div>';
	
	return $html;	 
}
?>