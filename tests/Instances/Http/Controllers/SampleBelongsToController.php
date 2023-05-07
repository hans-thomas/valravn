<?php

	namespace Hans\Tests\Valravn\Instances\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller;

	class SampleBelongsToController extends Controller {
		public function viewRelation( int $id ): void { }

		public function updateRelation( int $id, int $related ): void { }

	}