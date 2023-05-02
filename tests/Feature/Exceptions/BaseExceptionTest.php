<?php

	namespace Hans\Tests\Valravn\Feature\Exceptions;

	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Exceptions\BaseException;
	use Symfony\Component\HttpFoundation\Response;

	class BaseExceptionTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 * @throws BaseException
		 */
		public function make(): void {
			$exception = BaseException::make(
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