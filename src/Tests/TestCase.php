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

		public function getJsonRequest( $uri, array $headers = [], string $token = null ): TestResponse {
			return $this->getJson( $uri, $headers + [ 'Authorization' => $token ] );
		}

		public function postJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->postJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		public function patchJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->patchJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		public function deleteJsonRequest( $uri, array $data = [], array $headers = [], string $token = null ): TestResponse {
			return $this->deleteJson( $uri, $data, $headers + [ 'Authorization' => $token ] );
		}

		public function resourceToJson( BaseJsonResource $resource ): array {
			return json_decode(
				$resource->toResponse( request() )->content(),
				true
			);
		}

	}
