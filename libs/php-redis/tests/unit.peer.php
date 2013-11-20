<?

require_once dirname(__FILE__) . '/../lib/redis.php';
require_once dirname(__FILE__) . '/../lib/redis.pool.php';
require_once dirname(__FILE__) . '/../lib/redis.peer.php';
require_once dirname(__FILE__) . '/test.lib.php';

# Config
redis_pool::add_servers( array('master' => array('127.0.0.1', 6379)) );
class mock_peer extends redis_peer
{}

# Tests

$ts = time();
$data = array('name' => 'Test', 'time' => $ts);
$p = new mock_peer();
$id = $p->insert($data);
test::assert_true( $id > 0, 'Insert tests' );

$v = $p->get_by_id($id);
test::assert_value($v['id'], $id);
test::assert_value($v['time'], $ts);
test::assert_value($v['name'], 'Test');
test::assert_value(count($v), 3);

$id1 = $p->insert($data);
$id2 = $p->insert($data);
$id3 = $p->insert($data);

test::assert_value($id1, $id2 -1);
test::assert_value($id1, $id3 -2);

$id = $p->insert($data);
$p->delete($id);
$v = $p->get_by_id($id);
test::assert_null( $v, 'Delete tests' );

$id = $p->insert($data);
$p->update($id, array('name' => 'Test updated'));
$v = $p->get_by_id($id);
test::assert_value($v['name'], 'Test updated', 'Update tests' );
test::assert_value($v['id'], $id );
test::assert_value($v['time'], $ts );
test::assert_value(count($v), 3 );

$p->update($id, array('country' => 'Ukraine'));
$v = $p->get_by_id($id);
test::assert_value($v['country'], 'Ukraine' );
test::assert_value(count($v), 4 );

test::summary();