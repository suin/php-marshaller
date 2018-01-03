<?php

declare(strict_types=1);

namespace Suin\Marshaller;

use PHPUnit\Framework\TestCase;
use Suin\Marshaller\ExampleModel\Grade;
use Suin\Marshaller\ExampleModel\Student;
use Suin\Marshaller\ExampleModel\StudentId;
use Suin\Marshaller\ExampleModel\StudentName;
use Suin\Marshaller\ExampleModel\StudentProtocol;

class MarshallerTest extends TestCase
{
    /**
     * @dataProvider dataForTestMarshall
     * @param Protocol $protocol
     * @param          $object
     * @param          $expect
     */
    public function testMarshall(Protocol $protocol, $object, $expect): void
    {
        $marshaller = new Marshaller($protocol);
        $output = $marshaller->marshall($object);
        $this->assertEquals($expect, $output);
    }

    public function dataForTestMarshall()
    {
        return [
            [
                new Protocol,
                new StudentId(1),
                ['id' => 1],
            ],
            [
                new StudentProtocol(),
                new StudentId(1),
                1,
            ],
            [
                new StudentProtocol,
                new Student(
                    new StudentId(1),
                    new StudentName(
                        'Alice',
                        'Brown'
                    ),
                    Grade::freshman(),
                    new \DateTime('2017-04-01', new \DateTimeZone('UTC')),
                    'alice@example.com'
                ),
                [
                    'id' => 1,
                    'name' => [
                        'firstName' => 'Alice',
                        'lastName' => 'Brown',
                    ],
                    'grade' => 'freshman',
                    'registrationDate' => '2017-04-01T00:00:00+0000',
                    'email' => 'alice@example.com',
                ],
            ],
            [
                new StudentProtocol,
                [
                    new Student(
                        new StudentId(1),
                        new StudentName(
                            'Alice',
                            'Brown'
                        ),
                        Grade::freshman(),
                        new \DateTime('2017-04-01', new \DateTimeZone('UTC')),
                        'alice@example.com'
                    ),
                    new Student(
                        new StudentId(2),
                        new StudentName(
                            'Bob',
                            'Smith',
                            'F'
                        ),
                        Grade::sophomore(),
                        new \DateTime('2016-04-01', new \DateTimeZone('UTC')),
                        'bob@example.com'
                    ),
                ],
                [
                    [
                        'id' => 1,
                        'name' => [
                            'firstName' => 'Alice',
                            'lastName' => 'Brown',
                        ],
                        'grade' => 'freshman',
                        'registrationDate' => '2017-04-01T00:00:00+0000',
                        'email' => 'alice@example.com',
                    ],
                    [
                        'id' => 2,
                        'name' => [
                            'firstName' => 'Bob',
                            'lastName' => 'Smith',
                            'middleName' => 'F',
                        ],
                        'grade' => 'sophomore',
                        'registrationDate' => '2016-04-01T00:00:00+0000',
                        'email' => 'bob@example.com',
                    ],
                ],
            ],
        ];
    }
}
