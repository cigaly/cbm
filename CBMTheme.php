<?php
class CBMTheme {

    public static function formatDate( $timestamp, $dateFormat = '' ) {
        if (!$dateFormat) {
            $dateFormat = get_option( 'date_format' );
        }
        if (gettype( $timestamp ) == 'string') {
            $timestamp = strtotime($timestamp);
        }
        return date_i18n( $dateFormat, $timestamp);
    }

    public static function displayCategory($cat)
    { ?>
        <div class="3u">
            <section>
                <header>
                    <h2 id="<?= $cat->slug ?>" class="<?= $cat->slug ?>"><a href="<?= get_category_link( $cat->cat_ID ) ?>"><?= $cat->name ?></a></h2>
                </header>
            </section>
        </div>
    <?php }

    public static function categoriesNavigation()
    {
        $categories = get_categories( );
        foreach( $categories as $category ) {
            if ($category->slug != 'uncategorized') {
                self::displayCategory($category);
            }
        }
    }

    // private static function compareByDate($a, $b)
    // {
    //     return strcmp( $b["date"], $a["date"] );
    // }

    public static function getLastPostIDs()
    {
        global $post;
        $p = array();

        foreach ( get_categories() as $category ) {
            if ($category->slug == 'uncategorized') {
                continue;
            }
            $args = array(
                'cat' => $category->term_id,
                'post_type' => 'post',
                'posts_per_page' => '1',
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $p[] = $post->ID;
                } // end while
            } // end if
            // Use reset to restore original query.
            $query->reset_postdata();
        }
        return $p;
    }

    public static function dispalyLastPosts()
    {
        $ids = self::getLastPostIDs();
        $query = new WP_Query( array( 'post__in' => $ids ) );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                self::displayLastPost();
            } // end while
        } // end if
        $query->reset_postdata();
    }

    static private function displayLastPost( ) {
        global $post;
        ?>
            <ul class="style2">
                <p class="<?= $item['slug'] ?>"><?= the_category() ?></p>
                <h3><?= the_title() ?></h3>
                <p><?= the_excerpt() ?></p>
                <p><span class="articledate"><?= the_time( 'd.m.Y' ) ?></span> <a href="<?= get_permalink( $post ) ?>" class="button">Read More </a></p>
            </ul>
            <?php
        // wp_reset_postdata();
    }

    private static function getTheExcerpt( $post )
    {
        setup_postdata( $post );
        $text = get_the_excerpt();
        wp_reset_postdata();
        return $text;
    }

    private static function getTheContent( $post, $more_link_text = null, $stripteaser = false )
    {
        setup_postdata( $post );
        $text = get_the_content( $more_link_text, $stripteaser );
        wp_reset_postdata();
        return $text;
    }

    public static function getLatestPosts( $n = 2 ) 
    {
        $args = array(
            'numberposts' => $n,
            // 'offset' => 0,
            // 'category' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC',
            // 'include' => '',
            // 'exclude' => '',
            // 'meta_key' => '',
            // 'meta_value' =>'',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        );
        // return wp_get_recent_posts( $args, OBJECT );
        return get_posts( $args );
    }

    private static function queryLatestPosts( $n = 2 ) 
    {
        return new WP_Query( array(
            'posts_per_page' => $n,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        ) );
    }

    public static function displayLatestPost()
    {
        global $post;
        $slug = get_the_category( $post->ID )[0]->slug;
        ?>
            <ul class="style1">
                <strong><span class="articledate"><?= the_time(  'd.m.Y H:i' ) ?></span></strong>
                <h3 class="color<?= $slug ?>"><?= the_title() ?></h3>
                <p class="color<?= $slug ?>"><?= the_excerpt() ?></p>
                <p><span class="articledate"><?= the_time( 'd.m.Y' ) ?></span> <a href="<?= get_permalink( $post ) ?>" class="button">Read More </a></p>
            </ul>
    <?php
    }

    public static function displayLatestPosts()
    {
        $q = self::queryLatestPosts();
        if ( $q->have_posts() ) {
            while ( $q->have_posts() ) {
                $q->the_post();
                self::displayLatestPost();
            } // end while
        } // end if
        $q->reset_postdata();
    }

    public static function getTaggedPosts( $tags, $n = 1 )
    {
        $args = array(
            'numberposts' => $n,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array( 
                array( 
                    'taxonomy' => 'post_tag',
                    'field'     => 'slug',
                    'terms' => $tags
                )
            )
        );
        return get_posts( $args );
    }

    public static function queryTaggedPosts( $tags, $n = 1 )
    {
        return new WP_Query( array(
            'numberposts' => $n,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array( 
                array( 
                    'taxonomy' => 'post_tag',
                    'field'     => 'slug',
                    'terms' => $tags
                )
            )
        ) );
    }

    public static function displayMiddlePost( )
    {
        global $post;
        // setup_postdata( $post );
        $cat = get_the_category( $post->ID )[0];
        $slug = $cat->slug;
    ?>
    <section>
      <header>
        <?php 
            if (has_post_thumbnail( $post )) {
                echo get_the_post_thumbnail( $post );
            }
        ?>
        <span class="<?= $slug ?>"><?= the_category() ?></span>
        <h2><?= the_title() ?></h2>
      </header>
      <p><?= the_content( '[...]', true ) ?></p>
      <p><span class="articledate"><?php the_time( 'd.m.Y' ) ?></span> <a href="<?= get_permalink( $post ) ?>" class="button">Read More </a></p>
    </section>
    <?php

        // wp_reset_postdata();
    }

    public static function displayCategoryMiddlePost( $post )
    {
        $html = '<section><header>';
        if (has_post_thumbnail( $post )) {
            $html .= get_the_post_thumbnail( $post );
        }
        $cat = get_the_category( $post->ID )[0];
        $slug = $cat->slug;
        $content = self::getTheContent( $post, '[...]', true );
        $html .=
		        '<h2><span class="color' . $slug . '">' . $post->post_title . '</span></h2>' .
            '</header>' .
            '<p>' . $content . '</p>' .
            '<p><span class="articledate">' . the_time( 'd.m.Y' ) . '</span> <a href="' . get_permalink( $post ) . '" class="button">Read More </a></p>' .
            '<hr>' .
            '</section>';
        return $html;
    }

    public static function displaySinglePosts()
    {
        global $post;
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                $cat = get_the_category( $post->ID )[0];
                $slug = $cat->slug;
                ?>
                <section>
                    <header>
                        <?php 
                            if (has_post_thumbnail( $post )) {
                                echo get_the_post_thumbnail( $post );
                            }
                        ?>
                        <h2><?= the_title() ?></h2>
                    </header>
                    <h3 class="color<?= $slug ?>"><?= the_excerpt() ?></h3>
                    <p><strong>Proin sed ipsum euismod, gravida metus vitae, ullamcorper ligula. Sed commodo sem sed ante venenatis interdum. </strong> </p>
                    <p><?= the_content() ?></p>
                    <p><span class="articledate"><?php the_time( 'd.m.Y' ) ?></span> <a href="<?= get_category_link( $cat->cat_ID ) ?>" class="button color<?= $slug ?>">More in <?= $cat->name ?></a></p>
                </section>
            <?php
            }
        }
    }

}
?>
