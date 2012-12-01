<?php
// Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'wheels_gallery_noncename' );

    // The actual fields for data entry
    echo '<input type="text" name="vehicle" value="" id="vehicle_id">';
    echo '<p class="hide-if-no-js">';
    echo '<a class="thickbox" id="set-post-thumbnail" href="'.plugins_url( 'wheels-vehicle-finder.php' , __FILE__ ).'?post_id=6&amp;type=image&amp;TB_iframe=1&amp;width=640&amp;height=249" title="Set featured image">';
    echo 'Set featured image';
    echo '</a></p>';
?>
<!--
<tr valign="top">
<th scope="row">Upload Image</th>
<td><label for="upload_image">
<input id="upload_image" type="text" size="36" name="upload_image" value="" />
<input id="upload_image_button" type="button" value="Upload Image" />
<br />Enter an URL or upload an image for the banner.
</label></td>
</tr>
-->
<!--
<script type="text/javascript">
jQuery(document).ready(function() {
	window.original_send_to_editor = window.send_to_editor;
	jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.original_send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#upload_image').val(imgurl);
		tb_remove();
	}
});
</script>
-->