<?php
include_once 'wheels-ac-list-class.php';
include_once 'wheels-ac-reply-list-class.php';

if( !empty($_GET['question_id']) && ac_is_question_exists($_GET['question_id']))
{
    //Create an instance of our package class...
    $acTable = new AC_Answer_List();
    //Fetch, prepare, sort, and filter our data...
    $acTable->prepare_items();
}else{
    //Create an instance of our package class...
    $acTable = new AC_Question_List();
    //Fetch, prepare, sort, and filter our data...
    $acTable->prepare_items();
}

?>
<style type="text/css" xmlns="http://www.w3.org/1999/html">
    #answer-list #cb { width: 25px; }
    #answer-list #answer { width: 400px; }
    #answer-list #user { width: 100px; }
    #answer-list #submitted { width: 70px; }
    #answer-list #status { width: 30px; }

    #question-list #question { width: 375px; }
    #question-list #category { width: 100px; }
    #question-list #user { width: 100px; }
    #question-list #answer { width: 56px; }
    #question-list #submitted { width: 70px; }
    #question-list #view { width: 30px; }
    #question-list #status { width: 40px; }

    .expert_row, .expert_row p {
        background-color: #333333 !important;
        color: #fff !important;
    }
</style>
<div class="wrap">

    <h2>Answer Centre <?php echo isset($acTable->question->question) ? '- ' . $acTable->question->question : ''?></h2>

    <?php if(get_class($acTable) == 'AC_Answer_List') {?>
    <a href="/wp-admin/admin.php?page=answer-centre">Back to main list page</a>
    <?php } ?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="<?php echo $acTable->table_id ?>" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $acTable->display() ?>
    </form>

</div>