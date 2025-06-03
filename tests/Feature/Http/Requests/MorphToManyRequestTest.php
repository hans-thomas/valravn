<?php

namespace Hans\Valravn\Tests\Feature\Http\Requests;

use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Http\Requests\PostCategoriesMorphToManyRequest;
use Hans\Valravn\Tests\Instances\Http\Requests\PostCategoriesMorphToManyWithPivotRequest;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Attributes\Test;

class MorphToManyRequestTest extends TestCase
{
    #[Test]
    public function belongsToMany(): void
    {
        $rules = app(PostCategoriesMorphToManyRequest::class)->rules();

        self::assertEquals(
            [
                'related'      => ['array'],
                'related.*.id' => ['required', 'numeric', Rule::exists(Post::class, 'id')],
            ],
            $rules
        );
    }

    #[Test]
    public function belongsToManyWithPivot(): void
    {
        $rules = app(PostCategoriesMorphToManyWithPivotRequest::class)->rules();

        self::assertEquals(
            [
                'related'               => ['array'],
                'related.*.id'          => ['required', 'numeric', Rule::exists(Post::class, 'id')],
                'related.*.pivot'       => ['array:order,info'],
                'related.*.pivot.order' => ['numeric', 'min:1', 'max:99'],
                'related.*.pivot.info'  => ['string', 'max:128'],
            ],
            $rules
        );
    }
}
