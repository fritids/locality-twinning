<?php include('../../../wp-load.php')?>
<?php 
$args = array('post_type' => 'news');
if(!empty($_GET['make']) && $_GET['make'] != '-1'){
	$args['tax_query'][] = array(
			'taxonomy' => 'make',
			'field' => 'id',
			'terms' => $_GET['make']
	);
}
if(!empty($_GET['class']) && $_GET['class'] != '-1'){
	$args['tax_query'][] = array(
			'taxonomy' => 'class',
			'field' => 'id',
			'terms' => $_GET['class']
	);
}
if(!empty($_GET['model']) && $_GET['model'] != '-1'){
	$args['tax_query'][] = array(
			'taxonomy' => 'model',
			'field' => 'id',
			'terms' => $_GET['model']
	);
}
if(!empty($_GET['year']) && $_GET['year'] != '-1'){
	$args['tax_query'][] = array(
			'taxonomy' => 'year',
			'field' => 'id',
			'terms' => $_GET['year']
	);
}
$the_query = new WP_Query( $args );
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<form action="<?php echo plugins_url( 'wheels-vehicle-finder.php' , __FILE__ )?>" method="get">
<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td>Make</td>
    <td>Class</td>
    <td>Model</td>
    <td>Year</td>
  </tr>
  <tr>
    <td><?php wp_dropdown_categories( 'taxonomy=make&hide_empty=0&hierarchical=0&name=make&show_option_none=Select&selected='.$_GET['make'] ); ?></td>
    <td><?php wp_dropdown_categories( 'taxonomy=class&hide_empty=0&hierarchical=0&name=class&show_option_none=Select&selected='.$_GET['class'] ); ?></td>
    <td><?php wp_dropdown_categories( 'taxonomy=model&hide_empty=0&hierarchical=0&name=model&show_option_none=Select&selected='.$_GET['model'] ); ?></td>
    <td><?php wp_dropdown_categories( 'taxonomy=year&hide_empty=0&hierarchical=0&name=year&show_option_none=Select&selected='.$_GET['year'] ); ?></td>
  </tr>
  <?php while ( $the_query->have_posts() ) : $the_query->the_post();?>
  <?php endwhile;?>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <?php while ( $the_query->have_posts() ) : $the_query->the_post();?>
  <tr>
    <td width="68%"><?php the_title()?></td>
    <td width="5%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
    <td width="19%"><span class="savesend">
      <input type="button" value="Insert into Post" class="send_vehicle" id="send_vehicle" name="<?php the_title()?>" />
    </span></td>
  </tr>
  <?php endwhile;?>
</table>
<input type="submit" value="Submit" />
</form>
<p class="savesend">&nbsp;</p>
<p class="savesend">&nbsp;</p>
<script type="text/javascript">
jQuery(document).ready(function(){
	$('.send_vehicle').click(function(){
		$('#vehicle_id', top.document).val( $(this).attr('name') );
		self.parent.tb_remove();
	});
});
</script>