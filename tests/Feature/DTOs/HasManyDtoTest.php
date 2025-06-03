<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\HasManyDto;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;

class HasManyDtoTest extends TestCase
{
    #[Test]
    public function parse(): void
    {
        $data = [
            ['id' => 1],
            [
                'id' => 1,
                'the art' => "my haters feel like i'm better dead, but i'm quite alive and getting bread instead",
            ],
            [
                'id' => 5,
                'the artist' => 'g-eazy',
                'song' => 'I Mean It',
            ],
        ];
        $result = HasManyDto::make([
            'related' => $data,
        ]);

        self::assertEquals(
            [1, 5],
            array_values($result->getData()->toArray())
        );
    }

    #[Test]
    public function parseEmptyData(): void
    {
        $result = HasManyDto::make([])->getData();

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(0, $result);
    }
}
