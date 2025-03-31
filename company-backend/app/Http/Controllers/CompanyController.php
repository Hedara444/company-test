<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyRegisterRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\Company\CompanyCollection;
use App\Services\CompanyServices;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(private CompanyServices $companyService) {}


    /**
     * Display a listing of the resource.
     */
    public function index():CompanyCollection
    {
        return $this->companyService->listCompanies(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRegisterRequest $request):JsonResponse
    {
        return $this->companyService->createCompany($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->companyService->showCompany($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, $id)
    { $validatedData=[
        'name'=>$request->name,
        'email'=>$request->email,
        'country'=>$request->country,
        'industry'=>$request->industry,
        'phone'=>$request->phone,
    ];


     return $this->companyService->updateCompanyData($validatedData , $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->companyService->deleteCompany($id);
    }
}
