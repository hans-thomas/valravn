<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\Tests\Instances\DTOs\SampleDto;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VDtoTest extends TestCase
{
    #[Test]
    public function make(): void
    {
        $data = [
            ['id' => 1],
            ['id' => 3],
        ];

        $result = SampleDto::make(['related' => $data]);

        self::assertEquals(
            [
                ['id' => 1],
                ['id' => 3],
            ],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function makeWithNoWrapper(): void
    {
        $data = [
            ['id' => 1],
            ['id' => 3],
        ];

        $result = SampleDto::make($data);

        self::assertEquals(
            [
                ['id' => 1],
                ['id' => 3],
            ],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function makeOneItemWithNoWrapper(): void
    {
        $data = [
            ['id' => 1],
        ];

        $result = SampleDto::make($data);

        self::assertEquals(
            [
                ['id' => 1],
            ],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function makeFromArray(): void
    {
        $data = [
            1,
            3 => [
                'the art'    => "i ain't even see the face, but she got beautiful boobies.",
                'the artist' => 'post malone',
            ],
        ];

        $result = SampleDto::makeFromArray($data);

        self::assertEquals(
            [
                ['id' => 1],
                [
                    'id'    => 3,
                    'pivot' => [
                        'the art'    => "i ain't even see the face, but she got beautiful boobies.",
                        'the artist' => 'post malone',
                    ],
                ],
            ],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function makeFromArrayWithEmptyArray(): void
    {
        $data = [];

        $result = SampleDto::makeFromArray($data);

        self::assertEquals(
            [],
            $result->getData()->toArray()
        );
    }

    #[Test]
    public function export(): void
    {
        $data = [
            ['id' => 1],
            [
                'id'    => 3,
                'pivot' => [
                    'the art'    => "i ain't even see the face, but she got beautiful boobies.",
                    'the artist' => 'post malone',
                ],
            ],
        ];
        $result = SampleDto::export(['related' => $data]);
        self::assertEquals(
            [
                ['id' => 1],
                [
                    'id'    => 3,
                    'pivot' => [
                        'the art'    => "i ain't even see the face, but she got beautiful boobies.",
                        'the artist' => 'post malone',
                    ],
                ],
            ],
            $result->toArray()
        );
    }
}
