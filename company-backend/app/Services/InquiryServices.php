<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Inquiry\InquiryCollection;
use App\Http\Resources\Inquiry\InquiryResource;

class InquiryServices
{

    protected $phoneVerificationService;

    public function __construct(PhoneVerificationService $phoneVerificationService)
    {
        $this->phoneVerificationService = $phoneVerificationService;

    }

    public function submitInquiry($validatedData):JsonResponse
    {   //check if the company doesn't exist
        $company = Company::find($validatedData->companyId);
        if(!$company)
        {
            return response()->json(['error'=>'Company Not Found'],404);
        }

        $phoneNumber =  $validatedData->phone;
        //verify the phone number
        $res= $this->phoneVerificationService->verifyPhoneNumber($phoneNumber);

        if(isset($res['valid']) && $res['valid'])
        { //if the phone number is valid , create new inquiry
            Inquiry::create([
                'name'=> $validatedData->name,
                'email'=>$validatedData->email,
                'phone'=>$validatedData->phone,
                'company_id'=>$validatedData->companyId,
                'message'=>$validatedData->message,
            ]);
            return  response()->json(["message"=>"Your Inquiry Submitted Successfully"],201);
        }

        //if the phone number is invalid , return an error
        return  response()->json(["error"=>"Your Phone Number is not valid"],422);
    }

    public function listInquiries( int $perPage = 10):InquiryCollection
    {
        $companies = Inquiry::paginate($perPage);
        return new InquiryCollection($companies);
    }

    public function showInquiry($id):InquiryResource|JsonResponse
    {

        $inquiry = Inquiry::find($id);
        if(!$inquiry){
            return response()->json(['error'=>'Inquiry not found'],404);
        }
        return new  InquiryResource($inquiry);
    }


}
