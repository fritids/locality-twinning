<?php include('../../../wp-load.php');
if( !current_user_can('upload_files') ) die('Access denied');
?>

<?php
global $wpdb;
$post_id = $_GET['post_id'];
$table = $wpdb->prefix . WHEELS_GALLEY_METABOX_EXTEND_TABLE;
if($_POST['ajax_submit']){
    $rows = $_POST['select'];
    $sql = array();
    if(!empty($rows)){
       foreach($rows as $key => $value){
           $weight = $key;
           $id = $_POST['select'][$key];
           $title = $_POST['title'][$key];
           $video = $_POST['video'][$key];
           $sql = "UPDATE $table SET title = '$title', video = '$video', weight = '$weight' WHERE id = '$id'";
           $wpdb->query($sql);
       }
    }
    echo json_encode(array('status'=>'success'));
    exit;
}
$results = $wpdb->get_results("SELECT * FROM $table WHERE post_id = '$post_id' ORDER BY weight ASC");
?>
<link rel='stylesheet' href='<?php echo admin_url()?>/load-styles.php?c=1&amp;dir=ltr&amp;load=wp-admin,media&amp;ver=84ec3b382256370ca5cc55ba806e0b62' type='text/css' media='all' />
<link rel='stylesheet' id='colors-css'  href='<?php echo admin_url()?>/css/colors-fresh.css?ver=20111206' type='text/css' media='all' />
<style type="text/css">@import "css.css";</style>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/jquery.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/ui/jquery.ui.core.min.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/ui/jquery.ui.widget.min.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/ui/jquery.ui.mouse.min.js'?>"></script>
<script type="text/javascript" src="<?php echo site_url().'/wp-includes/js/jquery/ui/jquery.ui.sortable.min.js'?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js.js', __FILE__)?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery("#sortable tbody").sortable({
            handle: 'img'
        });

        jQuery("#list-form").submit(function(){
            jQuery.ajax({
                url: '<?php echo plugins_url('wheels_gallery_metabox_extend_list.php', __FILE__)?>',
                type: 'post',
                dataType: 'json',
                data: jQuery('#list-form').serialize(),
                success: function(json){
                    if(json.status == 'success'){
                        alert('Saved');
                        sync_gallery_metabox();
                        parent.tb_remove();
                    }
                }
           });
           return false;
        });
        var ajax_delete_url='<?php echo plugins_url('delete-image.php', __FILE__) ?>';
        jQuery(".delete-image").click(function(){
            var this_elm = jQuery(this);
            var id = this_elm.attr('rel');
            var post_id = this_elm.attr('rev');
            var alert = confirm('Do you want to delete?');
            if(alert){
                jQuery.ajax({
                    url: ajax_delete_url,
                    data: 'id='+id+'&post_id='+post_id,
                    type:'post',
                    async: false,
                    dataType:'json',
                    success: function(json){
                        if(json.status == 'success'){
                            jQuery(this_elm).parent().parent().remove();
                            sync_gallery_metabox();
                        }
                    }
                });
            }
        });
        sync_gallery_metabox();
    });
</script>

<a class="active first_elm">Gallery</a> <?php if( current_user_can('upload_files') ){?> <a class="tab" href="wheels_gallery_metabox_extend_uploader.php?post_id=<?php echo $post_id?>">Uploader</a><?php }?><br />
<div class="clear"></div>
<form action="" method="post" id="list-form">
    <table id="sortable" class="table-3" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th width="10%">Image</th>
                <th width="80%">Title</th>
                <th width="10%">Delete</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($results as $row){?>
            <tr class="row">
                <td><img class="image" src="<?php echo $row->url?>" alt="" width="100" height="100"></td>
                <td>
                    <label>Title:</label> <input type='text' name='title[]' value="<?php echo stripcslashes($row->title)?>" /><br />
                    <label>Video:</label> <input type='text' name='video[]' value="<?php echo $row->video?>" />
                </td>
                <td><br /><a href="#" class="delete-image" rel="<?php echo $row->id?>" rev="<?php echo $row->file?>">Delete</a> <input type='hidden' name='select[]' value="<?php echo $row->id?>" title="Photo - <?php echo $row->id?>" /></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <br />
    <input type="hidden" name="ajax_submit" value="true" />
    <input type="submit" value="Save and Back to editor" class="button-primary" />
</form>