<?php

$file = __DIR__.'/../vendor/laravel/framework/config/database.php';

if (! file_exists($file)) {
    exit(0);
}

$content = file_get_contents($file);

if (strpos($content, 'Mysql::ATTR_SSL_CA') !== false) {
    exit(0);
}

$pattern = '/\<\?php\n/';
if (! str_contains($content, 'use Pdo\\Mysql;')) {
    $content = preg_replace($pattern, "<?php\n\nuse Pdo\\Mysql;\n\n", $content, 1);
}

$content = str_replace('PDO::MYSQL_ATTR_SSL_CA', 'Mysql::ATTR_SSL_CA', $content);

file_put_contents($file, $content);
