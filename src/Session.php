<?php
	
	namespace Pavl\Short_Link_Generator;
	
	class Session {
		public static string $key_last_time_click = 'last_click_time';
		
		/**
		 * Starts the session if not already started
		 *
		 * @return void
		 */
		public function start_session() {
			if ( ! session_id() ) {
				session_start();
			}
		}
		
		/**
		 * Retrieves the last click time for a given post ID from the session
		 *
		 * @param int $post_id
		 *
		 * @return int
		 */
		public function get_last_time_click( int $post_id ): int {
			$session_last_click_time = 0;
			if ( isset( $_SESSION[ $this::$key_last_time_click ][ $post_id ] ) ) {
				$session_last_click_time = (int) $_SESSION[ $this::$key_last_time_click ][ $post_id ];
			}
			
			return $session_last_click_time;
		}
		
		/**
		 * Saves the last click time for a given post ID into the session
		 *
		 * @param int $post_id
		 * @param int $time
		 *
		 * @return void
		 */
		public function save_last_time_click( int $post_id, int $time ): void {
			if ( ! isset( $_SESSION[ $this::$key_last_time_click ] ) ) {
				$_SESSION[ $this::$key_last_time_click ] = array();
			}
			$_SESSION[ $this::$key_last_time_click ][ $post_id ] = $time;
		}
	}
