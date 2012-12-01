<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>

<!-- begin .browse-categories-->
<div class="browse-categories">
    <ul>
        <li>
            <a href="/vehicle-finder/?category=Luxury">
                <img src="<?php echo get_template_directory_uri();?>/img/cars/nav-category-luxury.jpg" alt="Luxury">

                <div class="copy"><p>Luxury</p></div>
            </a>
        </li>

        <li>
            <a href="/vehicle-finder/?category=Fuel Efficient">
                <img src="<?php echo get_template_directory_uri();?>/img/cars/nav-category-fuel-efficient.jpg"
                     alt="Fuel Efficient">

                <div class="copy"><p>Fuel Efficient</p></div>
            </a>
        </li>
        <li>
            <a href="/vehicle-finder/?category=First Car">
                <img src="<?php echo get_template_directory_uri();?>/img/cars/nav-category-first-car.jpg"
                     alt="First Car">

                <div class="copy"><p>First Car</p></div>
            </a>
        </li>
        <li>
            <a href="/vehicle-finder/?category=Family Friendly">
                <img src="<?php echo get_template_directory_uri();?>/img/cars/nav-category-family.jpg"
                     alt="Family Friendly">

                <div class="copy"><p>Family Friendly</p></div>
            </a>
        </li>
        <li>
            <a href="/vehicle-finder/?category=City Driving">
                <img src="<?php echo get_template_directory_uri();?>/img/cars/nav-category-city.jpg" alt="City Driving">

                <div class="copy"><p>City Driving</p></div>
            </a>
        </li>
    </ul>
</div>

<form method="post" action="/vehicle-finder" id="header-vehicle-finder-category-form" style="display: none;">
    <input type="hidden" name="category" id="vehicle-finder-category" value="">
</form>
<!-- end .browse-categories-->