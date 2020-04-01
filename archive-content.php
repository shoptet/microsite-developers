<?php /* Template Name: Archive Content */ ?>

<?php if( !isset($singleView) || $singleView ) { get_header(); }  ?>

<section class="section section-primary">
    <div class="section-inner container">
    <?php if( !isset($singleView) || $singleView ) { get_template_part( 'template-parts/utils/content', 'breadcrumb' ); ?>

        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>

    <?php } else { ?>

        <h2><?php echo apply_filters( 'get_the_title', $page->post_title ); ?></h2>
        <?php echo apply_filters( 'the_content', $page->post_content ); ?>

    <?php } ?>

    <?php

    $query_ID = ($page->ID) ? $page->ID : $post->ID;

    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $query_ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
     );


    $parent = new WP_Query( $args );

    if ( $parent->have_posts() ) : ?>

        <div>

            <?php
            $counter = 0;
            while ( $parent->have_posts() ) : $parent->the_post();
                if ($counter % 2 == 0):
                echo '</div><div class="row row-indented"><!--row -->';
                endif; ?>

                <div class="col-12 col-md-6 homepage-archive-excerpt">

                    <h3><?php the_title(); ?></h3>

                    <?php the_excerpt(); ?>

                    <?php

                    $post_id = get_the_ID();
                    $more_button_href = get_post_meta($post_id, 'more_button_href', true);
                    $more_button_text = get_post_meta($post_id, 'more_button_text', true);

                    if($more_button_href && $more_button_text) { ?>


                    <a href="<?php echo $more_button_href; ?>" target="_blank" class="btn btn-secondary">
                        <?php echo _e($more_button_text, 'shoptet'); ?>
                    </a>

                    <?php
                    } else { ?>

                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn btn-secondary">
                        <?php echo _e('Go to article', 'shoptet'); ?>
                    </a>

                    <?php } ?>

                </div>

            <?php $counter++; ?>

            <?php endwhile; ?>

        </div>

    <?php endif; wp_reset_postdata(); ?>

    <?php if( !isset($singleView) || $singleView ) { get_template_part( 'template-parts/page/content', 'widget' ); }  ?>

    </div>
</section>

<?php if( !isset($singleView) || $singleView ) { get_footer(); }  ?>
