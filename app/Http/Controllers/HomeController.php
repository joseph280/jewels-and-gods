<?php

namespace App\Http\Controllers;

use Auth;
use Inertia\Response;
use App\Services\ContractApiManagerInterface;

class HomeController extends Controller
{
    public function index(ContractApiManagerInterface $api): Response
    {
        return inertia('Home', [
            'assets' => $api->assets(Auth::user()->account_id),
            'wallets' => Auth::user()->wallets()->with('token')->get(),
        ]);
    }
}
