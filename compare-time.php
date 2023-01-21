<?php
if ( ! (file_exists('./benchmark-rollup.time.txt') && file_exists('./benchmark-vite.time.txt')) )
   exit("\e[31m[ERROR] The benchmark files are not been found.\e[0m\n");

$rol = array_map('trim',file('./benchmark-rollup.time.txt'));
$vit = array_map('trim',file('./benchmark-vite.time.txt'));
$len = max(count($rol),count($vit));
$max = 0;

for ( $i = 0; $i < $len; $i++ ) $max = max($max, strlen($rol[$i]), strlen($vit[$i]));

$date = date('Y-m-d H:i');

print<<<EOF
# Benchmark rollup/vite

Generated at $date

## Usage:

```shell
$ /usr/bin/time --verbose --output=./benchmark-rollup.time.txt make build-rollup
$ /usr/bin/time --verbose --output=./benchmark-vite.time.txt make build-vite
$ php -f compare-time.php > benchmark.md
```


EOF;

$max+= strlen('***') * 2; // ***...***

printf("| %s | %s |\n", str_pad('rollup', $max, ' ', STR_PAD_RIGHT), str_pad('vite', $max, ' ', STR_PAD_RIGHT));
printf("|%s|%s|\n", str_repeat('-', 2 + $max), str_repeat('-', 2 + $max));
for ( $i = 0; $i < $len; $i++ )
{
   $r = $rol[$i];
   $v = $vit[$i];
   if ( strcmp($r,$v) !== 0 )
   {
      $r = "***{$r}***";
      $v = "***{$v}***";
   }
   printf("| %s | %s |\n", str_pad($r, $max, ' ', STR_PAD_RIGHT), str_pad($v, $max, ' ', STR_PAD_RIGHT));
}

@unlink('./benchmark-rollup.time.txt');
@unlink('./benchmark-vite.time.txt');