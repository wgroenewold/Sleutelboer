<?php


/**
 * Class keyhub. Do all Keyhub associated stuff.
 */

class sleutelboer_keyhub{
	private $db;

	public function __construct() {
		$this->db = new sleutelboer_db();
	}

	public function format_message($data) {
		if ( $_ENV[ $data['type'] ] ) {
			$name = $by = $param1 = $param2 = $group = '';

			$search = array(
				'{NAME}',
				'{BY}',
				'{GROUP}',
				'{PARAM1}',
				'{PARAM2}',
			);

			if ( isset( $data['account']['name'] ) && $data['account']['name'] ) {
				$name = $this->db->read( 'users', 'user_id', [ "email" => $data['account']['name'] . '@' . $_ENV['ORGANISATION_DOMAIN'] ] );
				$name = '<@' . $name . '>';
			}

			if ( isset( $data['byParty']['name'] ) && $data['byParty']['name'] ) {
				$by = $this->db->read( 'users', 'user_id', [ "email" => $data['byParty']['name'] . '@' . $_ENV['ORGANISATION_DOMAIN'] ] );
				$by = '<@' . $by . '>';
			}

			if ( isset( $data['group']['name'] ) && $data['group']['name'] ) {
				$group = $data['group']['name'];
			}

			if ( isset( $data['parameter1'] ) && $data['parameter1'] ) {
				$param1 = $data['parameter1'];
			}

			if ( isset( $data['parameter2'] ) && $data['parameter2'] ) {
				$param2 = $data['parameter2'];
			}

			$replace = array(
				$name,
				$by,
				$group,
				$param1,
				$param2,
			);

			$text = str_replace( $search, $replace, $_ENV[ $data['type'] ] );

			return array(
				'type' => $data['type'],
				'text' => $text,
				'user_id' => substr($name, 2, (strlen($name) - 3)),
			);
		}

		return false;
	}
}