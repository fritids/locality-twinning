<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class AC_Question_List extends WP_List_Table {

    var $table_id = 'category-list';
    var $category_table;
    var $question_table;
    var $answer_table;
    var $perpage;
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

        $this->category_table = $wpdb->prefix . WHEELS_AC_CAT_TABLE;
        $this->question_table = $wpdb->prefix . WHEELS_AC_QUESTION_TABLE;
        $this->answer_table = $wpdb->prefix . WHEELS_AC_ANSWER_TABLE;
        $this->perpage = 10;
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
            //The code that goes before the table is here
            //echo"Hello, I'm before the table";
        }
        if ( $which == "bottom" ){
            //The code that goes after the table is there
            //echo"Hi, I'm after the table";
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns= array(
            'name' => __('Category Name'),
            'weight' => __('Weight'),
            'action' => __('Action')
        );
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'name' => 'name',
            'weight' => 'weight'
        );
    }


    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        /* -- Preparing your query -- */
        $query = "
            SELECT
                *
            FROM
                ". $this->category_table ."
            ";

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';

        $order = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'weight';

        switch($_GET["orderby"])
        {
            case 'n':
                $order = 'name';
                break;
            case 'w':
                $order = 'weight';
                break;
            default:
                $order = 'weight';
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
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //edit link
                    $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$row->id;

                    //Display the cell
                    switch ( $column_name ) {
                        case "name":
                            echo '<td '.$attributes.'>'.stripslashes($row->name).'</td>';
                            break;
                        case "weight":
                            echo '<td '.$attributes.' align="center">'.stripslashes($row->weight).'</td>';
                            break;
                        case "action":
                            echo '<td '.$attributes.' align="center">
                                    <a href="/wp-admin/?id='.$user->id.'">Edit</a> |
                                    <a href="/wp-admin/?id='.$user->id.'">Delete</a>
                                  </td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }
}


?>