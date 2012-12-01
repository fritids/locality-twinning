<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';
global $wpdb;

$isUserLoggedIn = is_user_logged_in();

$answerCenter = new \Emicro\Model\AnswerCenter($wpdb);

$answerCenterCategories = $answerCenter->getCategories();
$answerCenterQuestions = $answerCenter->getQuestions($category_id, 5);
$answerCenterPopularQuestions = $answerCenter->getQuestions($category_id, 5, 'popular');
?>

<div class="answer-centre">

    <div class="header">
        <h3>সমাধান কেন্দ্র</h3>
        <a href="/guides/#answer-centre">সকল প্রশ্ন</a>
        <a href="#" class="pull-right" data-controller="AnswerCentrePostQuestionController">
            প্রশ্ন করুন
        </a>
    </div>

    <div class="answer-nav-wrap">
        <ul id="answer-centre-pill-menu" class="pill-menu">
            <li class="on ac">
                <a href="#">সর্বশেষ</a>
            </li>
            <li class="ac">
                <a href="#">লোকপ্রিয়</a>
            </li>
        </ul>
    </div>

    <div data-controller="SlidesController" class="answer-centre-container-latest">
        <div class="nav-wrap">
            <div class="navigation">
                <a href="#" class="nav left">ডান</a>
                <a href="#" class="nav right">Right</a>
            </div>
        </div>
        <div class="viewport">
            <div class="container">
                <ul>
                    <?php
                    foreach($answerCenterQuestions as $key => $question):
                    ?>
                    <li class="slide"<?php if ($key != 0) echo ' style="display: none;"' ?>>
                        <div class="sidebar">
                            <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$question->uid.'&size=48') ?>
                        </div>
                        <div class="main">
                            <div class="quote">
                                <div class="pos">
                                    <a href="/guides/?category=<?php echo $question->category_id ?>#answer-centre/<?php echo $question->id ?>">
                                        <?php echo stripslashes($question->question) ?>
                                        </br></br>
                                    <span class="home-view-answer">উত্তর দেখুন</span>
                                    </a>
                                </div>
                                <div class="nub"></div>
                            </div>

                        </div>
                    </li>

                    <?php endforeach; ?>

                </ul>
            </div>
        </div>
    </div>
    <div data-controller="SlidesController" class="answer-centre-container-popular">
        <div class="nav-wrap">
            <div class="navigation">
                <a href="#" class="nav left">বাম</a>
                <a href="#" class="nav right">ডান</a>
            </div>
        </div>
        <div class="viewport">
            <div class="container">
                <ul>
                    <?php
                    foreach($answerCenterPopularQuestions as $key => $question):
                        ?>
                        <li class="slide"<?php if ($key != 0) echo ' style="display: none;"' ?>>
                            <div class="sidebar">
                                <?php echo wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$question->uid.'&size=48') ?>
                            </div>
                            <div class="main">
                                <div class="quote">
                                    <div class="pos">
                                        <a href="/guides/?category=<?php echo $question->category_id ?>#answer-centre/<?php echo $question->id ?>">
                                            <?php echo stripslashes($question->question) ?>
                                            </br></br>
                                        <span class="home-view-answer">উত্তর দেখুন</span>
                                        </a>
                                    </div>
                                    <div class="nub"></div>
                                </div>

                            </div>
                        </li>

                        <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- begin .ask-question-->
    <div id="ask-question-message" data-controller="AskQuestionMessageController" data-destroy="false" class="ask-question">
        <div class="pos">
            <h5>প্রশ্ন করুন:</h5>

            <p>Our editors will select the best questions to answer. You will be notified if your
                question is posted.</p>

            <div class="form-container">
                <form>
                    <fieldset>
                        <ol>
                            <li>
                                <label for="question-type">প্রশ্ন ধরন</label><!-- begin .question-type-container-->
                                <div class="question-type-container">

                                    <select data-role="none"
                                            name="category_id"
                                            data-controller="ComboboxController"
                                            data-readonly="true"
                                            class="question-type-selector ui-dark"
                                            id="question-type">
                                        <option value="">স্যানিটেশন</option>
                                        <option value="">অন্যান্য</option>
                                    </select>
                                </div>
                                <!-- end .question-type-container-->
                            </li>
                            <li>
                                <label for="question">Question</label>
                                <textarea id="question"
                                          data-role="none"
                                          name="question"
                                          rows="3"
                                          class="global-inner-shadow full"></textarea>
                                <input type="hidden" name="question_form" value="yes" />
                            </li>
                            <li>
                                <input data-role="none" type="submit" value="Submit Question" class="formbtn green"/>
                            </li>
                        </ol>
                    </fieldset>
                </form>
            </div>
        </div>
        <a href="#" class="close"></a>
    </div>
    <!-- end .ask-question-->

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

    <div style="height: 200px;"></div>

</div>