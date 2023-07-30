<?php

namespace Hans\Valravn\Tests\Instances\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SampleBelongsToManyController extends Controller
{
    public function viewRelations(int $id): void
    {
    }

    public function updateRelations(int $id, Request $request): void
    {
    }

    public function attachRelations(int $id, Request $request): void
    {
    }

    public function detachRelations(int $id): void
    {
    }
}
