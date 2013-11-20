<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 9:54 PM
 */

class Chat
{
	private $redis;

	public function __construct()
	{
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis.pool.php';
		require $_SERVER["DOCUMENT_ROOT"] . '/libs/php-redis/lib/redis_list.peer.php';

		redis_pool::add_servers(array('master' => array('127.0.0.1', 6379)));

		$this->redis = new php_redis();
	}

	public function get_list($limit = 50)
	{
		$offset = $this->get_list_count() - $limit;
		$list = $this->redis->get_list("chat", $limit, $offset);
		for ($i = 0; $i < count($list); $i++) {
			$list[$i]['time'] = $this->get_time_ago(date("Y-m-d H:i:s", $list[$i]['time']));
		}
		return $list;
	}

	public function get_list_count()
	{
		return $this->redis->get_list_length("chat");
	}

	public function insert_msg($body, $user)
	{
		return !$this->redis->append("chat", array("username" => $user, "body" => $body, "time" => strtotime(date("Y-m-d H:i:s"))));
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