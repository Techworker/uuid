<?php

date_default_timezone_set('Europe/Berlin');

require_once ('../vendor/autoload.php');

$uuid_v1 = \Techworker\Uuid::v1("5d:ef:0d:53:7a:50");
$exposer = \Techworker\Uuid\VersionProvider\RFC4122\V1::expose($uuid_v1);

print_r($exposer->data());

echo $uuid_v1;
exit;


$uuid_v1 = \Techworker\Uuid::v1("5d:ef:0d:53:7a:50");
$uuid_v2 = \Techworker\Uuid::v2("5d:ef:0d:53:7a:50", posix_getuid());
$uuid_v3 = \Techworker\Uuid::v3("my_unique_value", TW_UUID_NAMESPACE_DNS);
$uuid_v4 = \Techworker\Uuid::v4();
$uuid_v5 = \Techworker\Uuid::v5("my_unique_value", TW_UUID_NAMESPACE_DNS);
