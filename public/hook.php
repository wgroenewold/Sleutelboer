<?php

require_once('sleutelboer.class.php');
$instance = sleutelboer::instance();

$input = $instance->api->receive();
$data = json_decode($input, true);

$message = $instance->keyhub->format_message($data);

if($message){
	$block = file_get_contents('msg.json');
	$msg = str_replace('{TEXT}', $message['text'], $block);

	switch(true){
		case ($message['type'] == 'ACCOUNT_CREATED'):
			$channels = $instance->db->read('users', 'channel_id', ["channel_id[!]" => null]);

			foreach($channels as $channel){
				$instance->helpers->create_msg('Hallo!', $msg, $channel);
			}

			break;
		case strpos($message['type'], 'REQUESTED'):
			$admins = $_ENV['KEYHUB_ADMINS'];

			if($admins){
				$admins = explode(',', $admins);

				foreach($admins as &$admin){
					$admin =  $admin . '@' . $_ENV['ORGANISATION_DOMAIN'];
				}
				unset($admin);

				$channels = $instance->db->read('users', 'channel_id', ["AND" => ["channel_id[!]" => null, 'email' => $admins]]);
			}

			break;
		case strpos($message['type'], 'DECLINED'):
		case strpos($message['type'], 'ACCEPTED'):
			$channels[] = $message['user_id'];

			break;
	}

	foreach($channels as $channel){
		$instance->helpers->create_msg($_ENV['BALLOON_TXT'], $msg, $channel);
	}
}