<?php namespace unserialize;

/**
 * @param $string
 *
 * @return array|mixed|string
 */
function fix($string)
{
	$basePattern = '/^([asi]):[0-9]+:(.*)$/i';
	preg_match($basePattern, $string, $matches);

	switch ($matches[1])
	{
		case 'a':
			$return = processArray($matches[2]);
			break;
		case 's':
			$return = processString($matches[2]);
			break;
		case 'i':
			$return = processInteger($matches[2]);
			break;
		default:
			break;
	}

	return $return;
}

/**
 * @param $string
 *
 * @return array
 */
function processArray($string)
{
	$return  = [];
	$pattern = "/[asi]:[0-9]+:(\"|\\{)[^\"\\\\]*(?:\\\\.[^\"\\\\]*)*(\"|\\});/m";
	preg_match_all($pattern, $string, $matches);

	$position = 0;
	$key      = 0;

	foreach ($matches[0] as $part)
	{
		$tmp = \unserialize\fix($part);

		if ($position % 2 == 0)
		{
			$key = $tmp;
		}
		else
		{
			$return[ $key ] = $tmp;
		}
		$position++;
	}

	return $return;
}

/**
 * @param $string
 *
 * @return mixed
 */
function processString($string)
{
	$pattern     = '/^"(.*)";$/';
	$replacement = '$1';

	return preg_replace($pattern, $replacement, $string);
}

/**
 * @param $string
 *
 * @return string
 */
function processInteger($string)
{
	return trim($string, ';');
}