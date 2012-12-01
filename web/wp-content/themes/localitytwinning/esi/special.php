<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$theme_option = get_option("wheels_theme_options");
$term = get_term($theme_option['special_feature_term'], 'feature-category');

$postModel   = new \Emicro\Model\Post($wpdb);
$features    = $postModel->getAll(array('post_type' => 'feature', 'limit' => 6, 'taxonomy' => $term->taxonomy, 'term' => $term->slug,
'custom_field' => true));

if(!empty($term)):
?>
<div class="row"><!--Begin .special section-->
    <div class="special"><h3>Special</h3>

        <div class="electric-cars">
            <div class="section-head">
                <h4><?php echo $term->name ?></h4>

                <p><?php echo $term->description ?></p>

                <?php //echo get_sponsor_markup('linkOnly') ?>
                <?php if(!DISABLE_ALL_AD):?>
                <!--JavaScript Tag // Tag for network 5214: Olive Media // Website: Wheels // Page: Homepage // Placement: wheels_homepage_240x50_1 (1145243) // created at: Jun 15, 2012 12:19:58 PM-->
                <script language="javascript">
                    <!--
                    var curDateTime = new Date();
                    var offset = -(curDateTime.getTimezoneOffset());
                    if (offset > 0)
                        offset = "+" + offset;

                    if (window.adgroupid == undefined) {
                        window.adgroupid = Math.round(Math.random() * 1000);
                    }
                    document.write('<scr'+'ipt language="javascript1.1" src="http://adserver.adtechus.com/addyn/3.0/5214.1/1145243/0/1282/ADTECH;loc=100;size=; target=_blank;key=key1+key2+key3+key4;grp='+window.adgroupid+';misc='+new Date().getTime()+';aduho='+offset+';rdclick="></scri'+'pt>');
                    //-->
                </script>
                <noscript>
                    <a href="http://adserver.adtechus.com/adlink/3.0/5214.1/1145243/0/1282/ADTECH;loc=300;size=; key=key1+key2+key3+key4;rdclick=" target="_blank"><img src="http://adserver.adtechus.com/adserv/3.0/5214.1/1145243/0/1282/ADTECH;loc=300;key=key1+key2+key3+key4" border="0" width="240" height="50"></a>
                </noscript>
                <!-- End of JavaScript Tag -->
                <?php endif; ?>
            </div>

            <ul class="listing">

                <?php foreach($features as $key => $post): setup_postdata($post); ?>

                <li class="vehicle<?php if($post->sponsor_id) echo ' sponsored' ?>" <?php if(in_array($key, array(2,4))) echo ' style="clear: both;"' ?>>
                    <div class="wrap">
                        <a href="<?php the_permalink() ?>">
                            <?php the_post_thumbnail('120x68', array('width'=>120, 'height'=>68)) ?>
                            <div class="copy">
                                <span class="title"><?php echo character_limiter(get_the_title(), 60, '&hellip;') ?></span>
                                <strong>By <?php the_author() ?></strong>
                            </div>
                        </a>
                        <?php //if($post->sponsor_id) echo get_sponsor_markup('title') ?>
                    </div>
                </li>

                <?php endforeach ?>

            </ul>
        </div>
    </div>
    <!--End .special section-->
</div>
<?php endif; ?>