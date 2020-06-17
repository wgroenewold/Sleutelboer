<?php


/**
 * Class slack_helpers. Common functionality abstracted from the Slack class.
 */

class sleutelboer_helpers{
	private $token;
	private $slack;
	private $api;

	public function __construct() {
		$this->token = $_ENV['SLACK_TOKEN'];
		$this->slack = new sleutelboer_slack();
		$this->api = new sleutelboer_api();
	}

	//"Validator" for Slack
	public function validator(){
		$input = file_get_contents('php://input');
		if($input){
			$decode = json_decode($input, true);
			if(isset($decode) && array_key_exists('challenge', $decode)) {
				var_dump($input);
			}
		}
	}

	public function list_channels(){
		$data = array();

		$args = array(
			'token' => $this->token,
			'exclude_archived' => true,
			'limit' => 1000,
			'types' => 'im',
			'cursor' => '',
		);

		$response = $this->slack->conversations_list($args);

		if($response && $response['ok'] == true){
			foreach($response['channels'] as $value){
				$data[] = $value;
			}

			unset($value);

			while(array_key_exists('response_metadata', $response) && $response['response_metadata']['next_cursor'] != false){
				$args['cursor'] = $response['response_metadata']['next_cursor'];

				$response = $this->slack->conversations_list($args);

				if($response && $response['ok'] == true){
					foreach($response['channels'] as $value){
						$data[] = $value;
					}

					unset($value);

					$args['cursor'] = $response['response_metadata']['next_cursor'];
				}
			}
		}

		return $data;
	}

	public function list_connected_users(){
		$data = array();

		$args = array(
			'token' => $this->token,
			'exclude_archived' => true,
			'limit' => 1000,
			'types' => 'im',
			'cursor' => '',
		);

		$response = $this->slack->conversations_list($args);

		if($response && $response['ok'] == true){
			foreach($response['channels'] as $value){
				$data[] = $value['user'];
			}

			unset($value);

			while(array_key_exists('response_metadata', $response) && $response['response_metadata']['next_cursor'] != false){
				$args['cursor'] = $response['response_metadata']['next_cursor'];

				$response = $this->slack->conversations_list($args);

				if($response && $response['ok'] == true){
					foreach($response['channels'] as $value){
						$data[] = $value['user'];
					}

					unset($value);

					$args['cursor'] = $response['response_metadata']['next_cursor'];
				}
			}
		}

		return $data;
	}

	public function create_msg($balloon_txt, $blocks, $channel){
		$blocks = json_decode($blocks, true);

		$data = array(
			'channel' => $channel,
			'text' => $balloon_txt,
			'blocks' => $blocks,
		);

		if($data){
			$msg = $this->slack->chat_postmessage($data);
			return $msg;
		}else{
			$this->api->log('Kon geen data maken, dus stuk.');
		}

		return null;
	}

	public function create_home($blocks, $user_id){
		$blocks = json_decode($blocks, true);

		$data = array(
			'user_id' => $user_id,
			'view' => array(
				'type' => 'home',
				'blocks' => $blocks,
			),
		);

		if($data){
			$msg = $this->slack->views_publish($data);
			return $msg;
		}else{
			$this->api->log('Kon geen data maken, dus stuk.');
		}

		return null;
	}

	public function list_users(){
		$data = array();

		$args = array(
			'token' => $this->token,
		);

		$response = $this->slack->users_list($args);

		if($response && $response['ok'] == true){
			foreach($response['members'] as $value){
				if($value['deleted'] !== true && $value['is_bot'] !== true){
					$data[] = array(
						'user_id' => $value['id'],
						'name' => $value['profile']['real_name'],
						'email' => $value['profile']['email'],
					);
				}
			}
		}

		return $data;
	}
}