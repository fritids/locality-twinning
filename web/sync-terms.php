<?php
exit;
require_once 'wp-load.php';

set_time_limit(0);

updatePosts();


syncMake();
syncModel();
syncClass();
syncYear();
echo 'sync done!';

function syncMake()
{
    global $wpdb;
    $makeTable = $wpdb->prefix . 'make';
    $termsTable = $wpdb->prefix . 'terms';
    $termTaxonomyTable = $wpdb->prefix . 'term_taxonomy';

    //$makeTable = 'wp_make_test';//test
    //$termsTable = 'wp_terms_test';//test
    //$termTaxonomyTable = 'wp_term_taxonomy_test';//test

    $makes = $wpdb->get_results("SELECT * FROM $makeTable");
    foreach($makes as $thismake)
    {
        $terms = $wpdb->get_row("SELECT term_id FROM $termsTable WHERE name = '$thismake->DivDesc'");
        $term_id = $terms->term_id;
        if(empty($term_id)){
            $wpdb->insert($termsTable,array('name' => $thismake->DivDesc,'slug' => sanitize_title($thismake->DivDesc)),array('%s','%s' ) );
            $term_id = $wpdb->insert_id;
        }
        $termTaxonomyCount = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) AS 'taxocount' FROM $termTaxonomyTable WHERE taxonomy = 'make' AND term_id = '$term_id'"));
        if(!$termTaxonomyCount){
            $wpdb->insert($termTaxonomyTable,array('term_id' => $term_id, 'taxonomy' => 'make'),array('%d','%s' ) );
        }
        $wpdb->update($makeTable,array('term_id' => $term_id), array('id' => $thismake->id) ,array('%d'), array('%d') );
    }
}

function syncModel()
{
    global $wpdb;
    $modelTable = $wpdb->prefix . 'model';
    $termsTable = $wpdb->prefix . 'terms';
    $termTaxonomyTable = $wpdb->prefix . 'term_taxonomy';

    //$modelTable = 'wp_model_test';//test
    //$termsTable = 'wp_terms_test';//test
    //$termTaxonomyTable = 'wp_term_taxonomy_test';//test

    $models = $wpdb->get_results("SELECT * FROM $modelTable");
    foreach($models as $thismodel)
    {
        $terms = $wpdb->get_row("SELECT term_id FROM $termsTable WHERE name = '$thismodel->ModelDesc'");
        $term_id = $terms->term_id;
        if(empty($term_id)){
            $wpdb->insert($termsTable,array('name' => $thismodel->ModelDesc,'slug' => sanitize_title($thismodel->ModelDesc)),array('%s','%s' ) );
            $term_id = $wpdb->insert_id;
        }
        $termTaxonomyCount = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) AS 'taxocount' FROM $termTaxonomyTable WHERE taxonomy = 'model' AND term_id = '$term_id'"));
        if(!$termTaxonomyCount){
            $wpdb->insert($termTaxonomyTable,array('term_id' => $term_id, 'taxonomy' => 'model'),array('%d','%s' ) );
        }
        $wpdb->update($modelTable,array('term_id' => $term_id), array('id' => $thismodel->id) ,array('%d'), array('%d') );
    }
}

function syncClass()
{
    global $wpdb;
    $classTable = $wpdb->prefix . 'wheels_class';
    $termsTable = $wpdb->prefix . 'terms';
    $termTaxonomyTable = $wpdb->prefix . 'term_taxonomy';

    //$classTable = 'wp_wheels_class_test';//test
    //$termsTable = 'wp_terms_test';//test
    //$termTaxonomyTable = 'wp_term_taxonomy_test';//test

    $classes = $wpdb->get_results("SELECT * FROM $classTable");
    foreach($classes as $thisclass)
    {
        $terms = $wpdb->get_row("SELECT term_id FROM $termsTable WHERE name = '$thisclass->name'");
        $term_id = $terms->term_id;
        if(empty($term_id)){
            $wpdb->insert($termsTable,array('name' => $thisclass->name,'slug' => sanitize_title($thisclass->name)),array('%s','%s' ) );
            $term_id = $wpdb->insert_id;
        }
        $termTaxonomyCount = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) AS 'taxocount' FROM $termTaxonomyTable WHERE taxonomy = 'class' AND term_id = '$term_id'"));
        if(!$termTaxonomyCount){
            $wpdb->insert($termTaxonomyTable,array('term_id' => $term_id, 'taxonomy' => 'class'),array('%d','%s' ) );
        }
        $wpdb->update($classTable,array('term_id' => $term_id), array('id' => $thisclass->id) ,array('%d'), array('%d') );
    }
}

