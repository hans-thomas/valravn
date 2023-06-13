<?php

	namespace Hans\Valravn\Tests\Instances\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller;

	class SampleHasManyController extends Controller {
		public function viewRelations( int $id ): void { }

		public function updateRelations( int $id, Request $request ): void { }

	}