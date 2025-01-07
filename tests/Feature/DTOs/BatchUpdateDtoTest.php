<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;

class BatchUpdateDtoTest extends TestCase
{
    #[Test]
    public function parse(): void
    {
        $data = [
            ['id' => 1],
            ['id' => 1, 'the art' => "no limit i'm a fucking soldier"],
            ['id' => 5, 'the artist' => 'g-eazy'],
        ];
        $result = BatchUpdateDto::make([
            'batch' => $data,
        ]);

        self::assertEquals(
            [
                ['id' => 1, 'the art' => "no limit i'm a fucking soldier"],
                ['id' => 5, 'the artist' => 'g-eazy'],
            ],
            array_values($result->getData()->toArray())
        );
    }

    #[Test]
    public function makeFromArray(): void
    {
        $data = [
            ['id' => 1],
            ['id' => 1, 'the art' => "no limit i'm a fucking soldier"],
            ['id' => 5, 'the artist' => 'g-eazy'],
        ];

        $result = BatchUpdateDto::makeFromArray($data);

        self::assertEquals(
            [
                ['id' => 1, 'the art' => "no limit i'm a fucking soldier"],
                ['id' => 5, 'the artist' => 'g-eazy'],
            ],
            array_values($result->getData()->toArray())
        );
    }

    #[Test]
    public function parseEmptyData(): void
    {
        $result = BatchUpdateDto::make([])->getData();

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(0, $result);
    }
}
