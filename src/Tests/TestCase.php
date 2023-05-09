<?php

	namespace Hans\Valravn\Tests;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Testing\TestResponse;
	use Tests\CreatesApplication;

	abstract class TestCase extends BaseTestCase {
		use CreatesApplication, RefreshDatabase, WithFaker;

		/**
		 * Make a get json request and set the token
		 *
		 * @param             $uri
		 * @param array       $headers
		 * @param string|null $token
		 *
		 * @return TestResponse
		 */
		public function getJsonRequest( $uri, array $headers = [], string $token = null ): TestResponse {
			return $this->getJson( $uri, $headers + [ 'Authorization' => $token ] );
		}

		/**
		 * Make a post json request and set the token
		 *
		 * @param             $uri
		 * @param array       $data
		 * @param array       $headers
		 * @param string|null $token
		 *
		 * @return TestResponse
		 */
		public function postJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->postJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		/**
		 * Make a patch json request and set the token
		 *
		 * @param             $uri
		 * @param array       $data
		 * @param array       $headers
		 * @param string|null $token
		 *
		 * @return TestResponse
		 */
		public function patchJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->patchJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		/**
		 * Make a delete json request and set the token
		 *
		 * @param             $uri
		 * @param array       $data
		 * @param array       $headers
		 * @param string|null $token
		 *
		 * @return TestResponse
		 */
		public function deleteJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->deleteJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		/**
		 * Convert resource class to an array
		 *
		 * @param BaseJsonResource $resource
		 *
		 * @return array
		 */
		public function resourceToJson( BaseJsonResource $resource ): array {
			return json_decode(
				$resource->toResponse( request() )->content(),
				true
			);
		}

	}
