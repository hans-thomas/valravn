<?php

namespace Hans\Valravn\Tests\Instances\Http\Controllers;

    use Illuminate\Routing\Controller;

    class SampleMorphToController extends Controller
    {
        public function viewRelation(int $id): void
        {
        }

        public function updateRelation(int $id, int $related): void
        {
        }
    }
