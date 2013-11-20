<?

require_once dirname(__FILE__) . '/../lib/redis.php';
require_once dirname(__FILE__) . '/test.lib.php';

# Config
$r = new php_redis( '127.0.0.1', 6379 );

# Get, set, exists, delete
$r->delete('test');

$v = $r->exists('test');
test::assert_false( $v, 'Scalar tests' );

$v = $r->get('test');
test::assert_null( $v );

$r->set('test', 1);
$v = $r->get('test');
test::assert_true( $v == 1 );

$r->set('test', array(2, 1));
$v = $r->get('test');
test::assert_true( $v == array(2, 1) );

$r->set('test', null);
$v = $r->get('test');
test::assert_null( $v );
$v = $r->exists('test');
test::assert_true( $v );

$r->set('test', true);
$v = $r->get('test');
test::assert_true( $v );

$r->set('test', false);
$v = $r->get('test');
test::assert_false( $v );

$r->delete('test');
$v = $r->get('test');
test::assert_null( $v );
$v = $r->exists('test');
test::assert_false( $v );

# Increment/decrement

$r->delete('test');
$r->set('test', 1);
$v = $r->inc('test');
test::assert_value( $v, 2, 'Increment/Decrement' );
$v = $r->inc('test', 10);
test::assert_value( $v, 12 );

$r->delete('test');
$v = $r->inc('test');
test::assert_value( $v, 1 );
$v = $r->inc('test');
test::assert_value( $v, 2 );

$r->delete('test');
$v = $r->dec('test');
test::assert_value( $v, -1 );
$v = $r->dec('test', 2);
test::assert_value( $v, -3 );
$v = $r->inc('test', 10);
$v = $r->dec('test', 2);
test::assert_value( $v, 5 );

# Lists
$r->delete('test');
$r->append('test', 'a');
$r->append('test', 'b');
$r->append('test', 'c');
$v = $r->get_list('test', 3);
test::assert_value( $v, array('a', 'b', 'c'), 'Lists' );

$r->append('test', 'd');
$v = $r->get_list('test', 4);
test::assert_value( $v, array('a', 'b', 'c', 'd') );
$v = $r->get_list('test', 5);
test::assert_value( $v, array('a', 'b', 'c', 'd') );
$v = $r->get_list('test', 2);
test::assert_value( $v, array('a', 'b') );
$v = $r->get_list('test', 2, 1);
test::assert_value( $v, array('b', 'c') );
$v = $r->get_list('test', 10, 1);
test::assert_value( $v, array('b', 'c', 'd') );

$v = $r->get_list_length('test');
test::assert_value( $v, 4 );

$r->delete('test1');
$v = $r->get_list_length('test1');
test::assert_value( $v, 0 );

$v = $r->append('test1', 1);
$v = $r->get_list_length('test1');
test::assert_value( $v, 1 );

test::summary();