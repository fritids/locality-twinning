<?php
namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';

class Post extends Base
{
    public function getById($postId)
    {
        $prefix = $this->prefix;
        $sql = $this->db->prepare("SELECT * FROM {$prefix}posts WHERE ID = %d LIMIT 1", $postId);
        $post = $this->db->get_row($sql);

        return $post;
    }

    public function getAll($options = array())
    {
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10,
            'term' => '',
            'taxonomy' => 'news-category',
            'term2' => '',
            'taxonomy2' => '',
            'popularity_field' => 'wheels_post_popularity',
            'count' => false,
            'except' => '',
            'custom_field' => false
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;
        $custom_field_table = $prefix . 'wheels_custom_data';

        $query = "SELECT * FROM " . $this->db->posts;
        if ($args['count'] == true) $query = "SELECT COUNT(ID) as num_rows FROM " . $this->db->posts;
        if ($args['type'] == 'popular') {
            $query .= " LEFT JOIN {$prefix}postmeta ON({$prefix}posts.ID = {$prefix}postmeta.post_id)";
        }

        if ($args['custom_field']) {
            $query .= " LEFT JOIN $custom_field_table ON({$prefix}posts.ID = $custom_field_table.post_id)";
        }

        if (!empty($args['term'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships ON ({$prefix}posts.ID = {$prefix}term_relationships.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy ON ({$prefix}term_relationships.term_taxonomy_id = {$prefix}term_taxonomy.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms ON ({$prefix}term_taxonomy.term_id = {$prefix}terms.term_id)";
        }

        if (!empty($args['term2'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships AS tr ON ({$prefix}posts.ID = tr.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms AS t ON (tt.term_id = t.term_id)";
        }

        $query .= " WHERE 1=1";

        if (!empty($args['term'])) {
            $query .= " AND {$prefix}terms.slug = '" . $args['term'] . "'";
            $query .= " AND {$prefix}term_taxonomy.taxonomy = '" . $args['taxonomy'] . "'";
        }

        if (!empty($args['term2'])) {
            $query .= " AND t.slug = '" . $args['term2'] . "'";
            $query .= " AND tt.taxonomy = '" . $args['taxonomy2'] . "'";
        }

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $args['post_type'] . "'";

        if (!empty($args['except'])) {
            $query .= " AND {$prefix}posts.ID NOT IN (" . $args['except'] . ")";
        }

        if ($args['type'] == 'popular') {
            $query .= " AND {$prefix}postmeta.meta_key = '" . $args['popularity_field'] . "'";
            $query .= " ORDER BY CAST({$prefix}postmeta.meta_value AS UNSIGNED) DESC, {$prefix}posts.post_date DESC";
        } else {
            $query .= " ORDER BY {$prefix}posts.post_date DESC";
        }

        if ($args['count'] == false) {
            $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";
        }
        $results = $this->db->get_results($this->db->prepare($query));
        return $results;
    }

    public function getCustomValues($postId)
    {
        if (empty($postId)) {
            return false;
        }

        $sql = $this->db->prepare("SELECT * FROM " . $this->db->prefix . "wheels_custom_data WHERE post_id = %d", $postId);
        $row = $this->db->get_row($sql);

        return $row;
    }

    public function setMeta($postId, $key, $value)
    {
        $data = array('meta_value' => maybe_serialize($value));
        $where = array('post_id' => $postId, 'meta_key' => $key);

        $this->db->update('wp_postmeta', $data, $where);
    }

    public function getMeta($postId, $key)
    {
        $meta = $this->getPostMetaRow($postId, $key);
        return $meta->meta_value;
    }

    protected function getPostMetaRow($postId, $key)
    {
        $sql = $this->db->prepare("SELECT meta_id, post_id, meta_key, meta_value
                                    FROM wp_postmeta
                                    WHERE post_id = %d
                                    AND meta_key = %s", $postId, $key);

        $meta = $this->db->get_row($sql);
        return $meta;
    }

    public function getVehicleReviewData($postID)
    {
        $vehicle_review_table = $this->db->prefix . 'wheels_vehicle_review';
        $sql = $this->db->prepare("SELECT * FROM $vehicle_review_table WHERE post_id = %d", $postID);

        $vehicleData = $this->db->get_row($sql);
        return $vehicleData;
    }

    public function getSecondOpinionData($postID){
        $second_opinion_table = $this->db->prefix . 'wheels_second_opinion';
        $sql = $this->db->prepare("SELECT * FROM $second_opinion_table WHERE post_id = %d", $postID);

        $vehicleData = $this->db->get_row($sql);
        return $vehicleData;
    }


    public function getPostsForTaxonomy($id, $taxonomyOptions)
    {
        if (empty($id) || empty($taxonomyOptions)) {
            return false;
        }

        $terms = get_the_terms($id, $taxonomyOptions['taxonomy']);
        if ($terms && !is_wp_error($terms)) {
            $keys = array_keys($terms);
            $term = $terms[$keys[0]];
            $data = $this->getAll(array('post_type' => $taxonomyOptions['post_type'], 'term' => $term->slug, 'taxonomy' => $term->taxonomy, 'except' => $id, 'limit' =>$taxonomyOptions['limit']));
            return array('name' => $term->name, 'data' => $data);
        }
    }

     public function getPostByVehicleId($vehicleId, $postType = 'reviews')
    {
        $prefix = $this->prefix;
        $query = "SELECT {$prefix}posts.*, {$prefix}wheels_custom_data.*, wp_wheels_vehicle_review.star_rating, wp_wheels_vehicle_review.user_rating FROM " . $this->db->posts;
        $query .= " INNER JOIN {$prefix}wheels_custom_data ON {$prefix}posts.ID = {$prefix}wheels_custom_data.post_id";
        $query .= " INNER JOIN {$prefix}wheels_vehicle_review ON {$prefix}posts.ID = {$prefix}wheels_vehicle_review.post_id";
        $query .= " WHERE 1=1";

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $postType . "'";
        $query .= " AND {$prefix}wheels_custom_data.vehicle_id_1 = '" . $vehicleId . "'";

        $query .= " ORDER BY {$prefix}posts.post_date DESC";

        $results = $this->db->get_row($this->db->prepare($query));

        if ($results) {
            return $results;
        }
        return false;
    }

     public function getSecondOpinionByVehicleId($vehicleId, $postType = 'second-opinion')
    {
        $prefix = $this->prefix;
        $query = "SELECT {$prefix}posts.*, {$prefix}wheels_custom_data.*, {$prefix}wheels_second_opinion.star_rating, {$prefix}wheels_second_opinion.user_rating FROM " . $this->db->posts;
        $query .= " INNER JOIN {$prefix}wheels_custom_data ON {$prefix}posts.ID = {$prefix}wheels_custom_data.post_id";
        $query .= " INNER JOIN {$prefix}wheels_second_opinion ON {$prefix}posts.ID = {$prefix}wheels_second_opinion.post_id";
        $query .= " WHERE 1=1";

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $postType . "'";
        $query .= " AND {$prefix}wheels_custom_data.vehicle_id_1 = '" . $vehicleId . "'";

        $query .= " ORDER BY {$prefix}posts.post_date DESC";

        $results = $this->db->get_results($this->db->prepare($query));

        if ($results) {
            return $results;
        }
        return false;
    }

    function getAllFeatures($options = array())
    {

        return $this->getAll($options);
    }

    function getAllReviews($options = array())
    {
        return $this->getAll($options);
    }

    function getExpertsPost($postNumber)
    {

        $data = array();

        $authorsResult = $this->getExpertAuthorIds();

        foreach($authorsResult as $author) :

            $userQuery = "SELECT * FROM ".$this->prefix."posts WHERE post_author = '".$author->post_author."' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1";

            $data[] = $this->db->get_row( $userQuery );

        endforeach ;

        return $data;
    }

    public function getPostWithComment($options = array()){
        $defaults = array(
            'post_type' => '',
            'start' => 0,
            'limit' => 4,
            'post_id' => 0
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;

        $query = "SELECT DISTINCT wp_posts.*,
                wp_comments.comment_author,
                wp_comments.comment_author_email,
                wp_comments.comment_date,
                wp_comments.comment_content,
                wp_comments.comment_ID FROM " . $this->db->posts;

        $query .= " INNER JOIN wp_comments ON wp_posts.ID = wp_comments.comment_post_ID";

        $query .= " WHERE 1=1";

        if ($args['post_id'] != 0) {
            $query .= " AND {$prefix}posts.ID = '" .$args['post_id']. "'";
        }

        if (!empty($args['post_type'])) {
            $query .= " AND {$prefix}posts.post_type = '" . $args['post_type'] . "'";
        }
        $query .= " AND {$prefix}comments.comment_approved = '1'";
        $query .= " AND {$prefix}posts.post_status = 'publish'";

        if (!empty($args['except'])) {
            $query .= " AND {$prefix}posts.ID NOT IN (" . $args['except'] . ")";
        }

        if ($args['post_id'] != 0) {
            $query .= " GROUP BY wp_posts.ID";
        } else {
            $query .= " GROUP BY wp_comments.comment_ID ORDER BY wp_comments.comment_date DESC";
        }

        if ($args['count'] == false) {
            $query .= " LIMIT " . $args['start'] . ", " . 100 . "";
        }

        $temp_results = $this->db->get_results($this->db->prepare($query));

        $temp_post_id = array();
        $results =  array();

        foreach($temp_results as $row)
        {
            if(!in_array($row->ID, $temp_post_id))
            {
                $temp_post_id[] = $row->ID;
                $results[] = $row;
            }
            if(count($results) == $args['limit']) break;
        }

        return $results;
    }

    public function secondOpinionReviewData($options)
    {
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10,
            'term' => '',
            'taxonomy' => 'news-category',
            'term2' => '',
            'taxonomy2' => '',
            'popularity_field' => 'wheels_post_popularity',
            'count' => false,
            'except' => '',
            'custom_field' => false
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;
        $custom_field_table = $prefix . 'wheels_custom_data';

        $query = "SELECT
                wp_wheels_second_opinion.second_opinion_title,
                wp_posts.*
                FROM wp_wheels_second_opinion ";

        if ($args['count'] == true) $query = "SELECT COUNT(wp_wheels_second_opinion.post_id) as num_rows FROM wp_wheels_second_opinion";

        $query .= " INNER JOIN wp_posts ON wp_wheels_second_opinion.post_id = wp_posts.ID";

        if (!empty($args['term'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships ON ({$prefix}posts.ID = {$prefix}term_relationships.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy ON ({$prefix}term_relationships.term_taxonomy_id = {$prefix}term_taxonomy.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms ON ({$prefix}term_taxonomy.term_id = {$prefix}terms.term_id)";
        }

        if (!empty($args['term2'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships AS tr ON ({$prefix}posts.ID = tr.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms AS t ON (tt.term_id = t.term_id)";
        }

        $query .= " WHERE 1=1";

        if (!empty($args['term'])) {
            $query .= " AND {$prefix}terms.slug = '" . $args['term'] . "'";
            $query .= " AND {$prefix}term_taxonomy.taxonomy = '" . $args['taxonomy'] . "'";
        }

        if (!empty($args['term2'])) {
            $query .= " AND t.slug = '" . $args['term2'] . "'";
            $query .= " AND tt.taxonomy = '" . $args['taxonomy2'] . "'";
        }

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $args['post_type'] . "'";

        if (!empty($args['except'])) {
            $query .= " AND {$prefix}posts.ID NOT IN (" . $args['except'] . ")";
        }


        $query .= " ORDER BY {$prefix}posts.post_date DESC";


        if ($args['count'] == false) {
            $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";
        }
        $results = $this->db->get_results($this->db->prepare($query));

        return $results;

    }

    public function getLatestReviewVideoId()
    {
       $prefix = $this->prefix;
       $sql = $this->db->prepare("SELECT  `vehicle_review_video_id` FROM  {$prefix}wheels_vehicle_review
                                  WHERE vehicle_review_video_id !=0 ORDER BY wheels_vehicle_review_id DESC LIMIT 1");

       $vehicleId = $this->db->get_row($sql);

       return $vehicleId;
    }

    public function getExpertAuthorIds()
    {
        // Query to get all expert authors
        $sql = "SELECT DISTINCT ps.post_author
            FROM wp_posts ps
            INNER JOIN wp_cimy_uef_data cd ON ps.post_author = cd.USER_ID
            INNER JOIN wp_usermeta AS um ON ps.post_author = um.user_id
            WHERE cd.FIELD_ID =  '2'
            AND cd.VALUE =  'YES'
            AND um.meta_key = 'wp_user_level'
            AND um.meta_value = '2'";
        $results = $this->db->get_results($sql);

        $authors = array();
        foreach($results as $row)
        {
            $authors[] = $row->post_author;
        }
        $authors = array_unique($authors);
        $authors = implode(',', $authors);

        // Query to get authors who have latest posts
        $sql = "SELECT DISTINCT ps.post_author
            FROM wp_posts ps
            WHERE ps.post_author IN ($authors)
            AND ps.post_status = 'publish'
            ORDER BY ps.post_date DESC
            LIMIT 4";

        $authorsResult = $this->db->get_results( $sql );
        return $authorsResult;
    }

}