<?php

declare(strict_types=1);

namespace Suin\Marshaller\ExampleModel;

use Suin\Marshaller\StandardProtocol;

class StudentProtocol extends StandardProtocol
{
    public function __construct()
    {
        parent::__construct(
            $this->studentIdFormat(),
            $this->gradeFormat()
        );
    }

    private function studentIdFormat()
    {
        return new class {
            public function read(int $value): StudentId
            {
                return new StudentId($value);
            }

            public function write(StudentId $id): int
            {
                return $id->id();
            }
        };
    }

    private function gradeFormat()
    {
        return new class {
            public function read(string $grade): Grade
            {
                switch ($grade) {
                    case 'freshman':
                        return Grade::freshman();
                    case 'sophomore':
                        return Grade::sophomore();
                    case 'junior':
                        return Grade::junior();
                    case 'senior':
                        return Grade::senior();
                    default:
                        throw new \LogicException("Unknown grade: ${grade}");
                }
            }

            public function write(Grade $grade): string
            {
                switch (true) {
                    case $grade->isFreshman():
                        return 'freshman';
                    case $grade->isSophomore():
                        return 'sophomore';
                    case $grade->isJunior():
                        return 'junior';
                    case $grade->isSenior():
                        return 'senior';
                    default:
                        throw new \LogicException("Unknown grade: ${grade}");
                }
            }
        };
    }
}
