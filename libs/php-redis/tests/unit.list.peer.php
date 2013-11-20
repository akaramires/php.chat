<?

require_once dirname(__FILE__) . '/../lib/redis.php';
require_once dirname(__FILE__) . '/../lib/redis.pool.php';
require_once dirname(__FILE__) . '/../lib/redis_list.peer.php';
require_once dirname(__FILE__) . '/test.lib.php';

# Config
redis_pool::add_servers( array('master' => array('127.0.0.1', 6379)) );
class mock_list_peer extends redis_list_peer
{}

# Tests

$p = new mock_list_peer();
$p->clear('list');
$p->insert('list', array('id' => 1, 'type' => 'image'));
$p->insert('list', array('id' => 2, 'type' => 'text'));
$p->insert('list', array('id' => 3, 'type' => 'video'));
$p->insert('list', array('id' => 4, 'type' => 'video'));

$v = $p->get_list('list');

test::assert_value(count($v), 4, 'Adding, reading');
test::assert_value($p->length('list'), 4);
test::assert_value($v[1]['id'], 2);
test::assert_value($v[3]['type'], 'video');

$v = $p->get_list('list', array(), 1);

test::assert_value(count($v), 1);
test::assert_value($v[0]['id'], 1);
test::assert_value($v[0]['type'], 'image');

$v = $p->get_list('list', array(), 1, 2);

test::assert_value(count($v), 1);
test::assert_value($v[0]['id'], 3);
test::assert_value($v[0]['type'], 'video');

$p->clear('list');
$p->insert('list', array('id' => 1, 'type' => 'image'), false);
$p->insert('list', array('id' => 2, 'type' => 'text'), false);
$p->insert('list', array('id' => 3, 'type' => 'video'), false);
$v = $p->get_list('list');
test::assert_value(count($v), 3);
test::assert_value($v[0]['id'], 3);
test::assert_value($v[0]['type'], 'video');
test::assert_value($v[1]['id'], 2);
test::assert_value($v[2]['id'], 1);

$p->clear('list');
$p->insert('list', array('id' => 1, 'type' => 'image'));
$p->insert('list', array('id' => 2, 'type' => 'text', 'section' => 'blogs'));
$p->insert('list', array('id' => 3, 'type' => 'video'));
$p->insert('list', array('id' => 4, 'type' => 'video'));
$p->insert('list', array('id' => 5, 'type' => 'image'));
$p->insert('list', array('id' => 6, 'type' => 'text', 'section' => 'jokes'));
$p->insert('list', array('id' => 7, 'type' => 'text', 'section' => 'blogs'));

$v = $p->get_list('list', array('type' => 'image'));

test::assert_value($p->length('list'), 7);
test::assert_value(count($v), 2, 'Filtering');
test::assert_value($v[0]['id'], 1);
test::assert_value($v[0]['type'], 'image');
test::assert_value($v[1]['id'], 5);
test::assert_value($v[1]['type'], 'image');

$v = $p->get_list('list', array('type' => 'audio'));
test::assert_value(count($v), 0);

$v = $p->get_list('list', array('type' => 'text'));
test::assert_value(count($v), 3);

$v = $p->get_list('list', array('type' => 'text'), 1);
test::assert_value(count($v), 1	);
test::assert_value($v[0]['id'], 2);

$v = $p->get_list('list', array('type' => 'text'), 2, 1);
test::assert_value(count($v), 2	);
test::assert_value($v[0]['id'], 6);
test::assert_value($v[1]['id'], 7);

$v = $p->get_list('list', array('type' => 'text', 'section' => 'blogs'));
test::assert_value(count($v), 2	);
test::assert_value($v[0]['id'], 2);
test::assert_value($v[1]['id'], 7);

$v = $p->get_list('list', array('type' => 'text', 'section' => 'songs'));
test::assert_value(count($v), 0	);

$p->delete('list', array('section' => 'blogs'));
$v = $p->get_list('list');
test::assert_value(count($v), 5, 'Deleting' );
$v = $p->get_list('list', array('section' => 'blogs'));
test::assert_value(count($v), 0	);

$p->delete('list', array('type' => 'text'));
$v = $p->get_list('list');
test::assert_value(count($v), 4	);
$v = $p->get_list('list', array('type' => 'text'));
test::assert_value(count($v), 0	);

$p->clear('list');
$p->insert('list', array('id' => 1, 'type' => 'image'));
$p->insert('list', array('id' => 2, 'type' => 'text', 'section' => 'blogs'));
$p->insert('list', array('id' => 3, 'type' => 'video'));
$p->insert('list', array('id' => 4, 'type' => 'video'));
$p->insert('list', array('id' => 5, 'type' => 'image'));
$p->insert('list', array('id' => 6, 'type' => 'text', 'section' => 'jokes'));
$p->insert('list', array('id' => 7, 'type' => 'text', 'section' => 'blogs'));
$v = $p->get_list('list');
test::assert_value(count($v), 7, 'Truncating' );

$p->truncate('list', 5, 1);
$v = $p->get_list('list');
test::assert_value(count($v), 5 );
test::assert_value($v[0]['id'], 2 );
test::assert_value($v[4]['id'], 6 );

test::summary();