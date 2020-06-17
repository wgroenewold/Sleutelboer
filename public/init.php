<?php

require_once('sleutelboer.class.php');

$instance = sleutelboer::instance();

$users = $instance->helpers->list_users();

foreach($users as $user){
	if(empty($instance->db->read('users', 'user_id', ["user_id" => $user['user_id']]))){
		$instance->db->create('users', $user);
	}else{
		$instance->db->update('users', $user, ["user_id" => $user['user_id']]);
	}
}

$channels = $instance->helpers->list_channels();

foreach($channels as $channel){
	$instance->db->update('users', ['channel_id' => $channel['id']], ["user_id" => $channel['user']]);
}