<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(): View
    {
        // View all companies
        $companies = Company::all();
 
        return view('companies.index', compact('companies'));
    }
}
