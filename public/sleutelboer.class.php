<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

require_once('app/api.php');
require_once('app/slack.php');
require_once('app/helpers.php');
require_once('app/db.php');
require_once('app/keyhub.php');

setlocale(LC_ALL, 'nl_NL');

sleutelboer::instance();

/**
 * Class sleutelboer
 */
class sleutelboer
{
	/**
	 * Singleton holder
	 */
	private static $instance;

	/**
	 * Get the singleton
	 *
	 * @return sleutelboer
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	private function __construct()
	{
		$this->api = new sleutelboer_api();
		$this->slack = new sleutelboer_slack();
		$this->helpers = new sleutelboer_helpers();
		$this->db = new sleutelboer_db();
		$this->keyhub = new sleutelboer_keyhub();
	}
}