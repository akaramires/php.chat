<?

require_once 'common.php';

class mock_list_peer extends redis_list_peer
{}

# Benchmark

$p = new mock_list_peer();

bench_mark::start('Adding 10000 items to list');

$p->clear('list');
for ( $i = 0; $i < 10000; $i ++ )
{
	$p->insert('list', array('value' => md5($i), 'type' => rand(0, 1)));
}

bench_mark::stop();

bench_mark::start('Simple list retrievement 10000 times');

for ( $i = 0; $i < 10000; $i ++ )
{
	$p->get_list('list', null, rand(10, 20), rand(0, 500));
}

bench_mark::stop();