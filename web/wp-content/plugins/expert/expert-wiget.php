<?php
/*
Plugin Name: expert
Plugin URI: 
Description: Displays experts latest posts
Version: 1.0.0
Dependency: cimy-user-extra-fields plugin required
Author: mri
Author 
License: GPL2
*/

// Start class expert_widget //

class expert_widget extends WP_Widget 
{

// Constructor //

	function expert_widget() {
		$widget_ops = array( 'classname' => 'expert_widget', 'description' => 'Displays experts latest posts' ); // Widget Settings
		$control_ops = array( 'id_base' => 'expert_widget' ); // Widget Control Settings
		$this->WP_Widget( 'expert_widget', 'Expert', $widget_ops, $control_ops ); // Create the widget
	}

// Extract Args //

	function widget($args, $instance) {
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']); // the widget title
		$postnumber 	= $instance['post_number']; // the number of posts to show

// Before widget //

		echo $before_widget;

// Title of widget //

		//if ( $title ) { echo $before_title . $title . $after_title; }

// Widget output //

?>
<p>
<?php 
//------------------------
global  $wpdb;
global $post;

$qry = "SELECT ps.post_author FROM ".$wpdb->prefix."posts AS ps ";
$qry .= "INNER JOIN ".$wpdb->prefix."cimy_uef_data AS cd ON ps.post_author = cd.USER_ID ";
$qry .= "INNER JOIN ("; 
$qry .= "SELECT max(ps.post_date) AS mpd  FROM ".$wpdb->prefix."posts AS ps INNER JOIN ".$wpdb->prefix."usermeta AS um ON ps.post_author = um.user_id WHERE um.meta_key = 'wp_user_level' AND um.meta_value = '2' AND ps.post_status = 'publish' GROUP BY ps.post_author LIMIT ".$postnumber;;
$qry .= ") as tbl1 ON ps.post_date = tbl1.mpd WHERE cd.FIELD_ID =  '2' AND cd.VALUE = 'YES'";

$pauthors = $wpdb->get_results( $qry );

?>
	
<div class="our-experts">
<h3>Our Experts</h3>
    <?php
	if(count($pauthors))
	{
	?>
    
    <div class="clearfix related" data-controller="TabsController">
    <div class="tab-nav">
      	<ul>
    	<?php
		foreach($pauthors as $pa)
		{
			//setup_postdata($post);
			$qry = "SELECT usr.display_name,cd.value FROM ".$wpdb->prefix."users usr ";
			$qry .= "INNER JOIN ".$wpdb->prefix."cimy_uef_data AS cd ON usr.ID = cd.USER_ID WHERE cd.FIELD_ID = '1' AND usr.ID = '".$pa->post_author."'";
			$adet = $wpdb->get_results( $qry );
		?>
            <li class="on"><a href="#" class="active">
            <div class="img-container"><img alt="<?php echo $adet[0]->display_name; ?>" src="<?php echo $adet[0]->value; ?>" width="61" height="62"></div>
            <?php //echo substr($adet[0]->value,0,-4).'-thumbnail'.substr($adet[0]->value,-4); ?>
            <span><?php echo $adet[0]->display_name; ?></span></a><img class="nub" alt="" src="/img/dialog-nub-author.png"></li>
		<?php
		}//end foreach
		?>
        </ul>
    </div><!--tab-nav-->
    <div class="tabs">
    	<?php
		foreach($pauthors as $pa)
		{
		?>
            <div class="tab related" style="display: block;">
            <div class="viewport">
            <?php 
			$qry = "SELECT display_name FROM ".$wpdb->prefix."users WHERE ID = '".$pa->post_author."'"; 
			$dn = $wpdb->get_results( $qry );
			?>
            <h4><?php echo $dn[0]->display_name; ?>'s Recent Articles</h4>
			<ul>
				<?php
                $qry = "SELECT ID,post_title FROM ".$wpdb->prefix."posts WHERE post_author = '".$pa->post_author."' AND post_status = 'publish' ORDER BY ID DESC LIMIT 5";
                $eposts = $wpdb->get_results( $qry );
                foreach($eposts as $post)
                {
					setup_postdata($post);
                ?>
                    <li class="article-info slide">
                      <div class="wrap"><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a></div>
                    </li>
                <?php 
                }
                ?>
          </ul>
        </div>
      </div>
	<?php
		}//end foreach
		?>
    
    </div><!--tabs-->
   </div><!--clearfix-->
	<?php }//end count ?>
</div><!--our-experts-->


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

add_action('widgets_init', create_function('', 'return register_widget("expert_widget");'));
?>