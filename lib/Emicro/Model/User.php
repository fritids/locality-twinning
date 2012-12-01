<?php

namespace Emicro\Model;

include_once WP_CONTENT_DIR . '/bootstrap.php';

class User extends Base
{
    protected $facebook;
    protected $twitter;
    protected $userId = 0;

    public function __construct($wpdb)
    {
        parent::__construct($wpdb);
    }

    public function loadFacebookUser($facebookUserId = 0)
    {
        $prefix    = !empty($this->prefix) ? $this->prefix : 'wp_';
        $sql       = $this->db->prepare("SELECT * FROM {$prefix}usermeta WHERE meta_key = 'facebook_user_id' AND meta_value = '" . $facebookUserId . "'");
        $user_meta = $this->db->get_row($sql);

        if (!empty($user_meta)) {
            $this->userId = $user_meta->user_id;
            return true;
        }

        return false;
    }

    public function loadTwitterUser($twitterUserId = 0)
    {
        $prefix    = !empty($this->prefix) ? $this->prefix : 'wp_';
        $sql       = $this->db->prepare("SELECT * FROM {$prefix}usermeta WHERE meta_key = 'twitter_user_id' AND meta_value = '" . $twitterUserId . "'");
        $user_meta = $this->db->get_row($sql);

        if (!empty($user_meta)) {
            $this->userId = $user_meta->user_id;
            return true;
        }

        return false;
    }

    public function getWpUserId()
    {
        return $this->userId;
    }
}