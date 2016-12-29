<?php
class CBMTheme {

    public static function displayCategory($cat)
    {
        return
        '<div class="3u">' .
			'<section>' .
				'<header class="active">' .
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

        foreach ( $categories as $category ) {
            if ($category->slug == 'uncategorized') {
                continue;
            }
            // TODO : Ignore already loaded posts!
            $args = array(
                'cat' => $category->term_id,
                'post_type' => 'post',
                'posts_per_page' => '1',
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    array_push($last_posts, array("category" => $category->name, "post_id" => $post->ID, "date" => $post->post_date, "post" => $post));
                } // end while
            } // end if

            // Use reset to restore original query.
            wp_reset_postdata();
        }
        usort($last_posts, "CBMTheme::compareByDate");
        return $last_posts;
    }

    static public function sidebarPost( $item ) {
        $post = $item['post'];
        setup_postdata( $post );
        $text = get_the_excerpt();
        $html =
            '<ul class="style2">' .
                '<p class="finance">' . $item['category'] . '</p>' .
                '<h3>' . $post->post_title . '</h3>' .
                '<p>' . $text . '</p>' .
                '<p><span class="articledate">' . $item['date'] . '</span> <a href="' . get_permalink( $post ) . '" class="button">Read More </a></p>' .
            '</ul>';
        wp_reset_postdata();
        return $html;
    }

}
?>
