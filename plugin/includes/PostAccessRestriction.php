<?php

class PostAccessRestriction {

	const session_key = 'post_access';

	const max_articles_anon = 2;
	
	const not_allowed_anon = "Verboten!";
	
	const max_articles_user_per_day = 4;
	
	const clean_after_inserts = 100;
	
	private static $count_down = self::clean_after_inserts;
	
	private static function table_name() {
		global $wpdb;
		return $wpdb->prefix . "post_access_counter";
	}
	
	public static function install_db () {
		global $wpdb;
	
		$table_name = self::table_name();

		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE $table_name (
id mediumint(9) NOT NULL AUTO_INCREMENT,
time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
user_id bigint(20) unsigned NOT NULL,
post_id bigint(20) unsigned NOT NULL,
PRIMARY KEY  (id)
) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public static function start_session() {
		if(!session_id()) {
			session_start();
		}
	}

	public static function end_session() {
		session_destroy ();
	}
	
	private static function cleanup_non_anon_access() {
		global $wpdb;
		$table_name = self::table_name();
		$wpdb->query(
			$wpdb->prepare(
				"
				DELETE FROM $table_name
				WHERE time>= now() - interval 1 day
				"
			)
		);
	}
	
	private static function register_non_anon_access( $user_id, $post_id ) {
		global $wpdb;
		$wpdb->insert(
			self::table_name(),
			array(
				'time' => current_time( 'mysql' ),
				'user_id' => $user_id,
				'post_id' => $post_id
			),
			array( '%s', '%d', '%d' )
		);
		if (--self::$count_down <= 0) {
			self::cleanup_non_anon_access();
			self::$count_down = self::clean_after_inserts;
		}
	}
	
	private static function count_user_per_day( $user_id ) {
		global $wpdb;
		$table_name = self::table_name();
		$q = $wpdb->prepare(
			"
			SELECT count(distinct post_id)
			FROM $table_name
			WHERE user_id = %d and time>= now() - interval 1 day
			",
			$user_id
		);
		return $wpdb->get_var($q);
	}

	public static function page_restrict($pr_page_content) {
		if (is_single ()) {
			global $post;
			$user_id = get_current_user_id ();
			if (! $user_id) {
				if (isset ( $_SESSION [self::session_key] )) {
					$value = $_SESSION [self::session_key];
				} else {
					$value = array ();
				}
				if (! in_array ( $post->ID, $value )) {
					if (count ( $value ) >= self::max_articles_anon) {
						return self::not_allowed_anon;
					}
					$value [] = $post->ID;
					$_SESSION [self::session_key] = $value;
				}
			} else {
				self::register_non_anon_access($user_id, $post->ID);
				$count = self::count_user_per_day($user_id);
			}
		}
		return $pr_page_content;
	}

}

?>
