<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
 
<?php
$latest_properties = new WP_Query(array(
    'post_type'      => 'property',
    'posts_per_page' => -1, 
));
if($latest_properties->have_posts()){
?>
<section class="properties-wrap">
    <h2 class="section-name">Недвижимость</h2>
    <div class="properties row">
        <?php
        while ($latest_properties->have_posts()) {
            $latest_properties->the_post();
            ?>
            <div class="property col-lg-2 col-md-4 col-sm-6">
                <?php  
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail', ['class' => 'img-fluid rounded']);
                }
                ?>
                <div class="name">
                    <a href="<?php echo get_the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a>
                </div>
            </div>
            <?php
        }
         // Reset the post data
    }
    wp_reset_postdata();
        ?>
    </div>
</section>


<?php

    $latest_cities = new WP_Query(array(
        'post_type'      => 'city',
        'posts_per_page' => -1, 
    )); 

    if ($latest_cities->have_posts()) {
        ?>
        <section class="cities-wrap">
            <h2 class="section-name">Города</h2>
            <div class="cities">
                <?php
                while ($latest_cities->have_posts()) {
                    $latest_cities->the_post();
                    ?>
                    <div class="city">
                        <?php the_post_thumbnail(); ?>
                        <div class="name">
                            <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        <?php
    }
    wp_reset_postdata();
?>






<section class="add-property-form">
    <h2>Добавить Недвижимость</h2>
    <form id="postForm" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data" style="width:100%;margin:0 auto;max-width:500px">
     <input type="hidden" name="action" value="submit_post">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
            <div class="invalid-feedback">Пожалуйста, введите название.</div>
        </div>
        <div class="mb-3">
            <label for="area" class="form-label">Площадь</label>
            <input type="text" class="form-control" name="area" id="area" placeholder="Площадь" required>
            <div class="invalid-feedback">Пожалуйста, введите территорию.</div>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Стоимость</label>
            <input type="text" class="form-control" name="price" id="price" placeholder="Стоимость" required>
            <div class="invalid-feedback">Пожалуйста, введите цену.</div>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Адрес</label>
            <input type="text" class="form-control" name="address" id="address" placeholder="Адрес" required>
            <div class="invalid-feedback">Пожалуйста, введите адрес.</div>
        </div>
        <div class="mb-3">
            <label for="living_space" class="form-label">Жилая площадь</label>
            <input type="text" class="form-control" name="living_space" id="living_space" placeholder="Жилая площадь" required>
            <div class="invalid-feedback">Пожалуйста, введите в гостиную.</div>
        </div>
        <div class="mb-3">
            <label for="floor" class="form-label">Этаж</label>
            <input type="text" class="form-control" name="floor" id="floor" placeholder="Этаж" required>
            <div class="invalid-feedback">Пожалуйста, введите на этаж.</div>
        </div>
        <div class="mb-3">
            <label for="property_type" class="form-label">Тип недвижимости</label>
            <select class="form-select" id="property_type" name="property_type">
                <option value="">Тип недвижимости</option>
                <?php echo get_property_types_options(); ?>
            </select>
            <div class="invalid-feedback">Пожалуйста, введите на этаж.</div>
        </div>
        <div class="mb-3">
            <label for="property_city" class="form-label">Выберите Город</label>
            <select class="form-select" name="property_city" id="property_city" required>
                <option value="">Выберите город</option>
                <?php
                // Query to get cities
                $cities = new WP_Query(array(
                    'post_type'      => 'city',
                    'posts_per_page' => -1,
                ));
                if ($cities->have_posts()) {
                    while ($cities->have_posts()) {
                        $cities->the_post();
                        ?>
                        <option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
                        <?php
                    }
                }
                wp_reset_postdata();
                ?>
            </select>
            <div class="invalid-feedback">Пажалуйста выберите город.</div>
        </div>
        <div class="mb-3">
            <label for="featuredImage" class="form-label">Выберите главную картинку</label>
            <input type="file" class="form-control" name="featuredImage" id="featuredImage" placeholder="Стоимость" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить Недвижимость</button>
    </form>

    <span class="loader"></span>
</section>

<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Отлично, новый недвижимость добавлено!</h4>
  <p>Вы можете добавить еще!!</p>
</div>


 
<?php
get_footer();
