<?php
require_once('../../../wp-load.php');

if(!empty($_POST['hdn-the-grid-edit-category'])){
    $sql = "SELECT *  FROM {$wpdb->prefix}grid_markers
            WHERE term_id != '".$_POST['tag_ID']."' AND ( color = '".$_POST['color']."' OR icon = '".$_POST['icon']."' OR h_icon = '".$_POST['h_icon']."' )
            ";
    $results = $wpdb->get_results($sql);

    $in_use_color = 'false';
    $in_use_icon = 'false';
    $in_use_hicon = 'false';

    if(!empty($results)){
        foreach($results as $r){
            if($r->color == $_POST['color']){
                if($in_use_color == 'false'){
                    $in_use_color = 'true';
                }
            }
            if($r->icon == $_POST['icon']){
                if($in_use_icon == 'false'){
                    $in_use_icon = 'true';
                }
            }
            if($r->h_icon == $_POST['h_icon']){
                if($in_use_hicon == 'false'){
                    $in_use_hicon = 'true';
                }
            }
        }
    }
    $json = '{ "in_use_color": '.$in_use_color.', "in_use_icon": '.$in_use_icon.', "in_use_hicon": '.$in_use_hicon.'  }';
    echo $json;
    exit;
}

$grid_home_page_url = get_option("grid_home_page_url");
$grid_sign_up_url = get_option("grid_sign_up_url");
$grid_default_map_location = get_option("grid_default_map_location");

$sql = "SELECT *  FROM {$wpdb->prefix}grid_markers
            Inner Join {$wpdb->prefix}terms ON ({$wpdb->prefix}grid_markers.term_id = {$wpdb->prefix}terms.term_id )
            WHERE active = '1'
            ";

$results = $wpdb->get_results($sql);
$count = count($results);

$json = '{ "count": '.$count.', "grid_home_page_url": "'.$grid_home_page_url.'", "grid_sign_up_url": "'.$grid_sign_up_url.'", "grid_default_map_location": "'.$grid_default_map_location.'", "cats": [';
if($count){
    $str = '';
    foreach($results as $result){
        $str .= '{"category_id": '.$result->term_id.', "category_name": "'.$result->name.'", "color": "'.$result->color.'", "icon": "'.$result->icon.'", "hicon": "'.$result->hicon.'" },';
    }
    $json .= substr($str,0,strlen($str)-1);
}
else{
    $json .= '{}';
}
$json .= ']}';
echo $json;

?>

