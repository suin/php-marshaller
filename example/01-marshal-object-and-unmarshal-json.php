<?php

use Suin\Marshaller\JsonMarshaller;
use Suin\Marshaller\StandardProtocol;

class Cat
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

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
