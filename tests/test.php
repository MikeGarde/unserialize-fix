<?php

require __DIR__ . '/../vendor/autoload.php';

$array = [
	'this'          => 'that',
	'number'        => 123,
	'doubleQuote'   => 'I have a "double quote"',
	'singleQuote'   => 'I have a two \'single quotes\'',
	'quoteAndSemi'  => '";',
	'simpleArray'   => [
		'inside',
		'value',
	],
	'multiArray'    => [
		'this'   => 'that',
		'number' => 456,
	],
	'closeAbove'    => '}',
	'badCharacters' => '£ÉÁ',
	'arrayInArray'  => [
		'array'   => [
			1,
			2,
			3,
		],
		'another' => [
			'test' => '',
		],
	],
	'arrayLike'     => 'a:1:{"but isn\'t"}',
	'true'          => false,
];


$string = serialize($array);
$fixed  = \unserialize\fix($string);

echo ($array === $fixed) ? 'arrays match' : 'mismatch';