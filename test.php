<?php get_header(); ?>
    <div class="latest-news-block">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="top-news-block">
                        <?php
                        $term = get_queried_object();
                        wp_reset_query();
                        $args = array(
                            'post_type'      => 'news',
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                            'posts_per_page' => 1,
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'news_category',
                                    'field'    => 'slug',
                                    'terms'    => $term->slug,
                                ),
                            ),
                        );

                        $loop = new WP_Query($args);
                        if ($loop->have_posts()) {
                            while ($loop->have_posts()) : $loop->the_post(); ?>
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
                            <?php endwhile;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="sidebar">
                        <div class="news-latest-list latest-list">
                            <div class="sidebar-title">Popular News</div>
                            <ul class="cat-news-popular">
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
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </li>
                                        <?php wpb_track_post_views(get_the_ID()); ?>
                                    <?php endforeach;
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$term = get_queried_object();

wp_reset_query();
$args  = array(
    'post_type'      => 'news',
    'posts_per_page' => 3,
    'offset'         => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'news_category',
            'field'    => 'slug',
            'terms'    => $term->slug,
        ),
    ),
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
    $permalink      = get_the_permalink();

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
        'published_date' => $published_date,
        'permalink'      => $permalink
    );
    array_push($news_posts_array, $news_post_single);
}
$newsJSON = json_encode($news_posts_array);
?>
    <div class="news-listing-cat">
        <div class="container">
            <div class="masonry"></div>
        </div>
        <div class='loader'>
            <div class='spinner'>
                <div class='dot1'></div>
                <div class='dot2'></div>
            </div>  
        </div>
        <input type="hidden" class="showMore" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-category="<?php echo $term->slug; ?>">
    </div>

    <script>
        var json =  <?php echo $newsJSON;?>;
    </script>
    

<?php get_footer(); ?>
