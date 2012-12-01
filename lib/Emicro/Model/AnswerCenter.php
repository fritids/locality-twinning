<?php
namespace Emicro\Model;
include_once WP_CONTENT_DIR . '/bootstrap.php';

class AnswerCenter extends Base
{
    var $question_table;
    var $answer_table;
    var $category_table;

    public function __construct($db)
    {
        $this->db = $db;

        if (isset($db->prefix)) {
            $this->prefix = $db->prefix;
        }

        if (!defined('WHEELS_AC_CAT_TABLE'))
        {
            define('WHEELS_AC_CAT_TABLE', 'wheels_ac_categories');
            define('WHEELS_AC_QUESTION_TABLE', 'wheels_ac_questions');
            define('WHEELS_AC_ANSWER_TABLE', 'wheels_ac_answers');
        }

        $this->question_table = $this->db->prefix . WHEELS_AC_QUESTION_TABLE;
        $this->answer_table = $this->db->prefix . WHEELS_AC_ANSWER_TABLE;
        $this->category_table = $this->db->prefix . WHEELS_AC_CAT_TABLE;
    }

    public function getById($postId)
    {
        $prefix = $this->prefix;
        $sql = $this->db->prepare("SELECT * FROM {$prefix}posts WHERE ID = %d LIMIT 1", $postId);
        $post = $this->db->get_row($sql);

        return $post;
    }

