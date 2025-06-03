<?php

namespace Hans\Valravn\Tests\Feature\Models;

use Hans\Valravn\Tests\Core\Factories\PostFactory;
use Hans\Valravn\Tests\Core\Models\Post;
use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PaginatableTest extends TestCase
{
    #[Test]
    public function getPerPage(): void
    {
        $model = new Post();

        self::assertEquals(
            15,
            $model->getPerPage()
        );

        request()->merge([
            'per_page' => 17,
        ]);
        self::assertEquals(
            17,
            $model->getPerPage()
        );
    }

    #[Test]
    public function perPageMax(): void
    {
        $model = new Post();

        request()->merge([
            'per_page' => 50,
        ]);
        self::assertEquals(
            30,
            $model->getPerPage()
        );

        Post::setPerPageMax(60);

        request()->merge([
            'per_page' => 50,
        ]);
        self::assertEquals(
            50,
            $model->getPerPage()
        );

        Post::setPerPageMax(30);
    }

    #[Test]
    public function perPageAll(): void
    {
        PostFactory::new()->count(100)->create();
        $model = new Post();

        request()->merge([
            'per_page' => 'all',
        ]);
        self::assertEquals(
            30,
            $model->getPerPage()
        );

        Post::setPerPageMax(60);

        request()->merge([
            'per_page' => 'all',
        ]);
        self::assertEquals(
            60,
            $model->getPerPage()
        );
    }
}
