<?php
/**
 * RateKit Rating class
 *
 * Instantiated on a request to this page.
 * If the request is rating.php?item=XXX, queries the database for that item and returns the overall rating
 * If request is rating.php?item=XXX&rating=Z, checks the database to see if that IP has rated the item before
 * within the period set in THROTTLE_TIME. If it hasn't, inserts the rating in the database and returns the
 * new overall rating.
 */

include '../config.php';
include 'DB.class.php';

class Rating {

	private $db;
	private $id;
	private $item;
	private $rating;

	function __construct() {
		$this->db   = DB::get_instance();
		$this->item = filter_var( $_GET['item'], FILTER_SANITIZE_STRING );
		if ( isset( $_GET['rating'] ) ) {
			// User is submitting a rating
			$this->id = hash( 'md5', IP_ADDRESS . $this->item );
			$this->check_ratings_from_ip();
			$this->rating = filter_var( $_GET['rating'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			$this->rating = $this->round( $this->rating );
			$this->check_vars();
			$result = $this->db->insert_or_replace(
				array(
					'id'         => $this->id,
					'ip_address' => IP_ADDRESS,
					'item'       => $this->item,
					'rating'     => $this->rating
				)
			);
			if ( empty( $result ) || ! is_array( $result ) ) {
				$this->return_error( array(
					'type'    => 'database',
					'message' => 'Problem recording the rating for ' . $this->item
				) );
			}
			if ( $result[0] === true ) {
				// Success
				$result[2]['message'] = 'Rating updated.';
				if ( ! DEBUG ) {
					unset( $result[2]['id'] );
					unset( $result[2]['ip_address'] );
				}
				$this->return_success( $result[2] );
			} else {
				// Error
				$this->return_error( array(
					'type'    => $result[1],
					'message' => $result[2]
				) );
			}
		} else if ( ! empty( $this->item ) ) {
			// Request for a rating
			$this->check_vars( false );
			$count_result         = array();
			$count_result['item'] = $this->item;
			$this->return_success( $count_result );
		}
	}

	// Check if this IP address has tried to rate this item before
	private function check_ratings_from_ip() {
		if ( THROTTLE_TIME === 0 ) {
			return;
		}
		$result = $this->db->select_recent_rating( $this->id, round( THROTTLE_TIME ) );
		if ( isset( $result['rating'] ) ) {
			$this->return_error( array(
				'message' => 'You have already rated this.',
				'rating'  => $result['rating']
			) );
			exit();
		}
	}

	// Rough validation of input
	private function check_vars( $new_rating = true ) {
		$error         = false;
		$error_message = array();

		if ( $this->item === false ) {
			$error           = true;
			$error_message[] = 'The ID for the item being rated was invalid.';
		} else if ( empty( $this->item ) ) {
			$error           = true;
			$error_message[] = 'There was no ID for the item being rated.';
		}

		if ( strlen( $this->item ) > 1024 ) {
			$error           = true;
			$error_message[] = 'The item ID was too long - it can\'t be longer than 1024 characters.';
		}

		if ( $new_rating ) {
			if ( $this->rating < 0 ) {
				$this->rating = 0;
			}

			if ( $this->rating > MAX_RATING ) {
				$this->rating = MAX_RATING;
			}

			if ( (int) $this->rating !== 0 && empty( $this->rating ) ) {
				$error           = true;
				$error_message[] = 'The rating was not valid.';
			}

			if ( strlen( $this->rating ) > 1024 ) {
				$error           = true;
				$error_message[] = 'The rating was too big a number - it can\'t be longer than 1024 characters.';
			}
		}

		if ( $error ) {
			$this->return_error( array(
				'type'    => 'input',
				'message' => implode( ' ', $error_message )
			) );
		}
	}

	// Returns a rounded rating score, either to nearest integer or nearest .5
	private function round( $rating ) {
		if ( ALLOW_HALVES ) {
			// Round to nearest .5
			return round( $rating * 2 ) / 2;
		} else {
			// Round to nearest integer
			return round( $rating );
		}
	}

	// Gets the overall rating and rating count for an item
	private function get_rating() {
		$result = $this->db->select_count_rating( $this->item );
		if ( $result[2]['count'] == 0 ) {
			return array( 'overall_rating' => 0, 'count' => 0 );
		}

		$rating = $this->round( $result[2]['total'] / $result[2]['count'] );

		return array( 'overall_rating' => $rating, 'count' => $result[2]['count'] );
	}

	public function return_json( $arr ) {
		header( 'Content-type: application/json' );
		$arr = array_map( 'htmlentities', $arr );
		echo json_encode( $arr );
	}

	public function return_error( $arr ) {
		$arr['status'] = 'error';
		$this->return_json( $arr );
		exit();
	}

	public function return_success( $arr ) {
		$arr['status']         = 'success';
		$rating_data           = $this->get_rating();
		$arr['overall_rating'] = $rating_data['overall_rating'];
		$arr['count']          = $rating_data['count'];
		$this->return_json( $arr );
		exit();
	}
}

$rating = new Rating();