function syncYear()
{
    global $wpdb;
    $yearlTable = $wpdb->prefix . 'year';
    $termsTable = $wpdb->prefix . 'terms';
    $termTaxonomyTable = $wpdb->prefix . 'term_taxonomy';

    //$yearlTable = 'wp_year_test';//test
    //$termsTable = 'wp_terms_test';//test
    //$termTaxonomyTable = 'wp_term_taxonomy_test';//test

    $years = $wpdb->get_results("SELECT * FROM $yearlTable");
    foreach($years as $thisyear)
    {
        $terms = $wpdb->get_row("SELECT term_id FROM $termsTable WHERE name = '$thisyear->YearDesc'");
        $term_id = $terms->term_id;
        if(empty($term_id)){
            $wpdb->insert($termsTable,array('name' => $thisyear->YearDesc,'slug' => sanitize_title($thisyear->YearDesc)),array('%s','%s' ) );
            $term_id = $wpdb->insert_id;
        }
        $termTaxonomyCount = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) AS 'taxocount' FROM $termTaxonomyTable WHERE taxonomy = 'vehicle-year' AND term_id = '$term_id'"));
        if(!$termTaxonomyCount){
            $wpdb->insert($termTaxonomyTable,array('term_id' => $term_id, 'taxonomy' => 'vehicle-year'),array('%d','%s' ) );
        }
        $wpdb->update($yearlTable,array('term_id' => $term_id), array('id' => $thisyear->id) ,array('%d'), array('%d') );
    }
}

function updatePosts()
{
    global $wpdb;
    $results = $wpdb->get_results("SELECT
                        wp_posts.ID,
                        wp_posts.post_title,
                        wp_posts.post_name,
                        wp_posts.post_type,
                        wp_postmeta.meta_key,
                        wp_postmeta.meta_value
                        FROM
                        wp_posts
                        INNER JOIN wp_postmeta ON wp_posts.ID = wp_postmeta.post_id
                        WHERE wp_postmeta.meta_key = 'uid' AND wp_posts.post_name LIKE '% %'");
    foreach($results as $row)
    {
        $wpdb->query( "UPDATE wp_posts SET post_name = '".url_title($row->post_title)."' WHERE ID = '".$row->ID."'" );
    }

    $results2 = $wpdb->get_results("SELECT DISTINCT ID, post_name
                    FROM wp_posts
                    GROUP BY post_name
                    HAVING count( post_name ) > 1
                    ");

    $postNames = "'";
    foreach($results2 as $row)
    {
        $postNames .= $row->post_name."','";
    }
    $postNames .= "'";


    $results3 = $wpdb->get_results("SELECT ID, post_name
                    FROM wp_posts
                    WHERE post_name IN($postNames)
                    ");

    $dupticateUpdate = 0;
    foreach($results3 as $key => $row)
    {
        if($key > 0)
        {
            $wpdb->query( "UPDATE wp_posts SET post_name = '".$row->post_name.'-'.$key."' WHERE ID = '".$row->ID."'" );
            $dupticateUpdate++;
        }
    }

    echo count($results) . ' Posts Updated <br>';
    echo count($results2) . ' Duplicate Posts Found <br>';
    echo $dupticateUpdate . ' Duplicate Posts Name updated <br>';
}
?>
