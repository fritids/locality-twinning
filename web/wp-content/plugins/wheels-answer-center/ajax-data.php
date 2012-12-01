<?php

if (empty($_COOKIE)) {
    $isUserLoggedIn = false;
} else {
    include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
    $isUserLoggedIn = is_user_logged_in();
}

//if ( !$isUserLoggedIn ) exit(json_encode(array('status'=> 'fail', 'message' => 'Access denied')));

global $wpdb;

list($null, $page) = explode('/', $_SERVER['REQUEST_URI']);

$current_user = wp_get_current_user();

$answerCenter = new \Emicro\Model\AnswerCenter($wpdb);

if(!empty($_POST['answer']) && !empty($_POST['question_id'])){
    $status = $answerCenter->saveAnswer($current_user, (OBJECT)$_POST, 0);
    echo $status;
}

if(!empty($_POST['question_form']) && !empty($_POST['category_id']) && !empty($_POST['question'])){
    $status = $answerCenter->saveQuestion($current_user, (OBJECT)$_POST, 0);
    echo $status;
}