    public function getAll($options = array())
    {
        $defaults = array(
            'type' => 'latest',
            'post_type' => 'news',
            'start' => 0,
            'limit' => 10,
            'term' => '',
            'taxonomy' => 'news-category',
            'term2' => '',
            'taxonomy2' => '',
            'popularity_field' => 'wheels_post_popularity',
            'count' => false,
            'except' => '',
            'custom_field' => false
        );

        $args = array_merge($defaults, $options);
        $prefix = $this->prefix;
        $custom_field_table = $prefix . 'wheels_custom_data';

        $query = "SELECT * FROM " . $this->db->posts;
        if ($args['count'] == true) $query = "SELECT COUNT(ID) as num_rows FROM " . $this->db->posts;
        if ($args['type'] == 'popular') {
            $query .= " LEFT JOIN {$prefix}postmeta ON({$prefix}posts.ID = {$prefix}postmeta.post_id)";
        }

        if ($args['custom_field']) {
            $query .= " LEFT JOIN $custom_field_table ON({$prefix}posts.ID = $custom_field_table.post_id)";
        }

        if (!empty($args['term'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships ON ({$prefix}posts.ID = {$prefix}term_relationships.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy ON ({$prefix}term_relationships.term_taxonomy_id = {$prefix}term_taxonomy.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms ON ({$prefix}term_taxonomy.term_id = {$prefix}terms.term_id)";
        }

        if (!empty($args['term2'])) {
            $query .= " LEFT JOIN {$prefix}term_relationships AS tr ON ({$prefix}posts.ID = tr.object_id)";
            $query .= " LEFT JOIN {$prefix}term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)";
            $query .= " LEFT JOIN {$prefix}terms AS t ON (tt.term_id = t.term_id)";
        }

        $query .= " WHERE 1=1";

        if (!empty($args['term'])) {
            $query .= " AND {$prefix}terms.slug = '" . $args['term'] . "'";
            $query .= " AND {$prefix}term_taxonomy.taxonomy = '" . $args['taxonomy'] . "'";
        }

        if (!empty($args['term2'])) {
            $query .= " AND t.slug = '" . $args['term2'] . "'";
            $query .= " AND tt.taxonomy = '" . $args['taxonomy2'] . "'";
        }

        $query .= " AND {$prefix}posts.post_status = 'publish'";
        $query .= " AND {$prefix}posts.post_type = '" . $args['post_type'] . "'";

        if (!empty($args['except'])) {
            $query .= " AND {$prefix}posts.ID NOT IN (" . $args['except'] . ")";
        }

        if ($args['type'] == 'popular') {
            $query .= " AND {$prefix}postmeta.meta_key = '" . $args['popularity_field'] . "'";
            $query .= " ORDER BY CAST({$prefix}postmeta.meta_value AS UNSIGNED) DESC, {$prefix}posts.post_date DESC";
        } else {
            $query .= " ORDER BY {$prefix}posts.post_date DESC";
        }

        if ($args['count'] == false) {
            $query .= " LIMIT " . $args['start'] . ", " . $args['limit'] . "";
        }
        $results = $this->db->get_results($this->db->prepare($query));
        return $results;
    }

    function saveQuestion($user, $postdata, $status = 0)
    {
        $insert = $this->db->insert(
            $this->question_table,
            array(
                'uid' => $user->ID,
                'category_id' => $postdata->category_id,
                'question' => $postdata->question,
                'submitted' => time(),
                'expert_uid' => 0,
                'status' => $status
            ),
            array(
                '%d',
                '%d',
                '%s',
                '%d',
                '%d',
                '%d'
            )
        );

        $questionData = array(
            'status'    => 'fail'
        );
        if ($insert == !false) $questionData['status'] = 'success';

        //$questionData['query'] = $this->db->last_query;
        //$questionData['error'] = $this->db->last_error;

        return json_encode($questionData);
    }

    function saveAnswer($user, $postdata, $status = 1)
    {
        $uid = $user->ID;
        // check if user is mark as expert
        $expert_uid = $this->db->get_var("SELECT USER_ID FROM wp_cimy_uef_data WHERE FIELD_ID =  '2' AND VALUE = 'YES' AND USER_ID = '$uid'");

        // Prepare insert data
        $answerData = array(
            'uid'       => $uid,
            'qid'       => $postdata->question_id,
            'answer'    => $postdata->answer,
            'submitted' => time(),
            'is_expert' => ($expert_uid) ? 1 : 0,
            'status'    => ($expert_uid) ? 1 : 0
        );
        // Insert answer
        $insert = $this->db->insert(
            $this->answer_table,
            $answerData,
            array(
                '%d',
                '%d',
                '%s',
                '%d',
                '%d',
                '%d'
            )
        );
        $answerData['id'] = $this->db->insert_id;

        // Get total answer
        $answerCount = $this->db->get_var("SELECT COUNT(id) FROM ". $this->answer_table ." WHERE status = '1' AND qid =  '$postdata->question_id'");

        // Prepare update field/data
        $data = array('answer' => $answerCount);
        if($expert_uid)
        {
            $data['expert_uid'] = $expert_uid;
        }
        // Update total count value and expert uid
        $this->db->update(
            $this->question_table,
            $data,
            array(
            'id' => $postdata->question_id
            )
        );

        $categoryId = $this->db->get_var("SELECT category_id FROM ". $this->question_table ." WHERE id = '".$postdata->question."'");
        $this->invalidateUrls($categoryId);

        $expertName = '';
        if(($answerData['is_expert'] != 0))
        {
            $user = get_userdata($expert_uid);
            $expertName = $user->display_name;
            //$answerCount--;
        }

        $viewAnswer = '';
        if($answerCount == 1)
        {
            $viewAnswer = 'View Answer';
        }elseif($answerCount > 1){
            $viewAnswer = 'View Answers';
        }

        return json_encode(
            array(
                'type' => $answerData['is_expert'],
                'text' => $this->genereateAnswerHTML($user, (OBJECT)$answerData),
                'totalAnswer' => $answerCount,
                'expertName' => $expertName,
                'viewAnswer' => $viewAnswer
            )
        );
    }

    public function getQuestions($category_id = '', $limit = 10, $order = 'latest')
    {
        $category_id = mysql_real_escape_string($category_id);

        $query = "
            SELECT
                ". $this->question_table .".*,
                ". $this->category_table .".name
                FROM
                ". $this->question_table ."
                INNER JOIN ". $this->category_table ." ON ". $this->question_table .".category_id = ". $this->category_table .".id
                WHERE ". $this->question_table .".status = '1'

            ";
        if (!empty($category_id))
        {
            $query .= " AND ". $this->question_table .".category_id = '". $category_id ."'";
        }

        if ( $order == 'popular')
        {
            $query .= " ORDER BY answer DESC, submitted DESC ";
        }else{
            $query .= " ORDER BY submitted DESC ";
        }

        $query .= " LIMIT $limit";

        return $this->db->get_results($query);
    }

    public function getAnswers($qid)
    {
        return $this->db->get_results("SELECT * FROM ". $this->answer_table ." WHERE status = '1' AND qid = '$qid' ORDER BY is_expert DESC, submitted DESC");
    }

    public function getCategories()
    {
        return $this->db->get_results("SELECT * FROM ".$this->category_table." ORDER BY weight ASC");
    }

    /*
     * TODO: Recheck ESI call insteall call function get avatar
     * ESI url should be : wheels_esi_include(get_template_directory_uri() . '/esi/avatar.php?ID='.$user->ID.'&size=48')
     */
    private function genereateAnswerHTML($user, $answer)
    {
        $html = '';
        if( $answer->is_expert )
        {
            $html .= '
                <li class="answer" id="li-comment-'. $answer->id .'">
                <article class="comment" id="comment-'. $answer->id .'">
                    <div class="avatar-wrap">
                        '. get_avatar($user->ID, 48) .'
                    </div>
                    <div class="comment-wrap">
                        <footer class="comment-meta">

                            <div class="comment-author vcard">
                                <span class="fn">'. $user->display_name .'\'s Answer</span>
                            </div>

                        </footer>

                        <div class="comment-content">'. apply_filters('the_content', stripslashes($answer->answer) ) .'</div>

                    </div>
                </article>
            </li>
            ';
        }else{
            $html .= '<li>
            <article class="comment" id="comment-'. $answer->id .'">
            Your answer submitted successfully and pending for review. You will be notify when admin approve your answer
            </article>
            </li>
            ';
        }
        return $html;
    }

    public function getDefaultCategoryId()
    {
        return $this->db->get_var("SELECT id FROM ". $this->category_table ." ORDER BY weight ASC LIMIT 1 ");
    }

    public function link($categoryId = 0, $questionId = 0, $answerId = 0)
    {
        return '/guides/?category='.$categoryId.'#answer-centre/'.$questionId;
    }

    public function invalidateUrls($categoryId)
    {
        //$urls['cat-'.$category_id] = site_url().'/guides/?category_id='.$category_ids;

        $urls['widget-home'] = get_template_directory_uri().'/esi/answer-center/home-answer-center-widget.php';
        $urls['widget-footer'] = get_template_directory_uri().'/esi/answer-center.php';
        $urls['cat'] = get_template_directory_uri().'/esi/answer-center/answer-center-main.php?category=';
        $urls['cat-'.$categoryId] = get_template_directory_uri().'/esi/answer-center/answer-center-main.php?category='.$categoryId;
        $urls['cat-2-'.$categoryId] = get_template_directory_uri().'/esi/answer-center/answer-center-main.php?category='.$categoryId."#answer-centre";

        \Emicro\Plugin\Varnish::purgeAll($urls);
    }


}