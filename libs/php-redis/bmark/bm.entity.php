<?

require_once 'common.php';

class mock_peer extends redis_peer
{}

# Benchmark

$p = new mock_peer();

bench_mark::start('Setting 10000 entities');

for ( $i = 0; $i < 10000; $i ++ )
{
	$p->insert(array('data' => md5($i)));
}

bench_mark::stop();

bench_mark::start('Reading 10000 entities');

for ( $i = 0; $i < 10000; $i ++ )
{
	$p->get_by_id($i+1);
}

bench_mark::stop();

bench_mark::start('Deleting 10000 entities');

for ( $i = 0; $i < 10000; $i ++ )
{
	$p->delete($i + 1);
}

bench_mark::stop();