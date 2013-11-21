<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 9:54 PM
 */

class Chat
{
	private $redis;
	private $_prefix;

	public function __construct()
	{
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.pool.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis_list.peer.php';

		redis_pool::add_servers(array('master' => array('127.0.0.1', 6379)));

		$this->redis = new php_redis();
		$this->_prefix = "test_";
	}

	public function get_list($limit = 50)
	{
		$offset = $this->get_list_count() - $limit;
		$list = $this->redis->get_list($this->_prefix . "chat", $limit, $offset);
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['time'] = $this->get_time_ago(date("Y-m-d H:i:s", $list[$i]['time']));
		}
		return $list;
	}

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

	public function get_list_count()
	{
		return $this->redis->get_list_length($this->_prefix . "chat");
	}

	public function insert_msg($body, $user)
	{
		return !$this->redis->append($this->_prefix . "chat", array("username" => $user, "body" => $body, "time" => strtotime(date("Y-m-d H:i:s"))));
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

	public function get_users_count()
	{
		return $this->redis->get_list_length($this->_prefix . "users");
	}

	public function get_users_current()
	{
		$tenMinAgo = strtotime(date('Y-m-d H:i:s', strtotime("-10 min")));
		$now = strtotime(date("Y-m-d H:i:s"));

		$users = $this->redis->get_list($this->_prefix . "users", $this->get_users_count());
		$curUsers = array();
		for ($i = 0; $i < count($users); $i++) {
			if (($users[$i]['last_log'] >= $tenMinAgo) && ($users[$i]['last_log'] < $now)) {
				$curUsers[] = $users[$i]['username'];
			}
		}
		return $curUsers;
	}

}