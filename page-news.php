<?php get_header() ?>

<div class="latest-news-block">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="top-news-block">
                    <?php
                    query_posts('post_type=news&showposts=1&order=DESC');
                    while (have_posts()):
                    the_post();
                    ?>
                    <div class="top-news-img">
                        <?php the_post_thumbnail("medium_large"); ?>
                        <div class="overlay"></div>
                    </div>
                    <div class="top-news-content">
                        <div class="latest-news-title">
                            <a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                        <div class="latest-news-content">
                            <?php $content = get_the_content();
                            echo mb_strimwidth($content, 0, 125, '...') ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile;
            wp_reset_query(); //resetting the page query and loop ?>
            <div class="col-md-3">
                <div class=" sidebar top-stories">
                    <div class="sidebar-title">Most Popular</div>
                    <ul class="news-list">
                        <?php
                        global $post;
                        $args    = array(
                            'numberposts' => 4,
                            'post_type'   => 'news',
                            'meta_key'    => 'wpb_post_views_count',
                            'orderby'     => 'meta_value_num',
                            'order'       => 'DESC'
                        );
                        $myposts = get_posts($args);
                        foreach ($myposts as $post) : setup_postdata($post); ?>
                            <li>
                                <a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="posted-date">
                                    <i class="sm-icon-date">
                                    </i><?php the_time('F jS, Y'); ?>
                                </div>
                            </li>
                            <?php wpb_track_post_views(get_the_ID()); ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$args  = array(
    'post_type' => 'news',
);
$query = new WP_Query($args);

$news_posts_array = [];
while ($query->have_posts()) {
    $query->the_post();

    $title          = get_the_title();
    $content        = get_the_content();
    $featured_image = get_the_post_thumbnail_url();
    $categories     = get_the_terms(get_the_ID(), 'news_category');
    $published_date = get_the_date();


    $image_id = get_post_thumbnail_id();
    list($url, $width, $height) = wp_get_attachment_image_src($image_id, 'post-thumbnail');

    $post_category = [];
    foreach ($categories as $category) {
        if (sizeof($categories) > 1) {
            array_push($post_category, $category->name);
        } else {
            array_push($post_category, $category->name);
        }
    }


    $news_post_single = array(
        'title'          => $title,
        'content'        => $content,
        'featured_image' => $featured_image,
        'category'       => $post_category,
        'width'          => $width,
        'height'         => $height,
        'published_date' => $published_date
    );
    array_push($news_posts_array, $news_post_single);
}
$newsJSON = json_encode($news_posts_array);
?>

<div class="news-listing">
    <div class="container">
        <div class="masonry"></div>
    </div>
</div>

<?php
wp_enqueue_script('masonry-custom', get_template_directory_uri() . '/js/masonry.js', [], '', true);
wp_enqueue_style('masonry-css', get_template_directory_uri() . '/css/masonry.css');
add_action('wp_footer', function () use ($newsJSON) { ?>
    <script>
        jQuery(function () {
            jQuery('.masonry').masonry({
                data: <?php echo $newsJSON;?>
            });
        });
    </script>
    <?php
}, 100);

?>
<!-- <div class="news-listing">
    <div class="container">
        <?php
//echo do_shortcode ('[simple_masonry sm_post_type="news"]') ?>
    </div>
</div> -->

<?php get_footer() ?>

