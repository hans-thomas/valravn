<?php

	namespace Hans\Valravn\Tests\Instances\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller;

	class SampleController extends Controller {
		public function index(): void { }

		public function create(): void { }

		public function store( Request $request ): void { }

		public function show( int $id ): void { }

		public function edit( int $id ): void { }

		public function update( int $id, Request $request ): void { }

		public function destroy( int $id ): void { }
	}