<?php

namespace Domain\ValueObject;

use App\Domain\ValueObject\Year;
use PHPUnit\Framework\TestCase;

class YearTest extends TestCase
{
    public function test_it_should_return_any_year_as_string_when_year_is_greater_than_zero()
    {
        $year = new Year(2023);
        $this->assertInstanceOf(Year::class, $year);
        $this->assertEquals(2023, $year->getValue());
        $this->assertEquals("2023", (string)$year);
    }

    public function test_it_should_return_the_year_formatted_with_before_christ_when_year_is_negative(): void
    {
        $year = new Year(-500);
        $this->assertInstanceOf(Year::class, $year);
        $this->assertEquals(-500, $year->getValue());
        $this->assertEquals('500B.C.', (string)$year);
    }

    public function test_it_should_return_0_as_string_is_case_0_year(): void
    {
        $year = new Year(0);
        $this->assertInstanceOf(Year::class, $year);
        $this->assertEquals(0, $year->getValue());
        $this->assertEquals('0', (string)$year);
    }
}
