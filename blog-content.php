<?php /* Template Name: Blog Content */ ?>


<?php if( !isset($singleView) || $singleView ) { get_header();  ?>

<section class="section section-hidden">
    <div class="section-inner container">
        <?php the_title( '<h1>', '</h1>' ); ?>
    </div>
</section>

<section class="section section-primary">
    <div class="section-inner container">
        <?php get_template_part( 'template-parts/utils/content', 'breadcrumb' ); ?>

        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>

        <?php
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

        $prepareQuery = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'paged' => $paged,
        );

        $query = new WP_Query($prepareQuery);

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                get_template_part( 'template-parts/post/content', 'excerpt' );
            endwhile;

            $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;

            get_template_part( 'template-parts/utils/content', 'pagination' );

        else : ?>

            <h2><?php _e( 'We are sorry, no results were found', 'shoptet' ); ?></h2>

            <p><?php _e( 'Try to use search, please', 'shoptet' ); ?></p>

            <?php get_template_part( 'template-parts/search/content', 'search' ); ?>

        <?php
        endif;
        ?>
    </div>
</section>

<?php get_footer(); } else { $newsPermalink = get_permalink($page->ID); ?>

<section class="section section-primary">
    <div class="section-inner container">
        <h2><?php echo apply_filters( 'get_the_title', $page->post_title ); ?></h2>
        <?php echo apply_filters( 'the_content', $page->post_content ); ?>

        <div>

        <?php
        query_posts('post_type=post&post_status=publish&posts_per_page=4');

        if ( have_posts() ) :
            $counter = 0;
            while ( have_posts() ) : the_post();
                if ($counter % 2 == 0):
                echo '</div><div class="row row-indented"><!--row -->';
                endif;
            ?>

            <div class="col-12 col-md-6 homepage-blog-excerpt">
                <article>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                    <?php get_template_part( 'template-parts/post/content', 'meta' ); ?>

                    <?php the_excerpt(); ?>

                    <!--<a href="<?php the_permalink(); ?>" class="btn btn-secondary"><?php echo _e('Go', 'shoptet'); ?></a>-->
                </article>
            </div>

            <?php $counter++; ?>

            <?php
            endwhile;

        else:
            e_('No results found', 'shoptet');
        endif;
        ?>

        </div>

        <div class="row">
            <div class="col-12">
                <p class="text-right">
                    <a href="<?php echo $newsPermalink; ?>" class="btn btn-secondary"><?php echo _e('All news', 'shoptet'); ?></a>
                </p>
            </div>
        </div>
    </div>
</section>



<?php } ?>
