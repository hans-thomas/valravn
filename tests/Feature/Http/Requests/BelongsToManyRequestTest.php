<?php

namespace Hans\Valravn\Tests\Feature\Http\Requests;

use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\Instances\Http\Requests\PostCategoriesRequest;
use Hans\Valravn\Tests\Instances\Http\Requests\PostCategoriesWithPivotRequest;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Validation\Rule;
use PHPUnit\Framework\Attributes\Test;
use function PHPUnit\Framework\assertEquals;

class BelongsToManyRequestTest extends TestCase
{
    #[Test]
    public function belongsToMany(): void
    {
        $rules = app(PostCategoriesRequest::class)->rules();

        assertEquals(
            [
                'related' => ['array'],
                'related.*.id' => ['required', 'numeric', Rule::exists(Post::class, 'id')],
            ],
            $rules
        );
    }

    #[Test]
    public function belongsToManyWithPivot(): void
    {
        $rules = app(PostCategoriesWithPivotRequest::class)->rules();

        assertEquals(
            [
                'related' => ['array'],
                'related.*.id' => ['required', 'numeric', Rule::exists(Post::class, 'id')],
                'related.*.pivot' => ['array:order,info'],
                'related.*.pivot.order' => ['numeric', 'min:1', 'max:99'],
                'related.*.pivot.info' => ['string', 'max:128'],
            ],
            $rules
        );
    }
}
