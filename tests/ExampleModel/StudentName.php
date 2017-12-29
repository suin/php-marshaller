<?php
declare(strict_types=1);

namespace Suin\Marshaller\ExampleModel;


class StudentName
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $middleName;

    /**
     * @param string      $firstName
     * @param string      $lastName
     * @param string|null $middleName
     */
    public function __construct(string $firstName, string $lastName, ?string $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }
}
