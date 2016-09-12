<?php namespace testingGround;

require __DIR__ . '/../vendor/autoload.php';


/*
 * Array Test Should Work
 */
$array = [
	'this'           => 'that',
	'number'         => 123,
	'doubleQuote'    => 'I have a "double quote"',
	'singleQuote'    => 'I have a two \'single quotes\'',
	'quoteAndSemi'   => '";',
	'simpleArray'    => [
		'inside',
		'value',
	],
	'multiArray'     => [
		'this'   => 'that',
		'number' => 456,
	],
	#'closeAbove'    => '}', TODO: a single closing brace as a string
	'badCharacters1' => '£ÉÁ',
	'badCharacters2' => 'Ørnevej 48',
	'arrayInArray'   => [
		'arrayOfNum' => [
			1,
			2,
			3,
		],
		'another'    => [
			'test' => '',
		],
	],
	'arrayLike'      => 'a:1:{"but isn\'t"}',
	'false'          => false,
	'true'           => true,
	'null'           => null,
];

$string = serialize($array);
$fixed  = \unserialize\fix($string);

echo ($array === $fixed) ? 'arrays match' : 'mismatch';
echo PHP_EOL;

die();

/*
 * Object Test Should Throw Error
 */

class foo {
	function bar()
	{
		echo 'This is an object';
	}
}

try
{
	$bar    = new foo();
	$object = serialize($bar);
	$refuse = \unserialize\fix($object);
}
catch (\Exception $e)
{
	echo 'As expected the object failed to unserialize';
}