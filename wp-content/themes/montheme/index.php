    <?php get_header() ?>
    <main>
    <div class="row">
        <div class="col-sm-8 bloc-main">
            <?php
                if (have_posts()) : while (have_posts()) : the_post();
                get_template_part('content', get_post_format());
                endwhile;
                endif;
            ?>
        </div>
        <?php get_sidebar() ?>
    </div>
    </main>
    <?php get_footer() ?>


