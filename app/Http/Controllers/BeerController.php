<?php

namespace App\Http\Controllers;

use App\Exports\BeerExport;
use App\Http\Requests\BeerRequest;
use App\Jobs\ExportJob;
use App\Jobs\SendExportEmailJob;
use App\Jobs\StoreExportDataJob;
use App\Mail\ExportEmail;
use App\Models\Export;
use App\Models\Meal;
use App\Services\PunkapiService;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class BeerController extends Controller
{
    public function index(BeerRequest $request, PunkapiService $service)
    {
        $filters = $request->validated();

        $beers = $service->getBeers(...$filters);

        $meals = Meal::all();

        return Inertia::render('Beers', [
            'beers' => $beers,
            'meals' => $meals,
            'filters' => $request->validated()
        ]);
    }

    public function export(BeerRequest $request, PunkapiService $service)
    {
        $filename = 'cervejas_encontradas_'. now()->format('d-m-Y_H:i').'.xlsx';

        ExportJob::withChain([
            new SendExportEmailJob($filename),
            new StoreExportDataJob(auth()->user(), $filename)
        ])->dispatch($request->validated(), $filename);

        return redirect()->back()->with('success', 'Seu arquivo foi enviado para o processamento e em breve estará em seu e-mail.');
    }
}
