<?php

namespace NoeFleury\InfomaniakSdk\Tests\Unit;

use NoeFleury\InfomaniakSdk\Enum\RequestBuilder\OrderDirection;
use NoeFleury\InfomaniakSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\CoversClass;
#[CoversClass(OrderDirection::class)]
class BasicTest extends TestCase
{

    #[CoversNothing]
    public function test_nothing()
    {
        $this->assertTrue(true);
    }


    public function test_thing() {
        $this->assertSame('asc', OrderDirection::Ascendant->value);
    }

}
