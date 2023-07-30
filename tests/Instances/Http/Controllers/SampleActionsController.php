<?php

namespace Hans\Valravn\Tests\Instances\Http\Controllers;

use Hans\Valravn\Commands\Controller;

class SampleActionsController extends Controller
{
    public function action(int $id): void
    {
    }

    public function actionWithParams(int $id, int $related, int $something): void
    {
    }

    public function actionWithNoParam(): void
    {
    }
}
