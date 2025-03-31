<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inquiry\InquiryRegisterRequest;
use App\Services\InquiryServices;
use Illuminate\Http\Request;
use App\Models\Inquiry;
class InquiryController extends Controller
{

    protected $InqueryServices;
    public function __construct(InquiryServices $inquiryServices)
    {
        $this->InqueryServices = $inquiryServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->InqueryServices->listInquiries(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InquiryRegisterRequest $request)
    {
        return $this->InqueryServices->submitInquiry($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->InqueryServices->showInquiry($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
