<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';?>

<h5>Inside</h5>
<ul>
    <?php
    foreach(get_terms('guides-category', array('hide_empty'=>false)) as $term):
        ?>
    <li>
        <a href="<?php echo get_term_link($term)?>">
            <?php echo $term->name ?>
        </a>
    </li>
    <?php
    endforeach;
    ?>
</ul>