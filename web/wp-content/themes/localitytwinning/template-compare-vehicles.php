<?php
/*
        Template Name: Compate Vehicles
 */
?>
<?php get_header('meta') ?>
<body class="page article news mobile-page"><!-- begin #container-->
<div id="container" data-role="page"><!-- begin #topads-->
    <?php get_header()?>
    <!--====================================================================================================-->
    <!-- begin article.content-->
    <article class="content"><!-- begin header-->
        <header><h1>Compare Vehicles</h1>

            <p>Create your Comparison then add it to My Wheels or share it to get other's feedback.</p>
            <!--being .compare-utility-->
            <ul class="compare-utility">
                <li><a href="#" data-controller="ModalTriggerController" data-modal="#ask-friends-modal"
                       class="ask-friends"><span>&nbsp;</span>Ask Friends
                </a></li>
                <li><a href="#" data-controller="ModalTriggerController" data-modal="#add-to-wheels-modal"
                       class="my-wheels"><span>&nbsp;</span>Add to My Wheels
                </a></li>
            </ul>
            <!--end .compare-utility--></header>
        <!-- end header--><!-- begin .wrap-->
        <div class="wrap"><!-- begin .compare-container-->
            <div data-controller="CompareController" class="compare-container compare2 clearfix">
                <!-- begin .compare-filters-->
                <div class="compare-filters"><label>Compare up to three vehicles</label><!-- begin .form-container-->
                    <div class="form-container">
                        <form><!-- begin .make-container-->
                            <div class="make-container"><select name="make-selector"
                                                                data-controller="ComboboxController"
                                                                class="compare-selector ui-light">
                                <option>Make</option>
                                <option>Acura</option>
                                <option>Audi</option>
                                <option>BMW of North America</option>
                                <option>Buick</option>
                                <option>Cadillac</option>
                                <option>Chevrolet</option>
                                <option>Chrysler</option>
                                <option>Dodge</option>
                                <option>Eagle</option>
                                <option>Ferrari</option>
                                <option>Ford</option>
                                <option>General Motors</option>
                                <option>GMC</option>
                                <option>Honda</option>
                                <option>Hummer</option>
                                <option>Hyundai</option>
                                <option>Infiniti</option>
                                <option>Isuzu</option>
                                <option>Jaguar</option>
                                <option>Jeep</option>
                                <option>Kia</option>
                                <option>Lamborghini</option>
                                <option>Land Rover</option>
                                <option>Lexus</option>
                                <option>Lincoln</option>
                                <option>Lotus</option>
                                <option>Mazda</option>
                                <option>Mercedes-Benz</option>
                                <option>Mercury</option>
                                <option>Mitsubishi</option>
                                <option>Nissan</option>
                                <option>Oldsmobile</option>
                                <option>Peugeot</option>
                                <option>Pontiac</option>
                                <option>Porsche</option>
                                <option>Saab</option>
                                <option>Saturn</option>
                                <option>Subaru</option>
                                <option>Suzuki</option>
                                <option>Toyota</option>
                                <option>Volkswagen</option>
                                <option>Volvo</option>
                            </select></div>
                            <!-- end .make-container--><!-- begin .model-container-->
                            <div class="model-container"><select name="model-selector"
                                                                 data-controller="ComboboxController"
                                                                 class="compare-selector ui-light">
                                <option>Model</option>
                                <option>ZDX</option>
                                <option>TSX</option>
                                <option>RDX</option>
                                <option>MDX</option>
                                <option>TL</option>
                                <option>RL</option>
                                <option>NSX</option>
                            </select></div>
                            <!-- end .model-container--><!-- begin .year-container-->
                            <div class="year-container"><select name="year-selector"
                                                                data-controller="ComboboxController"
                                                                class="compare-selector ui-light">
                                <option>Year</option>
                                <option>2012</option>
                                <option>2011</option>
                                <option>2010</option>
                                <option>2009</option>
                                <option>2008</option>
                                <option>2007</option>
                                <option>2006</option>
                                <option>2005</option>
                                <option>2004</option>
                                <option>2003</option>
                                <option>2002</option>
                                <option>2001</option>
                                <option>2000</option>
                                <option>1999</option>
                                <option>1998</option>
                            </select></div>
                            <!-- end .year-container--><!-- begin .action-container-->
                            <div class="action-container"><input type="submit" value="Add" class="formbtn green"/></div>
                            <!-- end .action-container--></form>
                    </div>
                    <!-- end .form-container--><!-- begin .suggestions-container-->
                    <div class="suggestions-container"><h4>Suggestions</h4><!-- begin #suggestion-list-->
                        <ul id="suggestion-list">
                            <li class="odd">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="<?php echo get_template_directory_uri(); ?>/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                            <li class="even">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                            <li class="odd">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                            <li class="even">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                            <li class="odd">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                            <li class="even">
                                <div class="pos"><a href="#" class="suggestion-image"><img
                                    src="/img/cars/suggestion-132x74.png" alt="Vehicle Suggestion"/>

                                    <p class="suggestion-title">2012 Audi A3
                                        <br/>4-door AWD Quattro
                                    </p><a data-id="" href="#" class="compare callout">Compare
                                        <img alt="Compare this vehicle" src="/img/compare-callout.png"/></a></a></div>
                            </li>
                        </ul>
                        <!-- end #suggestion-list--></div>
                    <!-- end .suggestions-container--></div>
                <!-- end .compare-filters-->
                <div class="table-holder"><!-- begin .compare-table top-->
                    <table class="compare-table"><!-- begin .image-row-->
                        <tr class="image-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <div class="compare-image"><a href="#"><img src="/img/cars/compare-212x120.png"
                                                                                alt="Compare vehicle"/></a></div>
                                    <div class="compare-title"><h3><a href="#">2012 Audi A3</a></h3></div>
                                    <a href="#" data-id="799282" class="close">X</a></div>
                            </td>
                            <!-- end compare column--><!-- begin .sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <div class="compare-image"><a href="#"><img src="/img/cars/compare-212x120.png"
                                                                                alt="Compare vehicle"/></a></div>
                                    <div class="compare-title"><h3><a href="#">2011 Volkswagen GTI 2-Door</a></h3></div>
                                    <span class="sponsored">Sponsored</span></div>
                            </td>
                            <!-- end .sponsored--></tr>
                        <!-- end .image-row--><!-- begin .trim-row-->
                        <tr class="trim-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <div class="trim-container"><select data-role="none" name="trim-selector"
                                                                        data-controller="ComboboxController"
                                                                        data-readonly="true"
                                                                        class="compare-trim ui-dark">
                                        <option>4dr Sedan AWD</option>
                                        <option>V6 Convertible GT Names shouldn't be this long</option>
                                        <option>4dr Auto Highline V6</option>
                                        <option>4dr DSG Highline</option>
                                    </select></div>
                                </div>
                            </td>
                            <!-- end compare column--><!-- begin .sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <div class="trim-container"><select data-role="none" name="trim-selector"
                                                                        data-controller="ComboboxController"
                                                                        data-readonly="true"
                                                                        class="compare-trim ui-dark">
                                        <option>Titanium</option>
                                        <option>V6 Convertible GT Names shouldn't be this long</option>
                                        <option>2dr Cpe Man</option>
                                    </select></div>
                                </div>
                            </td>
                            <!-- end .sponsored column--></tr>
                        <!-- end .trim-row--></table>
                    <!-- end .compare-table top--><!-- begin .compare-table bottom-->
                    <table class="compare-table"><!-- begin .data-row-->
                        <tr class="data-row rating-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <div class="rating large">
                                        <div class="value rating-1-5">1-5</div>
                                    </div>
                                    <h4>Wheels Rating</h4></div>
                            </td>
                            <!-- end compare column--><!-- begin sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <div class="rating large">
                                        <div class="value rating-1-0">1-0</div>
                                    </div>
                                </div>
                            </td>
                            <!-- end sponsored column--></tr>
                        <!-- end .data-row--><!-- begin .data-row-->
                        <tr class="data-row rating-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <div class="rating large">
                                        <div class="value rating-3-5">3-5</div>
                                    </div>
                                    <h4>Reader Rating</h4></div>
                            </td>
                            <!-- end compare column--><!-- begin sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <div class="rating large">
                                        <div class="value rating-1-0">1-0</div>
                                    </div>
                                </div>
                            </td>
                            <!-- end sponsored column--></tr>
                        <!-- end .data-row--><!-- begin .data-row-->
                        <tr class="data-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos"><p>Duis sodales pulvinar elit. Maecenas tincidunt metus non lectus
                                    egestas euismod aliquam odio elementum. Etiam rutrum rhoncus velit, eu dictum odio
                                    commodo tristique.</p><h4>Lorem ipsum</h4></div>
                            </td>
                            <!-- end compare column--><!-- begin sponsored column-->
                            <td class="sponsored">
                                <div class="pos"><p>Proin porta ultrices commodo. Vestibulum libero enim, ultricies in
                                    laoreet in, imperdiet in sem. Vivamus vel ante id nisi elementum tincidunt.
                                    Phasellus suscipit sagittis pretium. Fusce lacus elit, porta rhoncus vestibulum nec,
                                    facilisis et erat.</p></div>
                            </td>
                            <!-- end sponsored column--></tr>
                        <!-- end .data-row--><!-- begin .data-row-->
                        <tr class="data-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <ul>
                                        <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                                        <li>Suspendisse feugiat metus id augue egestas ornare.</li>
                                        <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                                        <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                                        <li>Integer venenatis est at nisl pulvinar eget vestibulum urna tempus.</li>
                                        <li>Integer venenatis est at nisl pulvinar eget vestibulum urna tempus.</li>
                                        <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                                        <li>Vestibulum sed nisl dui, congue rutrum tortor.</li>
                                        <li>Integer venenatis est at nisl pulvinar eget vestibulum urna tempus.</li>
                                    </ul>
                                    <h4>Lorem ipsum</h4></div>
                            </td>
                            <!-- end compare column--><!-- begin sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <ul>
                                        <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                                        <li>Integer venenatis est at nisl pulvinar eget vestibulum urna tempus.</li>
                                        <li>Aenean ac ante non enim feugiat ornare eu pulvinar dolor.</li>
                                        <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                                        <li>Nulla nec ante ac ligula vehicula venenatis.</li>
                                        <li>Suspendisse feugiat metus id augue egestas ornare.</li>
                                        <li>Integer venenatis est at nisl pulvinar eget vestibulum urna tempus.</li>
                                    </ul>
                                </div>
                            </td>
                            <!-- end sponsored column--></tr>
                        <!-- end .data-row--><!-- begin .data-row-->
                        <tr class="data-row"><!-- begin compare column-->
                            <td data-column-id="799282">
                                <div class="pos">
                                    <ul>
                                        <li><strong>EPA Classification</strong>: Compact</li>
                                        <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                                        <li><strong>EPA Classification</strong>: Compact</li>
                                        <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                                        <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>Trans Description Cont.</strong>: Automatic</li>
                                        <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                                        <li><strong>EPA Classification</strong>: Compact</li>
                                    </ul>
                                    <h4>Lorem ipsum</h4></div>
                            </td>
                            <!-- end compare column--><!-- begin sponsored column-->
                            <td class="sponsored">
                                <div class="pos">
                                    <ul>
                                        <li><strong>EPA Classification</strong>: Compact</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>Trans Description Cont.</strong>: Automatic</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                                        <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                                        <li><strong>Drivetrain</strong>: Front Wheel Drive</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                        <li><strong>EPA Classification</strong>: Compact</li>
                                        <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                                        <li><strong>Trans Description Cont.</strong>: Automatic</li>
                                        <li><strong>SAE Net Horsepower @ RPM</strong>: 160 @ 6500</li>
                                        <li><strong>Maximum Alternator Capacity (amps)</strong>: -TBD-</li>
                                    </ul>
                                </div>
                            </td>
                            <!-- end sponsored column--></tr>
                        <!-- end .data-row--></table>
                </div>
            </div>
            <!-- end .compare-container--><!-- begin .share-->
            <div class="share"><!-- Begin AddThis Button-->
                <div class="addthis_toolbox addthis_default_style"><a class="addthis_counter"></a></div>
                <script type="text/javascript"
                        src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo ADDTHIS_PUBID ?>"></script>
                <!-- End AddThis Button--></div>
            <!-- end .share--></div>
        <!-- end .wrap-->
        <!--====================================================================================================-->
        <?php get_footer()?>
</body>
</html>