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
    {
        return
        '<div class="3u">' .
			'<section>' .
				// '<header' .' class="active"' . '>' .
				'<header>' .
					'<h2 id="' . $cat->slug . '" class="' . $cat->slug . '"><a href="' . get_category_link( $cat->cat_ID ) . '">' . $cat->name . '</a></h2>' .
				'</header>' .
			'</section>' .
		'</div>';
    }

    public static function categoriesNavigation()
    {
        $html = '';
        $categories = get_categories( );
        foreach( $categories as $category ) {
            if ($category->slug != 'uncategorized') {
                $html .= self::displayCategory($category);
            }
        }
        return $html;
    }

    private static function compareByDate($a, $b)
    {
        return strcmp( $b["date"], $a["date"] );
    }


    public static function getLastPosts($value='')
    {
        $last_posts = array();
        $categories = get_categories(); 
        global $post;
        $p = array();

        foreach ( $categories as $category ) {
            if ($category->slug == 'uncategorized') {
                continue;
            }
            // TODO : Ignore already loaded posts!
            $args = array(
                'cat' => $category->term_id,
                'post_type' => 'post',
                'post__not_in' => $p,
                'posts_per_page' => '1',
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    array_push($last_posts, array(
                        "category" => $category->name,
                        "slug" => $category->slug,
                        "post_id" => $post->ID,
                        "date" => $post->post_date,
                        "post" => $post
                    ));
                    array_push( $p, $post->ID );
                } // end while
            } // end if

            // Use reset to restore original query.
            wp_reset_postdata();
        }
        usort($last_posts, "CBMTheme::compareByDate");
        return $last_posts;
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

    static public function sidebarPost( $item ) {
        $post = $item['post'];
        return
            '<ul class="style2">' .
                '<p class="' . $item['slug'] . '">' . $item['category'] . '</p>' .
                '<h3>' . $post->post_title . '</h3>' .
                '<p>' . self::getTheExcerpt( $post ) . '</p>' .
                '<p><span class="articledate">' . self::formatDate( $item['date'], 'd.m.Y' ) . '</span> <a href="' . get_permalink( $post ) . '" class="button">Read More </a></p>' .
            '</ul>';
    }

    public static function getLatestPosts( $n = 2 ) 
    {
        $args = array(
            'numberposts' => $n,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' =>'',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        );
        $recent_posts = wp_get_recent_posts( $args, OBJECT );
        return $recent_posts;
    }

    public static function displayLatestPost( $post )
    {
        $slug = get_the_category( $post->ID )[0]->slug;
        return
            '<ul class="style1">' .
                '<strong><span class="articledate">' . self::formatDate( $post->post_modified, 'd.m.Y H:i' ) . '</span>' .
                '</strong>' .
                '<h3 class="color' . $slug . '">' . $post->post_title . '</h3>' .
                '<p class="color' . $slug . '">' . self::getTheExcerpt( $post ) . '</p>' .
                '<p><span class="articledate">' . self::formatDate( $post->post_modified, 'd.m.Y' ) . '</span> <a href="' . get_permalink( $post ) . '" class="button">Read More </a></p>' .
            '</ul>';
    }

    public static function getTaggedPosts( $tags )
    {
        $args = array(
            'numberposts' => 1,
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

    public static function displayMiddlePost( $post )
    {
        $html = '<section><header>';
        if (has_post_thumbnail( $post )) {
            $html .= get_the_post_thumbnail( $post );
        }
        $cat = get_the_category( $post->ID )[0];
        $slug = $cat->slug;
        $content = self::getTheContent( $post, '[...]', true );
        $html .=
                    '<span class="' . $slug . '">' . $cat->name . '</span>' .
                    '<h2>' . $post->post_title . '</h2>' .
                '</header>' .
                '<p>' . $content . '</p>' .
                '<p><span class="articledate">12.02.2016</span> <a href="#" class="button">Read More </a></p>' .
            '</section>';
        return $html;
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
            '<p><span class="articledate">12.02.2016</span> <a href="banking-single.php" class="button">Read More </a></p>' .
            '<hr>' .
            '</section>';
        return $html;
    }

}
?>
