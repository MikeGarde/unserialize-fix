# unserializeFix
PHP Unserialize Fixer

The best way to use serialize is to use json_encode, but when the guy before you used serialize use this unserialize instead.

```
$string = json_encode($array);
$array  = json_decode($string);
```

##Install

```
composer require "mikegarde/unserialize-fix:0.1.*"
```

No need to "use" if you are already including "autoload.php"

```
require __DIR__ . '/../vendor/autoload.php';

$fixed  = \unserialize\fix($string);
```

##Notes

I through this together so that I could work on some old logs and it is not 100% however was able to unserialize my logs. 
When time allows I will finish this project which will probably include new names so make sure you stick to 0.1.* and 
check here before upgrading to 0.2