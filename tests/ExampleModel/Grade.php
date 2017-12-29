<?php
declare(strict_types=1);

namespace Suin\Marshaller\ExampleModel;


class Grade
{
    private const FRESHMAN = 1;
    private const SOPHOMORE = 2;
    private const JUNIOR = 3;
    private const SENIOR = 4;

    /**
     * @var int
     */
    private $grade;

    private function __construct(int $grade)
    {
        $this->grade = $grade;
    }

    public static function freshman(): Grade
    {
        return new self(self::FRESHMAN);
    }

    public static function sophomore(): Grade
    {
        return new self(self::SOPHOMORE);
    }

    public static function junior(): Grade
    {
        return new self(self::JUNIOR);
    }

    public static function senior(): Grade
    {
        return new self(self::SENIOR);
    }

    public function isFreshman(): bool
    {
        return $this->grade === self::FRESHMAN;
    }

    public function isSophomore(): bool
    {
        return $this->grade === self::SOPHOMORE;
    }

    public function isJunior(): bool
    {
        return $this->grade === self::JUNIOR;
    }

    public function isSenior(): bool
    {
        return $this->grade === self::SENIOR;
    }
}
