<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;

$isUserLoggedIn = is_user_logged_in();

$answerCenter = new \Emicro\Model\AnswerCenter($wpdb);
$adModel = new \Emicro\Model\Ad($wpdb);

$answerCenterCategories = $answerCenter->getCategories();

$category_id = $_GET['category'];
if (empty($category_id) || $category_id == '0'){
    $category_id = $answerCenter->getDefaultCategoryId();
}
$answerCenterQuestions = $answerCenter->getQuestions($category_id);
?>

<div class="row">
    <a name="answer-centre"></a>
    <div class="answer-centre">
        <div class="header">
            <h3>Answer Centre</h3>
            <ul class="pill-menu">
                <?php foreach($answerCenterCategories as $key => $category): ?>
                <li class="ac<?php if($category->id == $category_id) echo ' on"' ?>"><a href="/guides/?category=<?php echo $category->id ?>"><?php echo $category->name ?></a></li>
                <?php endforeach; ?>
            </ul>

            <?php if($isUserLoggedIn): ?>
            <a class="primary" data-controller="AnswerCentrePostQuestionController" href="#" id="post-comment-link">
                Post a Question
            </a>
            <?php else: ?>
            <a class="primary" data-controller="ModalTriggerController" data-modal="#login-signup" href="#" id="post-comment-link">
                Sign-in to post a question
            </a>
            <?php endif ?>



            <?php if($isUserLoggedIn): ?>
            <div class="ask-question" data-destroy="false" data-controller="AskQuestionMessageController"
                 id="ask-question-message" style="display: none;">
                <div class="pos">
                    <h5>Ask a question:</h5>

                    <p>Our editors will select the best questions to answer. You will be notified if your question is
                        posted.</p>

                    <div class="form-container">
                        <form>
                            <fieldset>
                                <ol>
                                    <li>
                                        <label for="category_id">Question type</label>

                                        <div class="question-type-container">
                                            <select data-role="none"
                                                    id="category_id"
                                                    name="category_id"
                                                    data-controller="ComboboxController"
                                                    data-readonly="true"
                                                    class="question-type-selector ui-dark">
                                                <?php foreach($answerCenterCategories as $category) :?>
                                                <option value="<?php echo $category->id ?>"><?php echo $category->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <label for="question">Question</label>
                                        <textarea class="global-inner-shadow full" rows="3" name="question" data-role="none"
                                                  id="question"></textarea>
                                        <input type="hidden" name="question_form" value="yes" />
                                    </li>
                                    <li>
                                        <input type="submit" class="formbtn green" value="Submit Question" data-role="none">
                                    </li>
                                </ol>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="close" href="#"></a>
            </div>
            <?php endif; ?>
        </div>

        <ul class="acc-menu" data-controller="AccordionController2">

            <?php if( count($answerCenterQuestions) == 0) {?>
            <li>
                <p>No question posted in this category.</p>
            </li>
            <?php } ?>

            <?php foreach($answerCenterQuestions as $key => $question): $user = get_userdata($question->uid);?>

            <li<?php if($key == 0) echo ' class="on"' ?> id="question-id-<?php echo $question->id ?>">

                <div class="section-head">
                    <a class="heading" href="#">
                        <div class="wrap">
                            <?php echo get_avatar($user->ID, 48) ?>
                            <div class="question">
                                <p class="username"><?php echo $user->display_name ?></p>
                                <h5><?php echo stripcslashes($question->question) ?></h5>
                            </div>
                            <div class="comments">
                                <p>
                                    <?php
                                    if($question->answer == 1)
                                    {
                                        echo 'View Answer';
                                    }elseif($question->answer > 1){
                                        echo 'View Answers';
                                    }
                                    /*
                                    if($question->expert_uid != 0){
                                        $expert = get_userdata($question->expert_uid);
                                        echo $expert->display_name .' + ';
                                    }
                                    */
                                    ?>
                                </p>
                                <span class="comment-count"><?php echo $question->answer ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <?php $answers = $answerCenter->getAnswers($question->id);?>

                <div class="collapsible clearfix" style="display: <?php echo ($key == 0) ? 'block' : 'none'?>;">
                    <div class="main">
                        <div class="comments">
                            <ol class="commentlist">

                                <?php foreach($answers as $answer):
                                    $user = get_userdata($answer->uid);
                                    $isExpert = $wpdb->get_var("SELECT USER_ID FROM wp_cimy_uef_data WHERE USER_ID = '{$answer->uid}' AND FIELD_ID = '2' AND VALUE = 'YES'");
                                ?>

                                <li<?php if(!empty($isExpert)) echo ' class="answer"' ?> id="li-comment-<?php echo $answer->id ?>">
                                    <article class="comment" id="comment-<?php echo $answer->id ?>">
                                        <div class="avatar-wrap">
                                            <?php echo get_avatar($user->ID, 48) ?>
                                        </div>
                                        <div class="comment-wrap">
                                            <footer class="comment-meta">

                                                <?php if(!empty($isExpert)){ ?>

                                                <div class="comment-author vcard">
                                                    <span class="fn"><?php echo $user->display_name ?>'s Answer</span>
                                                </div>

                                                <?php }else{ ?>

                                                <div class="comment-author vcard">
                                                    <span class="fn"><?php echo $user->display_name ?></span>
                                                    <time datetime="<?php echo $answer->submitted ?>" pubdate="pubdate"><?php echo human_time_diff($answer->submitted, time())?> ago</time>
                                                </div>

                                                <?php } ?>

                                            </footer>

                                            <div class="comment-content"><?php echo apply_filters('the_content', stripslashes($answer->answer) ) ?></div>

                                        </div>
                                    </article>
                                </li>

                                <?php endforeach; ?>

                            </ol>

                            <div class="commentform">
                                <form method="post" class="answerform">
                                    <fieldset>
                                        <ol>
                                            <?php if ($isUserLoggedIn){ ?>
                                            <li>
                                                <label for="comment-<?php echo $question->id ?>">Have your say</label>
                                                <textarea class="global-inner-shadow full" rows="4" name="answer" data-role="none"
                                                          id="comment-<?php echo $question->id ?>"></textarea>
                                                <input type="hidden" name="question_id" value="<?php echo $question->id ?>" />
                                            </li>
                                            <li>
                                                <input type="submit" class="formbtn green" value="Submit Answer" data-role="none">

                                                <p class="tip">
                                                    <strong>Tip:</strong>&nbsp;Be positive and follow our
                                                    <a href="/commenting-guidelines/">commenting guidelines</a>
                                                </p>
                                            </li>
                                            <?php }else{ ?>
                                            <li>
                                                <p class="tip">
                                                    Please sign-in to post answer
                                                    <a href="#" data-controller="ModalTriggerController" data-modal="#login-signup">Sign-in</a>
                                                </p>
                                            </li>
                                            <?php } ?>
                                        </ol>
                                    </fieldset>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="sidebar">
                        <div class="mrec-ad">
                            <?php echo $adModel->getAd('300x250'); ?>
                        </div>
                    </div>

                </div>
            </li>

            <?php endforeach; ?>

        </ul>
    </div>
</div>

<!-- begin #alert.modal-->
<div id="modal-screens2" data-controller="ModalController">
    <div id="ac-message" style="display: none;" class="modal" data-controller="ModalController">
        <div class="content">
            <h3 id="ac-message-title"></h3>
            <p id="ac-message-body"></p>
            <a href="#" class="close">X</a>
        </div>
        <div class="mask"></div>
    </div>
</div>
<!-- end #alert.modal-->