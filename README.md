Techworker\Uuid
====

## Introduction

This class/namespace provides the functionality to create and import UUIDs (Universally Unique IDentifier) as defined in RFC 4122 (http://www.ietf.org/rfc/rfc4122.txt) and interpreted by myself as well as other developers. It supports the versions 1, 2, 3, 4 and 5 of the specification. It is also capable of creating custom UUIDs where the contents of the UUID can be defined by yourself.

I have not tagged this library at all, because I did not use it in production so far and, well, nobody else ever used it by now. So if you want a tagged stable release, give me some feedback.

Testing is incomplete too :-/

### Limitations

For versions 1 and 2 we need the MAC Address. The class will not try to read the MAC address of the host system since this will produce OS specific code which I cannot guarantee to be executed with success. You have to provide that data by yourself. See [http://de.wikipedia.org/wiki/MAC-Adresse#Ermitteln_und_Vergabe_einer_MAC-Adresse] on how the different Systems provide access to the MAC - its german language but I think you'll get the point.

The next problem is that PHP does not provide us a method to fetch the timestamp in nano-precision (maximum is microseconds). The generation is accomplished through microsecond precision with an emulated nanosecond counter.

## Credits

The source code of this package is inspired by various other UUID implementations like Mono, python, nodejs etc. I'm not a good bit shifter, so some shifting snippets are ported 1 to 1 from other language implementations.

## Usefulness

An UUID can give you extended information about a record. If you take a look at the v1 implementation, you can see that we can extract the creation date of the UUID as well as the MAC Address, which helps you identify the system on which the UUID was generated.

There are various other implementations out there. Groupon, for instance, created its own UUID implementation (uuid-locality), which holds the creation-timestamp in seconds, a part from the mac address, a version and the process ID which is all packed into an UUID 128 bit string. 

## Uniqueness

It's actually quite hard to check the uniqueness of the `UUID` generation because it depends on many parameters which are most likely not as unique or random as one would think. The most uniqueness-secure approach from my point of view is by using the v4 implementation of the UUID or by creating your own UUID specification. 

If you need a more reliable uniqueness (because I cannot really assure the uniqueness by myself), I would like you to either use the v5 UUID with a namespace and a clearly unique value that you can rely on or build your own `ByteProvider` which assures the uniqueness of your UUIDs (event though they might not fit into the specification).

## Some short info about the built-in versions

### UUID Version 1

The first version of the UUID takes the MAC address of the generating system and a nanosecond based timestamp into account which in fact thought to be unique across all systems. But a MAC Address can be set manually and the speed of the computation can be faster than the nanosecond approach can assure. That is why they did some follow up versions of the UUID.

### UUID Version 2

The second version uses the same data as the v1 but actually takes the current Posix-UID or Posix-GID as an additional identifier into account. See http://www.php.net/posix-getgid and http://www.php.net/posix-getuid for an explanation.

### UUID Version 3

The third version takes a value of your choice, combines it with a namespace (which in fact is a predefined UUID by itself) and creates a md5 hash from it. This 128 bit hash is the UUID. You can see that this system is only as unique as the value and namespace combination that you provide. And even then, md5s can collide: [http://www.mscs.dal.ca/~selinger/md5collision/]. Internally, the md5 can be created from a string, an int or an object (which gets serialized via spl_object_hash).

### UUID Version 4

The version 4 is a pure unique UUID. This is accomplished using the  http://php.net/manual/de/function.openssl-random-pseudo-bytes.php function.

### UUID Version 5

Version 5 is like Version 3 but it uses the sha1 algorithm instead.

## Usage

```php
use Techworker\Uuid;

// version specific factories
$uuid_v1 = \Techworker\Uuid::v1("5d:ef:0d:53:7a:50");
$uuid_v2 = \Techworker\Uuid::v2("5d:ef:0d:53:7a:50", posix_getuid());
$uuid_v3 = \Techworker\Uuid::v3("my_unique_value", TW_UUID_NAMESPACE_DNS);
$uuid_v4 = \Techworker\Uuid::v4();
$uuid_v5 = \Techworker\Uuid::v5("my_unique_value", TW_UUID_NAMESPACE_DNS);

```

### Create from existing UUID String

No description available by now, look at the `Techworker\Uuid::factory` method.

### Expose Data from and UUID

``` php
$uuid_v1 = \Techworker\Uuid::v1("5d:ef:0d:53:7a:50");
$exposer = \Techworker\Uuid\VersionProvider\RFC4122\V1::expose($uuid_v1);

print_r($exposer->data());
```