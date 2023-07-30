<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Tests\TestCase;

class BatchUpdateDtoTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
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
}
