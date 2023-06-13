<?php

	namespace Hans\Valravn\Tests\Feature\Exceptions;

	use Hans\Valravn\Tests\TestCase;
	use Hans\Valravn\Exceptions\ValravnException;
	use Symfony\Component\HttpFoundation\Response;

	class ValravnExceptionTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 * @throws ValravnException
		 */
		public function make(): void {
			$exception = ValravnException::make(
				"she was classy, now she is nasty.",
				1009,
				Response::HTTP_FORBIDDEN
			);

			self::assertEquals(
				[
					'title'  => 'Unexpected error!',
					'detail' => "she was classy, now she is nasty.",
					'code'   => 1009
				],
				$exception->render()->getData( true )
			);

			$this->expectExceptionMessage( "she was classy, now she is nasty." );
			$this->expectExceptionCode( Response::HTTP_FORBIDDEN );
			throw $exception;
		}
	}