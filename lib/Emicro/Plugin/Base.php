<?php

namespace Emicro\Plugin;

abstract class Base
{
    protected $db;
    protected $prefix = 'wp_';

    public function __construct($db)
    {
        $this->db = $db;

        if (isset($db->prefix)) {
            $this->prefix = $db->prefix;
        }
    }
}