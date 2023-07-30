<?php

namespace Hans\Valravn\Tests\Feature\Http\Requests;

use Hans\Valravn\Tests\Instances\Http\Requests\LikeLikableRequest;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\assertEquals;

class MorphToRequestTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function morphTo(): void
    {
        $rules = ( new LikeLikableRequest(request: ['related.entity' => 'posts']) )->rules();

        assertEquals(
            [
                'related'        => ['array:entity'],
                'related.entity' => ['required', 'string', Rule::in(['posts', 'comments'])],
            ],
            $rules
        );
    }
}
