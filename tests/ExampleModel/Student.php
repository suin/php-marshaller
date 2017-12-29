<?php
declare(strict_types=1);

namespace Suin\Marshaller\ExampleModel;


class Student
{
    /**
     * @var StudentId
     */
    private $id;

    /**
     * @var StudentName
     */
    private $name;

    /**
     * @var Grade
     */
    private $grade;

    /**
     * @var \DateTime
     */
    private $registrationDate;

    /**
     * @var string
     */
    private $email;

    /**
     * @param StudentId   $id
     * @param StudentName $name
     * @param Grade       $grade
     * @param \DateTime   $registrationDate
     * @param string      $email
     */
    public function __construct(
        StudentId $id,
        StudentName $name,
        Grade $grade,
        \DateTime $registrationDate,
        string $email
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->grade = $grade;
        $this->registrationDate = $registrationDate;
        $this->email = $email;
    }
}
