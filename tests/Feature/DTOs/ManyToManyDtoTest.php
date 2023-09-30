<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\ManyToManyDto;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Collection;

class ManyToManyDtoTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function parse(): void
    {
        $data = [
            [
                'id'         => 1,
                'the art'    => "for the what it's worth, you were a slut at birth, if the world had a dick, you would fuck the earth",
                'the artist' => 'eminem',
                'pivot'      => [
                    'extra' => "same as it ever was, say it'll change but it never does ain't gonna ever 'cause you're the cause of my pain and the medicine",
                    'song'  => 'farewell',
                ],
            ],
            [
                'id'         => 6,
                'the art'    => 'dark magic, night walker, she hunts me like no other, nobody told me love is pain.',
                'the artist' => 'eminem',
                'song'       => 'black magic',
            ],
            [
                'id' => 8,
            ],
            [
                'id'    => 6,
                'pivot' => [
                    'the art'    => 'sex plus drugs plus rock and roll added, that equation mixed with success and raw talent',
                    'the artist' => 'g-eazy',
                ],
            ],
        ];
        $result = ManyToManyDto::make([
            'related' => $data,
        ]);
        self::assertEquals(
            [
                1 => [
                    'extra' => "same as it ever was, say it'll change but it never does ain't gonna ever 'cause you're the cause of my pain and the medicine",
                    'song'  => 'farewell',
                ],
                7 => 8,
                6 => [
                    'the art'    => 'sex plus drugs plus rock and roll added, that equation mixed with success and raw talent',
                    'the artist' => 'g-eazy',
                ],
            ],
            $result->getData()->toArray()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function withValues(): void
    {
        $data = [
            [
                'id' => 1,
            ],
            [
                'id' => 8,
            ],
            [
                'id'    => 6,
                'pivot' => [
                    'the art'    => 'ever seen a devil with a halo?',
                    'the artist' => 'g-eazy',
                ],
            ],
        ];
        $result = ManyToManyDto::make([
            'related' => $data,
        ])->withValues(['song' => 'the beautiful and damned']);

        self::assertEquals(
            [
                1 => ['song' => 'the beautiful and damned'],
                8 => ['song' => 'the beautiful and damned'],
                6 => [
                    'song'       => 'the beautiful and damned',
                    'the art'    => 'ever seen a devil with a halo?',
                    'the artist' => 'g-eazy',
                ],
            ],
            $result->toArray()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function withValuesForced(): void
    {
        $data = [
            [
                'id' => 1,
            ],
            [
                'id' => 8,
            ],
            [
                'id'    => 6,
                'pivot' => [
                    'the art'    => 'ever seen a devil with a halo?',
                    'the artist' => 'g-eazy',
                    'song'       => 'hate the way i always miss you',
                ],
            ],
        ];
        $result = ManyToManyDto::make([
            'related' => $data,
        ])->withValues(['song' => 'the beautiful and damned'], true);

        self::assertEquals(
            [
                1 => ['song' => 'the beautiful and damned'],
                8 => ['song' => 'the beautiful and damned'],
                6 => [
                    'song'       => 'the beautiful and damned',
                    'the art'    => 'ever seen a devil with a halo?',
                    'the artist' => 'g-eazy',
                ],
            ],
            $result->toArray()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function parseEmptyData(): void
    {
        $result = ManyToManyDto::make([])->getData();

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(0, $result);
    }
}
