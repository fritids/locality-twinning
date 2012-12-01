<?php
/*
Plugin Name: our expert
Plugin URI: 
Description: Displays experts latest posts
Version: 1.0.0
Dependency: cimy-user-extra-fields plugin required
Author: mri
Author 
License: GPL2
*/

// Start class wheels_expert_widget //

class wheels_expert_widget extends WP_Widget
{

// Constructor //

	function wheels_expert_widget() {
		$widget_ops = array( 'classname' => 'wheels_expert_widget', 'description' => 'Displays experts latest posts' ); // Widget Settings
		$control_ops = array( 'id_base' => 'wheels_expert_widget' ); // Widget Control Settings
		$this->WP_Widget( 'wheels_expert_widget', 'Expert', $widget_ops, $control_ops ); // Create the widget
	}

    // Extract Args //

	function widget($args, $instance) {

        global $wpdb;
        $postModel = new \Emicro\Model\Post($wpdb);
        $authorsResult = $postModel->getExpertAuthorIds();

		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']); // the widget title
		$postnumber 	= $instance['post_number']; // the number of posts to show

        // Before widget //
		echo $before_widget;

        ?>
        <p>

        <div class="our-experts">
            <h3>স্যানিটেশন বিশেষজ্</h3>
            <?php
            if(count($authorsResult))
            {
            ?>

            <div class="clearfix related" data-controller="TabsController">
                <div class="tab-nav">
                    <ul>
                    <?php
                    foreach($authorsResult as $author)
                    {
                        $userdata = get_userdata($author->post_author);
                    ?>
                        <li class="on">
                            <a href="#" class="active">
                                <div class="img-container">
                                    <?php echo get_avatar($author->post_author, 61) ?>
                                </div>

                                <span><?php echo $userdata->display_name; ?></span>
                            </a>
                            <img class="nub" alt="" src="<?php echo get_template_directory_uri() ;?>/img/dialog-nub-author.png">
                        </li>
                    <?php
                    }//end foreach
                    ?>
                    </ul>
                </div><!--tab-nav-->
                <div class="tabs">
                    <?php
                    foreach($authorsResult as $key => $author)
                    {

                        $the_query = new WP_Query('post_type=any&posts_per_page=5&author='.$author->post_author);
                        //var_dump($the_query->post_count);
                        if($the_query->post_count > 0):?>
                            <div class="tab related" style="display: <?php echo ($key == 0) ? 'block' : 'none' ?> ;">
                                <div class="viewport">
                                    <h4><?php echo get_user_meta($author->post_author, 'first_name', true).' '.get_user_meta($author->post_author, 'last_name', true) ?>'s Recent Articles</h4>
                                    <ul>
                            <?php
                        endif;

                        $loop = 1;
                        while ( $the_query->have_posts() ) : $the_query->the_post();
                            $maxChar = (in_array($loop, array(1, 3))) ? 50 : 30;
                        ?>
                                <li class="article-info slide">
                                    <div class="wrap">
                                        <a href="<?php the_permalink() ?>"><?php echo character_limiter(strip_tags(get_the_title()), $maxChar,'&hellip;'); ?></a>
                                    </div>
                                </li>
                        <?php
                        $loop++;
                        endwhile;

                        if($the_query->post_count > 0): ?>
                                    </ul>
                                </div>
                            </div>
                        <?php
                        endif;

                        // Reset Post Data
                        wp_reset_postdata();

                    }//end foreach
                ?>

                </div>
            </div>
        <?php } //end count ?>
        </div>


    </p>
    <?php

    // After widget //

		echo $after_widget;

	}

    // Update Settings //
	function update($new_instance, $old_instance) {
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		return $instance;
	}

// Widget Control Panel //

	function form($instance) 
	{

	$defaults = array( 'title' => 'Experts Latest Posts', 'post_number' => 3);
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('post_number'); ?>"><?php _e('Number of posts to display'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo $instance['post_number']; ?>" />
	</p>
	<?php 
	}

}

// End class soup_widget

add_action('widgets_init', create_function('', 'return register_widget("wheels_expert_widget");'));
?>