<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 9:54 PM
 */

class Chat
{
	protected $redis;

	public $key_prefix;
	public $key_messages;

	public function __construct()
	{
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/rediska/Rediska.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/rediska/Rediska/Key/List.php';

		$options = array(
			'servers' => array(
				'server1' => array('host' => '127.0.0.1', 'port' => 6379)
			)
		);
		$this->redis = new Rediska($options);
		$this->key_prefix = "chat:messages";
		$this->key_messages = new Rediska_Key_List($this->key_prefix);

		$this->key_user_last_enter = new Rediska_Key_Hash("users:last_enter");
		$this->key_user_password = new Rediska_Key_List("users:password");
	}

	/**
	 * Get HTML messages list
	 * @return string
	 */
	public function get_list_html()
	{
		$html = "";
		$list = $this->get_list();
		for ($i = 0; $i < count($list); $i++) {
			$html .= '<li class="left clearfix">
							<div class="chat-body clearfix">
								<div class="header">
									<strong class="primary-font">' . $list[$i]['username'] . '</strong>
									<small class="pull-right text-muted">
										<span class="glyphicon glyphicon-time"></span>' . $list[$i]['time'] . '
									</small>
								</div>
								<p>' . urldecode($list[$i]['body']) . '</p>
							</div>
						</li>';
		}
		return $html;
	}

	/**
	 * Get messages array
	 * @param int $limit
	 * @return array
	 */
	public function get_list($limit = 50)
	{
		$list_length = $this->get_list_count();
		$offset = (($list_length - $limit) < 0) ? 0 : $list_length;
		$list = $this->key_messages->getValues($offset, $list_length);
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['time'] = $this->get_time_ago(date("Y-m-d H:i:s", $list[$i]['time']));
		}
		return $list;
	}

	/**
	 * Get messages count
	 * @return int
	 */
	public function get_list_count()
	{
		return $this->key_messages->getLength();
	}

	/**
	 * Insert new message
	 * @param $body
	 * @param $user
	 * @return bool
	 */
	public function insert_msg($body, $user)
	{
		$this->key_user_last_enter[$user] = strtotime(date("Y-m-d H:i:s"));

		return $this->key_messages->append(array(
			"username" => $user,
			"body" => $body,
			"time" => strtotime(date("Y-m-d H:i:s"))
		));
	}

	public function get_users_count()
	{
		return $this->key_user_password->count();
	}

	public function get_users_current()
	{
		$tenMinAgo = strtotime(date('Y-m-d H:i:s', strtotime("-10 min")));
		$now = strtotime(date("Y-m-d H:i:s"));

		$users = $this->key_user_last_enter->toArray();
		$curUsers = array();
		foreach ($users as $username => $time) {
			if (($time > $tenMinAgo) && ($time <= $now)) {
				$curUsers[] = $username;
			}
		}
		return $curUsers;
	}

	public function get_time_ago($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

}