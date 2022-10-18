<?php

namespace App\Http\Controllers;

use App\Service\PunkapiService;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class BeerController extends Controller
{
    public function index(Request $request, PunkapiService $service)
    {
        $params = $request->all();
        return $service->getBeers(...$params);
    }

    public function export()
    {
        return 'export';
    }
}
