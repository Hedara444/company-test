<?php

namespace App\Services;


use App\Models\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Company\CompanyResource;

class CompanyServices
{

    protected $phoneVerificationService;

    public function __construct(PhoneVerificationService $phoneVerificationService)
    {
        $this->phoneVerificationService = $phoneVerificationService;
    }

   public function createCompany($validatedData):JsonResponse
   {
       $phoneNumber =  $validatedData->phone;
       //verify the phone number
       $res= $this->phoneVerificationService->verifyPhoneNumber($phoneNumber);
       if(isset($res['valid']) && $res['valid'])
       { //if the phone number is valid , create new company
           Company::create([
               'name'=> $validatedData->name,
               'email'=>$validatedData->email,
               'phone'=>$validatedData->phone,
               'country'=>$validatedData->country,
               'industry'=>$validatedData->industry,
           ]);
           return  response()->json(["message"=>"Company Created Successfully"],201);
       }
       //if the phone number is invalid , return an error
       return  response()->json(["error"=>"your phone number is not valid"],422);
   }

   public function listCompanies( int $perPage = 10):CompanyCollection
   {
       $companies = Company::paginate($perPage);
       return new CompanyCollection($companies);
   }

   public function showCompany($id):CompanyResource|JsonResponse
   {

       $company = Company::find($id);
       if(!$company){
           return response()->json(['error'=>'Company not found'],404);
       }
       return new  CompanyResource($company);
   }

   public function updateCompanyData(array $validatedData , $id):JsonResponse
   { $company = Company::find($id);
     $company->fill(array_filter($validatedData));
     $company->save();
     return response()->json([],204);
   }

   public function deleteCompany($id):JsonResponse
   {$company = Company::find($id);
       if(!$company)
       {
           return response()->json(['error'=>'Company not found'],404);
       }
           $company->delete();
           return response()->json(["message"=>"Company deleted successfully"]);
   }
}
