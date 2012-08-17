<?php

class Config{
	static $confArray;

	public static function read($name)
	{
		return self::$confArray[$name];
	}

	public static function write($name, $value)
	{
		self::$confArray[$name] = $value;
	}
}

Config::write('db.host', 'localhost');
Config::write('db.basename', 'sweetfeedback');
Config::write('db.user', 'root');
Config::write('db.password', '29418401');

?>
