<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="event-title-container">
            <h1 class="event-title"><?php the_title(); ?></h1>
        </div>

        <!-- <div class="event-content"><?php
                                        //  the_content();
                                        ?></div> -->

        <?php
            $images = array_values(get_attached_media('image',get_the_ID()));
            $images_per_page = 15;
        ?>

        <div class="event-gallery">
            <?php
                for($i = 0;$i< min($images_per_page,count($images));$i++){
                    $img_url = wp_get_attachment_image_url($images[$i]->ID,'large');
                    echo '<div class="event-gallery-img">
                    <img src=" ' .esc_url($img_url) . ' " alt = "">
                    </div>';
                }
            ?>
        </div>

        <?php if(count($images) > $images_per_page) :?>
            <button id="load-more" data-offset = "<?php echo $images_per_page ?>" data-total = "<?php echo count($images) ?>">
                Load More
            </button>
        <?php endif;?>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>