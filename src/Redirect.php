<?php
	
	namespace Pavl\Short_Link_Generator;
	
	use Pavl\Short_Link_Generator;
	
	/**
	 * Class for redirecting short links
	 */
	class Redirect {
		
		/**
		 * Registers redirection action
		 *
		 * @return void
		 */
		public function register(): void {
			add_action( 'template_redirect', [ $this, 'handle_redirect' ] );
		}
		
		/**
		 * Handles the redirection process
		 *
		 * @return void
		 */
		public function handle_redirect(): void {
			if ( ! is_singular( Post\Type\Short_Link::$slug ) ) {
				return;
			}
			$post_id  = get_queried_object_id();
			$redirect = Post\Type\Short_Link::get_redirect( $post_id );
			if ( empty( $redirect ) ) {
				$this->redirect_to_404();
				
				return;
			}
			Post\Type\Short_Link::add_click( $post_id );
			$this->check_unique_click( $post_id );
			wp_redirect( $redirect );
		}
		
		/**
		 * Redirects to a 404 page
		 *
		 * @return void
		 */
		public function redirect_to_404(): void {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			get_template_part( 404 );
		}
		
		/**
		 * Checks for unique clicks and adds them
		 *
		 * @param int $post_id post id
		 *
		 * @return void
		 */
		private function check_unique_click( int $post_id ): void {
			$session = new Session();
			$session->start_session();
			$session_last_click_time = $session->get_last_time_click( $post_id );
			if ( time() - $session_last_click_time > Config::SECONDS_UNIQUE_CLICK ) {
				$session->save_last_time_click( $post_id, time() );
				Post\Type\Short_Link::add_unique_click( $post_id );
			}
		}
	}
