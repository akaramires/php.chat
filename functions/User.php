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

	public $key_last_enter;
	public $key_password;

	public function __construct($username, $password)
	{
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/rediska/Rediska.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/rediska/Rediska/Key/Hash.php';

		$options = array(
			'servers' => array(
				'server1' => array('host' => '127.0.0.1', 'port' => 6379)
			)
		);
		$this->redis = new Rediska($options);

		$this->username = mysql_real_escape_string($username);
		$this->password = mysql_real_escape_string($password);

		$this->key_password = new Rediska_Key_Hash("users:password");
		$this->key_last_enter = new Rediska_Key_Hash("users:last_enter");
	}

	/**
	 * User login
	 * @return bool
	 */
	public function login()
	{
		if ($this->_check()) {
			$this->key_last_enter[$this->username] = strtotime(date("Y-m-d H:i:s"));
			return true;
		}
		return false;
	}

	/**
	 * User registration
	 * @return bool
	 */
	public function register()
	{
		if (!$this->login()) {
			$this->key_password[$this->username] = md5($this->password);
			$this->key_last_enter[$this->username] = strtotime(date("Y-m-d H:i:s"));
			return true;
		}
		return false;
	}

	/**
	 * User verification
	 * @return bool
	 */
	protected function _check()
	{
		if ($this->key_password->get($this->username) && (md5($this->password) == $this->key_password->get($this->username)))
			return true;
		return false;
	}

}
