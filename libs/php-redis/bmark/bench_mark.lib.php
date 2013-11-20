<?

class bench_mark
{
	private static $checkpoint;

	public static function start( $note = '' )
	{
		self::$checkpoint = microtime(true);

		if ( $note )
		{
			echo $note . '...';
		}
	}

	public static function stop()
	{
		$spent = microtime(true) - self::$checkpoint;
		echo ' ' . number_format($spent, 2) . 's' . "\n";
	}
}