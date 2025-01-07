<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\MorphToDto;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;

class MorphToDtoTest extends TestCase
{
    #[Test]
    public function parse(): void
    {
        $data = [
            'entity'    => 'posts',
            'namespace' => 'blog',
        ];

        $result = MorphToDto::make([
            'related' => $data,
        ]);

        self::assertEquals(
            ['posts'],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function parseEmptyData(): void
    {
        $result = MorphToDto::make([])->getData();

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(0, $result);
    }
}
