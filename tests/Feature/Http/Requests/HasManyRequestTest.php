<?php

	namespace Hans\Tests\Valravn\Feature\Http\Requests;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Instances\Http\Requests\PostCategoriesHasManyRequest;
	use Hans\Tests\Valravn\TestCase;
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