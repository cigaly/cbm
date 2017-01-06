<?php

class PostAccessRestriction {

	const session_key = 'post_access';

	const max_articles_anon = 2;
	
	const not_allowed_anon = "Verboten!";

	public static function start_session() {
		if(!session_id()) {
			session_start();
		}
	}

	public static function end_session() {
		session_destroy ();
	}

	public static function page_restrict($pr_page_content) {
		if (is_single ()) {
			$user = get_current_user_id ();
			if (! $user) {
				global $post;
				if (isset ( $_SESSION [self::session_key] )) {
					$value = $_SESSION [self::session_key];
				} else {
					$value = array ();
				}
				if (! in_array ( $post->ID, $value )) {
					$value [] = $post->ID;
					$_SESSION [self::session_key] = $value;
					if (count ( $value ) > self::max_articles_anon) {
						return self::not_allowed_anon;
					}
				}
			}
		}
		return $pr_page_content;
	}

}

?>
