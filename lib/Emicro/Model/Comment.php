<?php
namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';

class Comment extends Base
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
            'start' => 0,
            'limit' => 10,
            'minPopularity' => 5,
            'post_id' => 0,
            'post_type' => ''
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;

        $query = "SELECT DISTINCT wp_comments.*, wp_posts.*
                FROM wp_comments
                INNER JOIN wp_posts ON wp_comments.comment_post_ID = wp_posts.ID ";

        if( in_array($args['type'], array('popular', 'rating', 'recentPostPopularComment')) )
        {
            $query .= " LEFT JOIN wp_commentmeta ON wp_comments.comment_ID = wp_commentmeta.comment_id";
        }

        $query .= " WHERE 1=1";

        $query .= " AND {$prefix}comments.comment_approved = '1'";
        $query .= " AND {$prefix}posts.comment_count > 0";

        if($args['post_id'] != 0)
        {
            $query .= " AND {$prefix}commentmeta.meta_key = 'comment_popularity'";
        }

        if( !empty($args['post_type']) )
        {
            $query .= " AND {$prefix}posts.post_type = '" .$args['post_type']. "'";
        }

        if($args['type'] == 'popular')
        {
            $query .= " AND {$prefix}commentmeta.meta_key = 'comment_popularity'";
            $query .= " ORDER BY {$prefix}comments.comment_date DESC, CAST({$prefix}commentmeta.meta_value AS UNSIGNED) DESC";
        }else if($args['type'] == 'rating')
        {
            $query .= " AND {$prefix}commentmeta.meta_key = 'comment_rating'";
            $query .= " ORDER BY {$prefix}comments.comment_date DESC, CAST({$prefix}commentmeta.meta_value AS UNSIGNED) DESC";
        }else if($args['type'] == 'recentPostPopularComment')
        {
            $query .= " AND {$prefix}commentmeta.meta_key = 'comment_popularity'";
            $query .= " ORDER BY {$prefix}comments.comment_date DESC, {$prefix}posts.post_date DESC, CAST({$prefix}commentmeta.meta_value AS UNSIGNED) DESC ";
        }else{

            $query .= " GROUP BY {$prefix}comments.comment_ID ";
            $query .= " ORDER BY {$prefix}comments.comment_date DESC";
        }

        $query .= " LIMIT " . $args['start'] . ", " . 100 . "";

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

    public function getPosts($options = array())
    {
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;

        $query = "SELECT * FROM " . $this->db->posts;

        $query .= " WHERE 1=1";

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $args['post_type'] . "'";

        $query .= " ORDER BY {$prefix}posts.post_date DESC, {$prefix}posts.post_date DESC";

        $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";

        $results = $this->db->get_results($this->db->prepare($query));
        return $results;
    }

    function getCommentCount($postId, $postType){
        $prefix = $this->prefix;
        $sql = $this->db->prepare("SELECT comment_count FROM wp_posts WHERE ID = %d AND post_type = '%s'", $postId, $postType);
        $count = $this->db->get_var($sql);

        if(empty($count)) $count = 0;
        return $count;
    }

}