<?php

function wheels_ac_create_table()
{

    $sql = "
        CREATE TABLE IF NOT EXISTS `wp_wheels_ac_answers` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `uid` int(10) unsigned NOT NULL,
          `qid` int(10) unsigned NOT NULL,
          `answer` text CHARACTER SET latin1 NOT NULL,
          `submitted` int(10) unsigned NOT NULL,
          `is_expert` tinyint(1) NOT NULL,
          `status` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


        CREATE TABLE IF NOT EXISTS `wp_wheels_ac_categories` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(50) NOT NULL,
          `weight` int(5) unsigned NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE IF NOT EXISTS `wp_wheels_ac_questions` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `uid` int(10) NOT NULL,
          `category_id` int(5) NOT NULL,
          `question` text NOT NULL,
          `submitted` int(10) NOT NULL,
          `expert_uid` int(10) NOT NULL,
          `answer` int(10) unsigned NOT NULL,
          `status` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function wheels_ac_drop_table()
{

}