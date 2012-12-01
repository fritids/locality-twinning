<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class AC_Answer_List extends WP_List_Table {

    var $table_id = 'answer-list';
    var $ac_category_table;
    var $ac_question_table;
    var $ac_answer_table;
    var $perpage;
    var $question;
    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        global $wpdb;

        parent::__construct( array(
            'singular'=> 'wp_list_text_link', //Singular label
            'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
            'ajax'	=> false //We won't support Ajax for this table
        ) );

        $this->ac_category_table = $wpdb->prefix . WHEELS_AC_CAT_TABLE;
        $this->ac_question_table = $wpdb->prefix . WHEELS_AC_QUESTION_TABLE;
        $this->ac_answer_table = $wpdb->prefix . WHEELS_AC_ANSWER_TABLE;
        $this->perpage = 10;

        $this->question = $wpdb->get_row("SELECT * FROM ". $this->ac_question_table . " WHERE id = '". $_GET['question_id'] ."'");
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav( $which ) {
        if ( $which == "top" ){

        }
        if ( $which == "bottom" ){

        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns= array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'answer' => __('Answer'),
            'user' => __('User'),
            'submitted' => __('Submitted'),
            'status' => __('Status')
        );
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'category' => 'category',
            'submitted' => 'submitted',
            'reply' => 'reply'
        );
    }

    function get_bulk_actions()
    {
        $actions = array(
            'approve' => 'Approve',
            'pending' => 'Pending',
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
        if (is_array($ids)) $ids = implode(',', $ids);

        if ('approve' === $this->current_action()) {
            if (!empty($ids)) {
                $wpdb->query("UPDATE ". $this->ac_answer_table ." SET status = '1' WHERE id IN($ids)");

                // getting record for sending mail
                $questions = $wpdb->get_results("SELECT
                    wp_wheels_ac_questions.*,
                    wp_wheels_ac_answers.uid as answerUid
                    FROM
                    wp_wheels_ac_questions
                    INNER JOIN wp_wheels_ac_answers ON wp_wheels_ac_questions.id = wp_wheels_ac_answers.qid
                    WHERE
                    wp_wheels_ac_answers.id IN ($ids)");

                // Sending mail to
                $this->send_approval_mail($questions);

                // Send purge request with Answer id
                $this->invalidateAction($ids);
            }
        }

        if ('pending' === $this->current_action()) {
            // Pending query
            if (!empty($ids)) {
                $wpdb->query("UPDATE ". $this->ac_answer_table ." SET status = '0' WHERE id IN($ids)");

                // Send purge request with Answer id
                $this->invalidateAction($ids);

            }
        }

        if ('delete' === $this->current_action()) {
            // Delete query
            if (!empty($ids)) {
                // Send purge request with Answer id
                $this->invalidateAction($ids, true);

            }
        }
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        /* -- Preparing your query -- */
        $query = "
            SELECT * FROM " . $this->ac_answer_table ." WHERE qid = '". $wpdb->escape($_GET['question_id']) ."'";

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';

        $order = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'submitted';

        switch($_GET["orderby"])
        {
            case 'c':
                $order = 'category';
                break;
            case 'r':
                $order = 'reply';
                break;
            default:
                $order = 'submitted';
        }

        if(!empty($orderby) && !empty($order)){ $query.=' ORDER BY '.$order.' '.$orderby; }

        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = $this->perpage;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged <= 0 ){ $paged = 1; }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
        if(!empty($paged) && !empty($perpage)){
            $offset = ($paged-1) * $perpage;
            $query .= ' LIMIT ' . (int)$offset . ',' . (int)$perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);

    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        $date_format = get_option('date_format');

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if(!empty($records)){
            foreach($records as $row){

                $user = get_userdata($row->uid);

                //Open the line
                echo '<tr id="record_'.$row->id.'">';

                foreach ( $columns as $column_name => $column_display_name ) {

                    //Style attributes for each col
                    $class = "class=\"$column_name column-$column_name";
                    if($row->is_expert == '1') $class .= ' expert_row';
                    $class .= '"';
                    $style = "";
                    if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //edit link
                    $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$row->id;

                    //Display the cell

                    switch ( $column_name ) {
                        case "cb":
                            echo '<td '.$attributes.'><input type="checkbox" name="id[]" value="'. $row->id .'" /></td>';
                            break;
                        case "answer":
                            echo '<td '.$attributes.'>'. apply_filters('the_content', stripslashes($row->answer)) .'</td>';
                            break;
                        case "user":
                            echo '<td '.$attributes.'>'.$user->display_name.'</td>';
                            break;
                        case "submitted":
                            echo '<td '.$attributes.'>'. date($date_format, $row->submitted).'</td>';
                            break;
                        case "status":
                            echo '<td '.$attributes.'>'. $this->get_status($row->status).'</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }

        echo '<input type="hidden" name="question_id" value="'. $_REQUEST['question_id'] .'">';

    }

    function get_status($status)
    {
        $str = '';
        switch($status)
        {
            case 1:
                $str = 'Approve';
                break;
            case 0:
                $str = 'Pending';
                break;
        }
        return $str;
    }

    private function send_approval_mail($questions)
    {

        $option = get_option('ac_option');
        $from_email = $option['question_approval_email_from_email'];
        $from_name = $option['question_approval_email_from_name'];

        $subject = $option['answer_approval_email_from_subject'];
        $body = $option['answer_approval_email'];

        $headers = "From: $from_name <$from_email>" . "\r\n";
        foreach($questions as $question)
        {
            $user = get_userdata($question->answerUid);
            $first_name = get_user_meta($user->ID, 'first_name', true);
            $last_name = get_user_meta($user->ID, 'last_name', true);

            // Prepare email text
            $text = str_replace(
                array(
                    '%user_name%',
                    '%first_name%',
                    '%last_name%',
                    '%question%'
                ),
                array(
                    $user->user_login,
                    $first_name,
                    $last_name,
                    $question->question
                ),
                $body
            );
            wp_mail($user->user_email, $subject, $text);
        }
    }

    private function invalidateAction($answerIds, $deleteAnswer = false)
    {

        global $wpdb;
        $answerCenter = new \Emicro\Model\AnswerCenter($wpdb);

        $category_ids = $wpdb->get_results("
                                    SELECT DISTINCT
                                    wp_wheels_ac_questions.category_id
                                    FROM
                                    wp_wheels_ac_questions
                                    INNER JOIN wp_wheels_ac_answers ON wp_wheels_ac_questions.id = wp_wheels_ac_answers.qid
                                    WHERE ". $this->ac_answer_table .".id IN ($answerIds)");

        $this->updateAnswerCount($answerIds, $deleteAnswer);

        foreach($category_ids as $row)
        {
            $answerCenter->invalidateUrls($row->category_id);
        }
    }

    public function updateAnswerCount($answerIds, $deleteAnswer)
    {
        global $wpdb;
        $results = $wpdb->get_results("
                                    SELECT DISTINCT
                                    qid
                                    FROM
                                    ". $this->ac_answer_table ."
                                    WHERE ". $this->ac_answer_table .".id IN ($answerIds)");


        if($deleteAnswer)
        {
            $wpdb->query("DELETE FROM ". $this->ac_answer_table ." WHERE id IN($answerIds)");
        }

        foreach($results as $row)
        {

            $answerCount = $wpdb->get_var("SELECT COUNT(id) FROM ". $this->ac_answer_table ." WHERE status = '1' AND qid =  '$row->qid'");

            // Get latest answer row of expert
            $answerData = $wpdb->get_row("
                            SELECT uid
                            FROM ". $this->ac_answer_table ."
                            WHERE status = '1' AND is_expert = '1' AND qid =  '$row->qid'
                            ORDER BY is_expert DESC, submitted DESC");

            // Prepare update date
            $data = array(
                'answer' => $answerCount
            );

            // If no expert answer found, set expert uid 0
            if(count($answerData) == 0)
            {
                $data['expert_uid'] = 0;
            }else{
                $data['expert_uid'] = $answerData->uid;
            }

            $wpdb->update(
                $this->ac_question_table,
                $data,
                array(
                    'id' => $row->qid
                )
            );

        }

    }

}


?>