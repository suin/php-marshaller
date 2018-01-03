<?php

declare(strict_types=1);
use Suin\Marshaller\JsonMarshaller;
use Suin\Marshaller\StandardProtocol;

class Health
{
    private $isHealthy = true;

    public function __construct(bool $isHealthy)
    {
        $this->isHealthy = $isHealthy;
    }

    public function isHealthy(): bool
    {
        return $this->isHealthy;
    }
}

class HealthFormat
{
    public function read(string $health): Health
    {
        return new Health($health === 'healthy');
    }

    public function write(Health $health): string
    {
        return $health->isHealthy() ? 'healthy' : 'sick';
    }
}

class HealthProtocol extends StandardProtocol
{
    public function __construct()
    {
        parent::__construct(
            new HealthFormat
        );
    }
}

// Transform an object to JSON
$marshaller = new JsonMarshaller(new HealthProtocol);
$json = $marshaller->marshal(new Health(true));
var_dump($json);
// Output:
// string(9) ""healthy""

// Transform JSON to an object
$health = $marshaller->unmarshal($json, Health::class);
var_dump($health);
// Output:
// object(Health)#%d (1) {
//   ["isHealthy":"Health":private]=>
//   bool(true)
// }
