<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;

$answerCenter = new \Emicro\Model\AnswerCenter($wpdb);

$answerCenterQuestions = $answerCenter->getQuestions($category_id, 4);
$answerCenterPopularQuestions = $answerCenter->getQuestions($category_id, 4, 'popular');

?>
<!-- begin .answer-centre-->
<div class="module answer-centre clearfix">
    <div data-controller="TabsController" class="clearfix related">
        <h3>Answer Centre</h3>
        <div class="tab-nav">
            <ul class="clearfix">
                <li><a>Related</a></li>
                <li class="last"><a class="last">Trending</a></li>
            </ul>
        </div>
        <div class="tabs">
            <div data-controller="SlidesController" data-mobileonly="true" class="tab related">
                <div class="viewport">
                    <ul class="container">

                        <?php foreach($answerCenterQuestions as $row): ?>
                        <li class="article-info slide">
                            <div class="wrap">
                                <a href="<?php echo $answerCenter->link($row->category_id, $row->id) ?>" class="title"><?php echo character_limiter(stripslashes($row->question), 100,'&hellip;') ?></a>
                                <a href="<?php echo $answerCenter->link($row->category_id, $row->id) ?>" class="small"><?php echo $row->answer ?> answer<?php if($row->answer > 1) echo 's'; ?></a>
                            </div>
                        </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
            <div data-controller="SlidesController" data-mobileonly="true" class="tab trending" style="display: none;">
                <div class="viewport">
                    <ul class="container">

                        <?php foreach($answerCenterPopularQuestions as $row): ?>
                        <li class="article-info slide">
                            <div class="wrap">
                                <a href="<?php echo $answerCenter->link($row->category_id, $row->id) ?>" class="title"><?php echo character_limiter(stripslashes($row->question), 100,'&hellip;') ?></a>
                                <a href="<?php echo $answerCenter->link($row->category_id, $row->id) ?>" class="small"><?php echo $row->answer ?> answer<?php if($row->answer > 1) echo 's'; ?></a>
                            </div>
                        </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <a href="/guides/#answer-centre" class="primary">More</a>
</div>
<!-- end .answer-centre-->