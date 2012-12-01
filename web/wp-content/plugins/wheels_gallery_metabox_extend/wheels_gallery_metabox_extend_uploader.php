<?php
include('../../../wp-load.php');
if( !current_user_can('upload_files') ) die('Access denied');

$post_id = $_GET['post_id'];

// place js config array for plupload
$plupload_init = array(
    'runtimes' => 'html5,silverlight,flash,html4',
    'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
    'container' => 'plupload-upload-ui', // will be adjusted per uploader
    'drop_element' => 'drag-drop-area', // will be adjusted per uploader
    'file_data_name' => 'async-upload', // will be adjusted per uploader
    'multiple_queues' => true,
    'max_file_size' => '2M',
    'url' => admin_url('admin-ajax.php'),
    'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
    'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
    'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
    'multipart' => true,
    'urlstream_upload' => true,
    'multi_selection' => false, // will be added per uploader
// additional post data to send to our ajax hook
    'multipart_params' => array(
        '_ajax_nonce' => "", // will be added per uploader
        'action' => 'plupload_action', // the ajax action name
        'imgid' => 0, // will be added per uploader
        'post_id' => $post_id
    )
);

$id = "img1"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1” then $_POST[“img1”] will have all the image urls
$svalue = ""; // this will be initial value of the above form field. Image urls.
$multiple = true; // allow multiple files upload
$width = null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
$height = null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
?>

<!--[if lt IE 9]>
<script type="text/javascript" src="json-js/json2.js"></script>
<script type="text/javascript" src="json-js/json_parse.js.js"></script>
<![endif]-->

<script type="text/javascript">
    var base_plupload_config=<?php echo json_encode($plupload_init); ?>;
    var ajax_delete_url='<?php echo plugins_url('delete-image.php', __FILE__) ?>';
</script>
<link rel='stylesheet' href='<?php echo admin_url()?>/load-styles.php?c=1&amp;dir=ltr&amp;load=wp-admin,media&amp;ver=84ec3b382256370ca5cc55ba806e0b62' type='text/css' media='all' />
<link rel='stylesheet' id='colors-css'  href='<?php echo admin_url()?>/css/colors-fresh.css?ver=20111206' type='text/css' media='all' />
<style type="text/css">@import "css.css";</style>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/jquery.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/plupload/plupload.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/plupload/plupload.html5.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/plupload/plupload.flash.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/plupload/plupload.html4.js'?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js.js', __FILE__)?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        if( jQuery.browser.msie && jQuery.browser.version < 9 )
        {
            jQuery('#img1plupload-upload-ui input[type="file"]').parent().css({'top':'-12px', 'left': '-12px'});
            jQuery('#img1plupload-upload-ui input[type="file"]').css({'top':'-14px', 'left': '-25px'});

        } else if( jQuery.browser.msie && jQuery.browser.version > 8 )
        {
            jQuery('#img1plupload-upload-ui input[type="file"]').parent().css({'top':'-12px', 'left': '-12px'});
            jQuery('#img1plupload-upload-ui input[type="file"]').css({'top':'-12px', 'left': '-12px'});
        }
    });
</script>
<a href="wheels_gallery_metabox_extend_list.php?post_id=<?php echo $post_id?>" class="tab first_elm">Gallery</a> <?php if( current_user_can('upload_files') ){?> <a class="active">Uploader</a><?php }?><br />
<form action="">
<table id="sortable" class="table-3" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
        <th width="100%">Upload Images</th>
    </tr>
    </thead>
    <tbody>
        <tr class="row">
            <td>
                <input type="hidden" id="this_post_id" value="<?php echo $post_id?>" />
                <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
                <div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
                    <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" />
                    <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
                    <?php if ($width && $height): ?>
                    <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
                    <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
                    <?php endif; ?>
                    <div class="filelist"></div>
                </div>
            </td>
        </tr>
        <tr class="row">
            <td>
                <div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">

                </div>
            </td>
        </tr>
        <tr class="row">
            <td>
                <a href="wheels_gallery_metabox_extend_list.php?post_id=<?php echo $post_id?>" class="button-primary button-primary-modified">Finish My Upload</a>
            </td>
        </tr>
    </tbody>
</table>
</form>