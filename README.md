Marshaller
================
[![travis-ci-badge]][travis-ci] [![packagist-dt-badge]][packagist]

Type-safe data mapping between JSON and a PHP class object.

## Features

1. Transforms JSON to PHP class object.
2. Transforms PHP object to JSON.

## How does it works?

Marshaller analyze all private properties and convert them into JSON. Unmarshaller analyze the signature of the constructor of the given class and convert JSON into an object.

## Usage

### Marshal object and Unmarshal JSON

This is the simplest use case of Marshaller.

```php
use Suin\Marshaller\JsonMarshaller;
use Suin\Marshaller\StandardProtocol;

// Transform an object to JSON.
$marshaller = new JsonMarshaller(new StandardProtocol);
$json = $marshaller->marshal(new Cat('Oliver'));
var_dump($json);
// Output:
// string(17) "{"name":"Oliver"}"

// Transform JSON to an object.
$cat = $marshaller->unmarshal($json, Cat::class);
var_dump($cat);
// Output:
// object(Cat)#%d (1) {
//   ["name":"Cat":private]=>
//   string(6) "Oliver"
// }
```

Please see [example#01](./example/01-marshal-object-and-unmarshal-json.php) for details.

### Protocols

Sometimes you would want to decode a JSON value in special transform way. In such a case, you can also define transforming rules between a PHP object and a JSON object by using `Protocol`s and `Format`s.

A `Format` describes how a single class or type becomes JSON and vice versa. All `Format`s must follow the interface below:

```php
interface Format<A, B> {
  public function read(A $jsonValue): B
  public function write(B $object): A
}
```

For example, a `Format` is implemented like this:

```php
class HealthFormat // naming rule: class name + "Format"
{
    // Define how transform a JSON value to a PHP object.
    public function read(string $health): Health
    {
        return new Health($health === 'healthy');
    }

    // Define how transform a PHP object to a JSON value.
    public function write(Health $health): string
    {
        return $health->isHealthy() ? 'healthy' : 'sick';
    }
}
```

Also, a protocol is a class that have a collection of `Format`s. The following example has only one format, but some more formats would be included here in real use case.

```php
class HealthProtocol extends StandardProtocol
{
    public function __construct()
    {
        parent::__construct(
            new HealthFormat
        );
    }
}
```

To see complete example code of protocols and formats, please see [example#02](./example/02-define-protocol.php).

#### Installation via Composer
``` bash
$ composer require suin/marshaller
```

#### Running tests
``` bash
$ composer test
```

#### License
This library is licensed under the MIT license. Please see [LICENSE](LICENSE.md) for more details.

#### Changelog
Please see [CHANGELOG](CHANGELOG.md) for more details.

#### Contributing
Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for more details.

<!-- Badges -->
[travis-ci]: https://travis-ci.org/suin/php-marshaller
[travis-ci-badge]: https://img.shields.io/travis/suin/php-marshaller.svg?style=flat-square
[packagist]: https://packagist.org/packages/suin/marshaller
[packagist-dt-badge]: https://img.shields.io/packagist/dt/suin/marshaller.svg?style=flat-square
