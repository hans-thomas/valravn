<?php

	namespace Hans\Tests\Valravn\Instances\Http\Controllers;

	use Illuminate\Routing\Controller;

	class SampleBelongsToController extends Controller {
		public function viewRelation( int $sample ): void { }

		public function updateRelation( int $id, int $related ): void { }

	}