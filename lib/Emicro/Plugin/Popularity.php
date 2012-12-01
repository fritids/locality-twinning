<?php

namespace Emicro\Plugin;

class Popularity extends Base
{
    const COMMENT_WEIGHT = .9;
    const VIEW_WEIGHT = .1;

    public function update($postId)
    {
        $postModel = new \Emicro\Model\Post($this->db);
        $post = $postModel->getById($postId);

        $commentCount = (int) $post->comment_count;
        $userViewCount = (int) $postModel->getMeta($post->ID, 'wheels_post_view');

        if(empty($userViewCount)) {
            $userViewCount = 0;
            $this->db->insert('wp_postmeta', array('post_id' => $postId, 'meta_key' => 'wheels_post_view', 'meta_value' => 0));
        }

        $userViewCount++;
        $popularity = ($commentCount * self::COMMENT_WEIGHT) + ($userViewCount * self::VIEW_WEIGHT);

        $postModel->setMeta($post->ID, 'wheels_post_view', $userViewCount);
        $postModel->setMeta($post->ID, 'wheels_post_popularity', $popularity);

        return $popularity;
    }

    public function get($postId)
    {
        $postModel = new \Emicro\Model\Post($this->db);
        return $postModel->getMeta($postId, 'wheels_post_popularity');
    }

    public function updateUserRating($postId)
    {
        $prefix = $this->prefix;

        $rating = $this->getUserRating($postId);

        $numberOfComment = (empty($rating->numberOfComment)) ? 0 : $rating->numberOfComment;
        $sumCommentRating = (empty($rating->sumCommentRating)) ? 0 : $rating->sumCommentRating;

        if($numberOfComment == 0) {
            $average = 0;
        }else{
            $average = $this->getRatingFloorValue($sumCommentRating/$numberOfComment);
        }


        $updateQuery = "UPDATE {$prefix}wheels_vehicle_review SET user_rating = '$average' WHERE post_id = '$postId'";
        $this->db->query($updateQuery);

    }

    public function getUserRating($postId)
    {
        $prefix = $this->prefix;

        $query = "
            SELECT
            COUNT({$prefix}comments.comment_ID) as numberOfComment,
            SUM({$prefix}commentmeta.meta_value) as sumCommentRating
            FROM {$prefix}comments
            INNER JOIN {$prefix}commentmeta ON {$prefix}comments.comment_ID = {$prefix}commentmeta.comment_id
            WHERE {$prefix}comments.comment_post_ID = $postId AND {$prefix}commentmeta.meta_key = 'comment_rating'
            AND {$prefix}comments.comment_approved = '1'
            AND {$prefix}commentmeta.meta_value != '0.0'
            GROUP BY {$prefix}comments.comment_post_ID";
        $rating = $this->db->get_row($query);
        return $rating;
    }

    public function getRatingFloorValue($value)
    {
        $floor = floor($value);
        $fraction = $value - $floor;
        $val = ($fraction >= .5) ? .5 : .0;
        return $floor + $val;
    }

    public function comment($commentID)
    {
        $commentPopularityKey = 'comment_popularity';
        $sql = $this->db->prepare("SELECT meta_id, post_id, meta_key, meta_value
                                    FROM wp_commentmeta
                                    WHERE comment_id = %d
                                    AND meta_key = %s", $commentID, $commentPopularityKey);

        $commentPopularity = $this->db->get_row($sql);

        if(empty($commentPopularity)) {
            $commentPopularity = 0;
            $this->db->insert('wp_commentmeta', array('comment_id' => $commentID, 'meta_key' => $commentPopularityKey, 'meta_value' => 0));
        }else{
            $commentPopularity = (int)$commentPopularity->meta_value;
        }

        $commentPopularity++;
        $this->db->update('wp_commentmeta', array('comment_id' => $commentID, 'meta_key' => $commentPopularityKey, 'meta_value' => $commentPopularity), array('%d', '%s', '%s'));

        $this->wheels_vehicle_popularity_varnish_invalidate();

        return $commentPopularity;
    }

    public function wheels_vehicle_popularity_varnish_invalidate()
    {
        $urls = array(
            'vehicles_and_reviews_popular_vehicles' => get_template_directory_uri() . '/esi/header/vehicles_and_reviews_popular_vehicles.php',
        );

        Varnish::purgeAll($urls);
    }

}