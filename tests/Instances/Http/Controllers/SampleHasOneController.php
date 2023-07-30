<?php

namespace Hans\Valravn\Tests\Instances\Http\Controllers;

    use Illuminate\Routing\Controller;

    class SampleHasOneController extends Controller
    {
        public function viewRelation(int $id): void
        {
        }

        public function updateRelation(int $id, int $related): void
        {
        }
    }
