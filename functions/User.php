<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/18/13
 * Time: 9:17 PM
 */

class User
{
	protected $username;
	protected $password;

	protected $redis;
	private $_prefix;

	public function __construct($username, $password)
	{
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.pool.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis_list.peer.php';

		redis_pool::add_servers(array('master' => array('127.0.0.1', 6379)));

		$this->redis = new php_redis();
		$this->_prefix = "test_";
		$this->username = mysql_real_escape_string($username);
		$this->password = mysql_real_escape_string($password);
	}

	public function login()
	{
		$username = $this->_check();
		if ($username) {
			$this->redis->remove_by_filter($this->_prefix . "users", array("username" => $this->username));
			$this->redis->append($this->_prefix . "users", array("username" => $this->username, "password" => md5($this->password), "last_log" => strtotime(date("Y-m-d H:i:s"))));
			$this->username = $username;
			return true;
		}
		return false;
	}

	public function register()
	{

		if (!$this->login()) {
			$this->redis->append($this->_prefix . "users", array("username" => $this->username, "password" => md5($this->password), "last_log" => strtotime(date("Y-m-d H:i:s"))));
			return true;
		}
		return false;
	}

	protected function _check()
	{
		$response = $this->redis->get_filtered_list($this->_prefix . "users", array("username" => $this->username));
		if (count($response) > 0) {
			$submitted_pass = md5($this->password);
			if ($submitted_pass == $response[0]['password']) {
				return $response[0]['username'];
			}
		}
		return false;
	}

}
