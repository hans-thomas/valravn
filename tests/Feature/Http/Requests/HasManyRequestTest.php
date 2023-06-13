<?php

	namespace Hans\Valravn\Tests\Feature\Http\Requests;

	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Instances\Http\Requests\PostCategoriesHasManyRequest;
	use Hans\Valravn\Tests\TestCase;
	use Illuminate\Validation\Rule;

	class HasManyRequestTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function hasMany(): void {
			$rules = app( PostCategoriesHasManyRequest::class )->rules();

			self::assertEquals(
				[
					'related'      => [ 'array' ],
					'related.*.id' => [ 'required', 'numeric', Rule::exists( Post::class, 'id' ) ],
				],
				$rules
			);
		}

	}