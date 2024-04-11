<?php get_header(); ?>
    <div class="container">
        <?php  
        if ( has_post_thumbnail() ) {
                ?>
                <div class="main-img"><?php the_post_thumbnail();?> </div>

                <?php       
            }
        ?>

        <div class="custom-fields">

        
                <div class="filed">
                    <p><b>Площадь</b> - <?php echo esc_html( get_field('area') ); ?></p>
                </div>
                <div class="filed">
                    <p><b>Стоимость</b> - <?php echo esc_html( get_field('price') ); ?></p>
                </div>
                <div class="filed">
                    <p><b>Адрес</b> - <?php echo esc_html( get_field('address') ); ?></p>
                </div>
                <div class="filed">
                    <p><b>Жилая площадь</b> - <?php echo esc_html( get_field('living_space') ); ?></p>
                </div>
                
                <div class="filed">
                    <p><b>Этаж</b> - <?php echo esc_html( get_field('floor') ); ?></p>
                </div>
            </div>
            <p class="description"> <?php echo get_the_content(); ?></p>


            <div class="taxanomy">


            <?php
                // Assuming $post_id is the ID of the post for which you want to display the taxonomy terms
                    global $post;

                    // Get the terms for the current post in the 'property_type' taxonomy
                    $terms = get_the_terms( $post->ID, 'property_type' );



                    if ( $terms && ! is_wp_error( $terms ) ) {
                        $term_links = array();

                        foreach ( $terms as $term ) {
                            $term_links[] = '<a href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
                        }

                        echo implode( ', ', $term_links );
                    }

            ?>

        </div>
    </div>
<?php get_footer(); ?>