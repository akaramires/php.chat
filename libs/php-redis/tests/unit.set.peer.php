<?

require_once dirname(__FILE__) . '/../lib/redis.php';
require_once dirname(__FILE__) . '/../lib/redis.pool.php';
require_once dirname(__FILE__) . '/../lib/redis_set.peer.php';
require_once dirname(__FILE__) . '/test.lib.php';

# Config
redis_pool::add_servers( array('master' => array('127.0.0.1', 6379)) );
class mock_set_peer extends redis_set_peer
{}

# Tests

$p = new mock_set_peer();

$p->clear('some_set');
$p->add('some_set', 1);
$p->add('some_set', 2);
test::assert_true($p->is_member('some_set', 1), 'Adding/removing');
test::assert_true($p->is_member('some_set', 2));
test::assert_false($p->is_member('some_set', 3));
test::assert_value($p->get_count('some_set'), 2);

$set = $p->get_all('some_set');
test::assert_value(count($set), 2);
test::assert_true(in_array(2, $set));
test::assert_true(in_array(1, $set));

$p->remove('some_set', 2);
test::assert_value($p->get_count('some_set'), 1);
test::assert_false($p->is_member('some_set', 2));
test::assert_true($p->is_member('some_set', 1));

$r = $p->add('some_set', 3);
test::assert_true($r);
$r = $p->add('some_set', 3);
test::assert_false($r);

test::summary();