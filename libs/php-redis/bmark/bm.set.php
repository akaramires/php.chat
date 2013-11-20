<?

require_once 'common.php';

class mock_set_peer extends redis_set_peer
{}

# Benchmark

$p = new mock_set_peer();

bench_mark::start('Setting 10000 members');

$p->clear('bm_set');
for ( $i = 0; $i < 10000; $i ++ )
{
	$p->add('bm_set', md5($i));
}

bench_mark::stop();


bench_mark::start('Checking random member 10000 times');

for ( $i = 0; $i < 10000; $i ++ )
{
	$p->is_member('bm_set', md5($i));
}

bench_mark::stop();


bench_mark::start('Removing 10000 members');

$p->clear('bm_set');
for ( $i = 0; $i < 10000; $i ++ )
{
	$p->remove('bm_set', md5($i));
}

bench_mark::stop();