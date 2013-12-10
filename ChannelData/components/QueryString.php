<?php namespace ChannelData\Component;

// namespace ChannelData;

class QueryString {
	
	protected $str;

	public function __construct($str)
	{
		$this->str = $str;
	}

	public function __toString()
	{
		return $this->str;
	}

	public static function protect($str, $table = FALSE)
	{
		if(!is_object($str))
		{
			$parts = explode('.', $str);

			foreach($parts as $index => $part)
			{
				$part = trim(self::strip($part));

				if(!preg_match('/\*/', $part))
				{
					$part = '`'.$part.'`';
				}
				
				$parts[$index] = $part;
			}

			if(count($parts) > 1)
			{
				$parts[0] = self::table($parts[0]);
			}
			else if($table && count($parts))
			{
				array_unshift($parts, self::table($table));
			}

			return implode('.', $parts);
		}

		return (string) $str;
	}

	public static function raw($str)
	{
		return new QueryString($str);
	}

	public static function escape($str)
	{
		if(!is_object($str))
		{
			return ee()->db->escape($str);
		}

		return (string) $str;
	}

	public static function operator($str)
	{
		if(!is_object($str))
		{
			return $str;
		}
		
		return (string) $str;
	}

	public static function table($str)
	{
		if($str)
		{
			$str = self::strip($str);

			if(!preg_match('/^exp_/', $str))
			{
				$str = ee()->db->dbprefix($str);
			}

			return self::protect($str);
		}

		return $str;
	}

	static function strip($str)
	{
		$str = preg_replace('/^\`|\`$/', '', $str);
		$str = preg_replace('/\{\d*\}/', '', $str);

		return $str;
	}

	public static function clean($sql)
	{
		if(is_array($sql))
		{
			$sql = implode(' ', $sql);
		}

		$sql = trim($sql);
		
		foreach(array('AND', 'OR') as $value)
		{
			$sql = ltrim($sql, $value);
		}
		
		return trim($sql);
	}
}