<?php
declare(strict_types=1);

namespace Suin\Marshaller;

use PHPUnit\Framework\TestCase;
use Suin\Marshaller\ExampleModel\Grade;
use Suin\Marshaller\ExampleModel\Student;
use Suin\Marshaller\ExampleModel\StudentId;
use Suin\Marshaller\ExampleModel\StudentName;
use Suin\Marshaller\ExampleModel\StudentProtocol;

class ConstructorUnmarshallerTest extends TestCase
{
    /**
     * @dataProvider dataForTestMarshall
     * @param Protocol $protocol
     * @param mixed    $data
     * @param string   $class
     * @param object   $expected
     */
    public function testUnmarshal(Protocol $protocol, $data, string $class, $expected)
    {
        $marshaller = new ConstructorUnmarshaller($protocol);
        $output = $marshaller->unmarshal($data, $class);

        $this->assertEquals($expected, $output);
    }

    public function dataForTestMarshall()
    {
        return [
            'Value class' => [
                new Protocol,
                ['id' => 1],
                StudentId::class,
                new StudentId(1),
            ],
            'Value' => [
                new StudentProtocol,
                1,
                StudentId::class,
                new StudentId(1),
            ],
            'Aggregate root class' => [
                new StudentProtocol,
                [
                    'id' => 1,
                    'name' => [
                        'firstName' => 'Alice',
                        'lastName' => 'Brown',
                    ],
                    'grade' => 'freshman',
                    'registrationDate' => '2017-04-01',
                    'email' => 'alice@example.com',
                ],
                Student::class,
                new Student(
                    new StudentId(1),
                    new StudentName(
                        'Alice',
                        'Brown'
                    ),
                    Grade::freshman(),
                    new \DateTime('2017-04-01'),
                    'alice@example.com'
                ),
            ],
            'Aggregate root collection' => [
                new StudentProtocol,
                [
                    [
                        'id' => 1,
                        'name' => [
                            'firstName' => 'Alice',
                            'lastName' => 'Brown',
                        ],
                        'grade' => 'freshman',
                        'registrationDate' => '2017-04-01',
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
                        'registrationDate' => '2016-04-01',
                        'email' => 'bob@example.com',
                    ],
                ],
                Student::class . '[]',
                [
                    new Student(
                        new StudentId(1),
                        new StudentName(
                            'Alice',
                            'Brown'
                        ),
                        Grade::freshman(),
                        new \DateTime('2017-04-01'),
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
                        new \DateTime('2016-04-01'),
                        'bob@example.com'
                    ),
                ],
            ],
            'Value collection' => [
                new StudentProtocol,
                [1, 2, 3],
                StudentId::class . '[]',
                [new StudentId(1), new StudentId(2), new StudentId(3)],
            ],
            'Nested value collection' => [
                new StudentProtocol,
                [[1], [2], [3]],
                StudentId::class . '[][]',
                [[new StudentId(1)], [new StudentId(2)], [new StudentId(3)]],
            ],
        ];
    }
}
