<?php

namespace Hans\Valravn\Tests\Feature\DTOs;

use Hans\Valravn\DTOs\MorphToDto;
use Hans\Valravn\Tests\TestCase;

class MorphToDtoTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
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
}
