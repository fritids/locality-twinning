<?php

namespace Emicro\Model;

abstract class Base
{
    /**
     * @var \WPDB
     */
    protected $db;

    /**
     * @var string DB prefix
     */
    protected $prefix = 'wp_';

    public function __construct($db)
    {
        $this->db = $db;

        if (isset($db->prefix)) {
            $this->prefix = $db->prefix;
        }
    }
}