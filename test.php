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

    //echo $publish_date;
    $image_id = get_post_thumbnail_id();
    list($url, $width, $height) = wp_get_attachment_image_src($image_id, 'post-thumbnail');
    /*        $width = $image_size[1];
            $height = $image_size[2];*/
    $post_category = [];
    //printer($categories);
    //echo sizeof($categories);
    foreach ($categories as $category) {
        if (sizeof($categories) > 1) {
            array_push($post_category, $category->name);
        } else {
            array_push($post_category, $category->name);
        }
    }

    //printer($test);
    //compact('title','content','featured_image','category','width','height');
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
