<?php
class CBMTheme {

    public static function displayCategory($cat) {
        return
        '<div class="3u">' .
			'<section>' .
				'<header class="active">' .
					'<h2 id="' . $cat->slug . '" class="' . $cat->slug . '"><a href="' . get_category_link( $cat->cat_ID ) . '">' . $cat->name . '</a></h2>' .
				'</header>' .
			'</section>' .
		'</div>';
    }

    public static function categoriesNavigation() {
        $html = '';
        $categories = get_categories( );
        foreach( $categories as $category ) {
            if ($category->slug != 'uncategorized') {
                $html .= self::displayCategory($category);
            }
        }
        return $html;
    }

}
?>
