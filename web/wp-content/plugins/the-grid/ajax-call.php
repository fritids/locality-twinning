<?php
header ('Content-type: text/html; charset=utf-8');
require_once('../../../wp-load.php');
if($_REQUEST['hdn_lat']!=0 && $_REQUEST['hdn_lng']!=0){
    $address = $_REQUEST['dealer-location'];
    $lat = $_REQUEST['hdn_lat'];
    $lng = $_REQUEST['hdn_lng'];
    $radius = (isset($_REQUEST['hdn_distance']) && $_REQUEST['hdn_distance']!= 0)?$_REQUEST['hdn_distance']:'300';//295

    $now = date("Y-m-d H:i:s");
    $str_ext1 = ''; $str_ext2 = ''; $str_ext3 = '';
    $str_ext1 = " ,(1609.34 * 3956 * 2 * ASIN(SQRT(POWER(SIN(($lat - abs(grid_lat)) * pi()/180 / 2), 2) + COS($lat * pi()/180 ) * COS(abs(grid_lat) * pi()/180) * POWER(SIN(($lng - grid_lng) * pi()/180 / 2), 2) ))) AS distance ";//meter
    $str_ext2 = " WHERE {$wpdb->prefix}grid_markers.active = '1' AND {$wpdb->prefix}posts.post_status = 'publish' AND grid_expire_date > '".$now."'";
    $str_ext3 = " GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";//HAVING distance <= $radius

    $sql = "SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_date, {$wpdb->prefix}posts.post_title, {$wpdb->prefix}posts.post_content, {$wpdb->prefix}terms.term_id, {$wpdb->prefix}grid_data.*, {$wpdb->prefix}grid_markers.*  $str_ext1 FROM {$wpdb->prefix}posts
                INNER JOIN {$wpdb->prefix}grid_data ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}grid_data.post_id )
                INNER JOIN {$wpdb->prefix}term_relationships ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}term_relationships.object_id )
                INNER JOIN {$wpdb->prefix}term_taxonomy ON ( {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id )
                INNER JOIN {$wpdb->prefix}terms ON ( {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id )
                INNER JOIN {$wpdb->prefix}grid_markers ON ( {$wpdb->prefix}grid_markers.term_id = {$wpdb->prefix}terms.term_id )
                $str_ext2  $str_ext3
                ";

    $results = $wpdb->get_results($sql);

    $count = count($results);

    $json = '{ "count": '.$count.',"posts": [';
    if($count){
        $str = '';
        foreach($results as $result){
            $permalink = get_permalink($result->ID);
            if($result->external_link!=''){
                $permalink = $result->external_link;
            }
            $content = truncate(strip_tags($result->post_content), 110, true);
            $str .= '{"post_id": '.$result->post_id.', "category_id": "'.$result->term_id.'", "title": "'.truncate(strip_tags($result->post_title), 40, true).'", "content": "'.strip_tags( $content ).'", "permalink": "'.$permalink.'", "post_date": "'.recentDateFormat($result->post_date).'", "longitude": '.$result->grid_lng.', "latitude": '.$result->grid_lat.', "color": "'.$result->color.'", "icon": "'.$result->icon.'", "h_icon": "'.$result->h_icon.'" },';
        }
        $json .= substr($str,0,strlen($str)-1);
    }
    else{
        $json .= '{}';
    }
    $json .= ']}';
    echo $json;
}

function truncate($string, $length, $ellipsis = true) {
  // Count all the uppercase and other wider-than-normal characters
  $wide = strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));

  // Reduce the length accordingly
  $length = round($length - $wide * 0.2);

  // Condense all entities to one character
  $clean_string = preg_replace('/&[^;]+;/', '-', $string);
  if (strlen($clean_string) <= $length) return $string;

  // Use the difference to determine where to clip the string
  $difference = $length - strlen($clean_string);
  $result = substr($string, 0, $difference);

  if ($result != $string and $ellipsis) {
    $result = add_ellipsis($result);
  }

  return $result;
}

function add_ellipsis($string) {
  $string = substr($string, 0, strlen($string) - 3);
  return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
}

function recentDateFormat($date)
{
    $datetime1 = new \DateTime($date);
    $datetime2 = new \DateTime(current_time('mysql'));//current date time

    $interval = $datetime1->diff($datetime2);

    $years   = $interval->format('%y');
    $months   = $interval->format('%m');
    $days   = $interval->format('%d');
    $hours   = $interval->format('%h');
    $minutes = $interval->format('%i');
    $seconds = $interval->format('%s');

    if($years){
        return replaceFirst($datetime1->format('d M, Y'), '');
    }else if($months){
        return replaceFirst($datetime1->format('d M'), '');
    }else if($days){
        return replaceFirst($datetime1->format('d M'), '');
    }else if($hours){
        return replaceFirst($hours, '').'h';
    }else if($minutes){
        return replaceFirst($minutes, '').'m';
    }else{
        return $seconds.'s';
    }
}

function replaceFirst($input, $replacement){
    $fd = substr ($input, 0, 1);
    if($fd != 0){
        return $input;
    }
    else{
        $result = substr_replace($input, $replacement, 0, 1);
        return $result;
    }
}
?>