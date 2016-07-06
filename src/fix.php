<?php namespace unserialize;

/**
 * @param $string
 *
 * @return array|mixed|string
 */
function fix($string)
{
	try
	{
		//return unserialize($string);
	}
	catch (Exception $e)
	{
		// do Nothing
	}

	$start    = 0;
	$isArray  = false;
	$isString = false;

	$checkEnd    = false;
	$depth       = 0;
	$placeholder = [];

	$length = strlen($string);
	for ($i = 0; $i < $length; $i++)
	{
		$character = substr($string, $i, 1);
		$current   = substr($string, $start, $i - $start);

		if (!$isArray && !$isString)
		{
			switch ($character)
			{
				case 'a':
					$isArray = true;
					break;
				case 's':
					$isString = true;
					break;
				case 'i':
					preg_match('/^([0-9]+)/', substr($string, $start + 2), $intMatch);
					$placeholder[] = (int) $intMatch[1];
					$i             = $i + strlen($intMatch[1]);
					continue;
					break;
			}
			continue;
		}

		if (($isArray && $character == '{') || ($isString && $character == '"'))
		{
			if (!$start)
			{
				$start = $i + 1;
			}
			$depth++;
		}
		elseif (($isArray && $character == '}') || ($isString && $character == '"'))
		{
			$depth--;
		}

		if (($checkEnd && $isString && $character == ';') || ($isArray && $character == '}' && $depth == 0))
		{
			if ($isString)
			{
				$placeholder[] = substr($string, $start, $i - $start - 1);
			}
			elseif ($isArray)
			{
				$newString     = substr($string, $start, $i - $start);
				$placeholder[] = \unserialize\fix($newString);
			}

			$isArray  = false;
			$isString = false;
			$checkEnd = false;
			$start    = 0;
			$depth    = 0;

			continue;
		}

		if ($isString && $character == '"' && $depth == 2)
		{
			$checkEnd = true;
		}

	}

	if (count($placeholder) == 1)
	{
		return $placeholder[0];
	}

	$position = 0;
	$key      = 0;
	$return   = null;

	foreach ($placeholder as $part)
	{
		if ($position % 2 == 0)
		{
			$key = $part;
		}
		else
		{
			$return[ $key ] = $part;
		}
		$position++;
	}

	return $return;
}
