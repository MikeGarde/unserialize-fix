<?php namespace unserialize;

/**
 * @param $string A PHP Serialized String (use JSON next time)
 *
 * @return mixed|null
 * @throws \Exception
 */
function fix($string)
{
	try
	{
		$return = unserialize($string);

		return $return;
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
					preg_match('/^([0-9]+)/', substr($string, $i + 2), $intMatch);
					$placeholder[] = (int) $intMatch[1];
					$i             = $i + strlen($intMatch[1]);
					break;
				case 'd':
					preg_match('/^([0-9.]+)/', substr($string, $i + 2), $intMatch);
					$placeholder[] = floatval($intMatch[1]);
					$i             = $i + strlen($intMatch[1]);
					break;
				case 'b':
					$bool          = substr($string, $i + 2, 1);
					$placeholder[] = ($bool == 1) ? true : false;
					$i             = $i + 1;
					break;
				case 'N':
					$placeholder[] = null;
					break;
				case 'O':
					throw new \Exception('Objects are not supported by unserialize fix');
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


/**
 * @param $string
 * @param $key
 *
 * @return null|string|integer
 */
function getValue($string, $key)
{
	preg_match('/"' . $key . '";(s:[0-9]+:"([^"]*)"|i:([0-9]+));/', $string, $match);

	return (isset($match[2])) ? $match[2] : null;
}