<?php
/*
 * Template Name: News & Features
 */
?>
<?php get_header('meta')?>
<!-- begin #main-->
<div id="main" role="main" class="clearfix">
    <!-- begin news article-->
    <div id="news-features" class="section-container clearfix">
        <?php wheels_breadcrumb()?>

        <div class="row"><!-- begin .carousel-->
            <div id="newsfeaturesCarousel" data-controller="CarouselController" class="carousel slide">
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="feature-container prime tl"><img src="img/cars/vehicle-432x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling sports sedan gets even better&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tm"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling  sports sedan&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tr"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>Terrific inner city electric car &mdash; if you can take the range anxiety&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                    </div>
                    <div class="item ">
                        <div class="feature-container prime tl"><img src="img/cars/vehicle-432x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling sports sedan gets even better&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tm"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling  sports sedan&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tr"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>Terrific inner city electric car &mdash; if you can take the range anxiety&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                    </div>
                    <div class="item ">
                        <div class="feature-container prime tl"><img src="img/cars/vehicle-432x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling sports sedan gets even better&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tm"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>World's best-selling  sports sedan&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                        <div class="feature-container tr"><img src="img/cars/vehicle-208x240.jpg" alt="2012 Honda Civic"/>
                            <div class="copy">
                                <div class="pos"><a href="#">
                                    <h4>Terrific inner city electric car &mdash; if you can take the range anxiety&nbsp;&raquo;</h4>
                                    <span class="author">Jim Kenzie</span></a></div>
                            </div>
                            <a href="#" class="comment-count">922</a>
                            <div class="overlay">&nbsp;</div>
                        </div>
                    </div>
                </div>
                <a href="#newsfeaturesCarousel" data-slide="prev" class="carousel-control left">&lsaquo;</a><a href="#newsfeaturesCarousel" data-slide="next" class="carousel-control right">&rsaquo;</a></div>
            <!-- end .carousel-->
        </div>

        <div class="row">
            <!-- begin .latest-news-->
            <div class="latest-news">
                <h3>Latest News</h3>
                <?php
                $latest_news = wp_cache_get('news-future-lading-latest-news', 'wheels');
                if(!$latest_news){
                    $latest_news = wheels_news_get();
                    wp_cache_add('news-future-lading-latest-news', $latest_news, 'wheels');
                }
                ?>
                <div class="col">
                    <div class="pos">
                        <?php
                        $loop = 1;
                        foreach($latest_news as $post){ setup_postdata($post);
                        ?>
                        <a href="<?php the_permalink()?>"><?php the_title()?></a>

                        <?php
                            if($loop == 5) echo '</div></div><div class="col"><div class="pos">';
                            $loop++;
                        }?>
                    </div>
                </div>
            </div>
            <!-- end .latest-news-->
            <div class="mrec-ad"><a href="#" target="_blank"><img src="img/ads/mrec.png" width="300" height="250" alt=""/></a></div>
        </div>

        <div class="row"><!-- begin .features-->
            <div class="features">
                <h3>Features</h3>
                <ul>
                    <li><a href="#"><img src="img/cars/vehicle-204x115.jpg" alt=""/>
                        <p>Electric Cars</p>
                    </a></li>
                    <li><a href="#"><img src="img/cars/vehicle-204x115.jpg" alt=""/>
                        <p>2012 Toronto International Auto Show</p>
                    </a></li>
                    <li><a href="#"><img src="img/cars/vehicle-204x115.jpg" alt=""/>
                        <p>2012 Detroit International Auto Show</p>
                    </a></li>
                </ul>
                <div class="col">
                    <div class="pos">
                        <h4>More Features</h4>
                        <ul>
                            <li><a href="#">Rumours & Scoops</a></li>
                            <li><a href="#">New Launches</a></li>
                            <li><a href="#">Concept Cars</a></li>
                            <li><a href="#">Technology</a></li>
                            <li><a href="#">Motorsports</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end .features-->
        </div>

        <div class="row"><!-- begin .popular-vehicles-->
            <div class="popular-features">
                <h3>Popular Features</h3>
                <div class="col">
                    <div class="pos"><a href="#">Volt delay shows 'unnatural' Obama-GM ties, Republicans say</a><a href="#">Video: When all-season tires hit black ice</a><a href="#">Video: Icy hill leads to winter carmageddon in Utah</a><a href="#">The world's first folding car makes its debut</a></div>
                </div>
                <div class="col">
                    <div class="pos"><a href="#">BMW engineers create 'robot car' that can drive itself</a><a href="#">GM faces task of mending Volt's image after safety probe ends</a><a href="#">Give in to the 'bark side': Volkswagen teaser ad goes to the dogs</a><a href="#">Fiat 500 set to grow a pair of doors</a></div>
                </div>
                <div class="col last">
                    <div class="pos"><a href="#">Top 10 tips for buying a used car</a><a href="#">How much will it cost you to own a car?</a><a href="#">OPP ramping up enforcement after spike in traffic deaths</a></div>
                </div>
            </div>
            <!-- end .popular-vehicles-->
        </div>

        <div class="row section-row">
            <?php wheels_esi_include(get_template_directory_uri().'/esi/weekly-debate.php?post_id='.get_the_ID())?>
            <?php wheels_esi_include(get_template_directory_uri().'/esi/our-experts.php')?>
        </div>

        <div class="row"><!-- begin .used-listings-->
            <?php wheels_esi_include(get_template_directory_uri().'/esi/answer-center.php')?>
            <?php wheels_esi_include(get_template_directory_uri().'/esi/more-news.php')?>
            <?php wheels_esi_include(get_template_directory_uri().'/esi/wheels-guides.php')?>
        </div>

    </div>
    <!-- end #main-->
    <?php get_footer()?>