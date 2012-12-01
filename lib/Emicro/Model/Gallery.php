<?php

namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';
class Gallery extends Base
{
    const TABLE_NAME = 'wheels_gallery';

    /**
     * Get gallery assets for a given post
     *
     * @param $postId
     * @return bool | array
     */
    public function getAssets($postId)
    {
        if (empty($postId)) {
            return false;
        }

        $sql = $this->db->prepare("SELECT * FROM " . $this->db->prefix . self::TABLE_NAME . " WHERE post_id = %d", $postId);
        $results = $this->db->get_results($sql);

        return $results;
    }
}