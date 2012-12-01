<?php
include_once 'wheels-ac-category-class.php';

//Create an instance of our package class...
$acTable = new AC_Question_List();
//Fetch, prepare, sort, and filter our data...
$acTable->prepare_items();

?>
<style type="text/css">
    #category-list #name { width: 400px; }
    #category-list #weight { width: 30px; text-align: center; }
    #category-list #action { width: 50px; text-align: center;  }

    .expert_row, .expert_row p {
        background-color: #333333 !important;
        color: #fff !important;
    }
</style>
<div class="wrap">

    <h2>Answer Centre - Category</h2>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="<?php echo $acTable->table_id ?>" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $acTable->display() ?>
    </form>

</div>