<?php

	namespace Hans\Tests\Valravn\Feature\Http\Requests;

	use Hans\Tests\Valravn\Instances\Http\Requests\LikeLikableRequest;
	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Validation\Rule;
	use function PHPUnit\Framework\assertEquals;

	class MorphToRequestTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function morphTo(): void {
			$rules = ( new LikeLikableRequest( request: [ 'related.entity' => 'posts' ] ) )->rules();

			assertEquals(
				[
					'related'        => [ 'array:entity' ],
					'related.entity' => [ 'required', 'string', Rule::in( [ 'posts', 'comments' ] ) ],
				],
				$rules
			);
		}

	}