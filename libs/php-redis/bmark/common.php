<?
require_once dirname(__FILE__) . '/../lib/redis.php';
require_once dirname(__FILE__) . '/../lib/redis.pool.php';
require_once dirname(__FILE__) . '/../lib/redis.peer.php';
require_once dirname(__FILE__) . '/../lib/redis_list.peer.php';
require_once dirname(__FILE__) . '/../lib/redis_set.peer.php';
require_once dirname(__FILE__) . '/bench_mark.lib.php';

# Config
redis_pool::add_servers( array('master' => array('127.0.0.1', 6379)) );