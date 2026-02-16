<?php

namespace App\Http\Controllers;

use App\Models\QuoteStatus;
use Illuminate\Http\Request;

class QuoteStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        // Retorna todos os status ordenados pelo ID
        return response()->json(QuoteStatus::orderBy('id')->get());
    }
}
