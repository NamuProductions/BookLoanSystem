<?php
declare(strict_types=1);

namespace Util;

use App\Util\UUID;
use PHPUnit\Framework\TestCase;

class UUIDTest extends TestCase
{
    private UUID $sut;

    public function test_it_should_generate_valid_uuid(): void
    {
        $uuid = $this->sut::generate();

        $this->assertSame(36, strlen($uuid));

        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $uuid);
    }

    public function test_it_should_generate_unique_uuids(): void
    {
        $uuid1 = $this->sut::generate();
        $uuid2 = $this->sut::generate();

        $this->assertNotSame($uuid1, $uuid2);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new UUID();
    }
}
