<?php get_header(); ?>
<div class="container">
    <?php
    

if (have_posts()) {
    while (have_posts()) {
        the_post();

        
        echo '<h2>' . get_the_title() . '</h2>';

        
        $city_id = get_the_ID();

        
        $args = array(
            'post_type' => 'property', 
            'meta_query' => array(
                array(
                    'key' => 'property_city',
                    'value' => $city_id,
                    'compare' => '=',
                ),
            ),
        );
        $property_query = new WP_Query($args);

        
        if ($property_query->have_posts()) {
            echo '<div class="cities_properties">';
            while ($property_query->have_posts()) {
                $property_query->the_post();
                echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . get_the_title() .'</a>';
            }
            echo '</div>';
            wp_reset_postdata(); 
        } else {
            echo '<p>Нет недвижимости в этом городе.</p>';
        }
    }
} else {
    echo '<p>Город не найден.</p>';
}


    ?>

    <p class="city-desc"><?php echo get_the_content();?></p>
</div>
<?php get_footer(); ?>