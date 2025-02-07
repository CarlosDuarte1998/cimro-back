<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use Illuminate\Http\Request;
use App\Services\ImageValidationService;
use Illuminate\Support\Facades\Auth;


class InsuranceCompanyController extends Controller
{
    //api

    public function index()
    {
        $insuranceCompanies = InsuranceCompany::all();
        $insuranceCompanies = InsuranceCompany::with('user')->get();

        $insuranceCompanies = $insuranceCompanies->map(function ($insuranceCompany) {
            return [
                'id' => $insuranceCompany->id,
                'name' => $insuranceCompany->name,
                'image' => $insuranceCompany->image,
                'user' => [
                    'id' => $insuranceCompany->user->id,
                    'name' => $insuranceCompany->user->name,
                    'email' => $insuranceCompany->user->email,
                ],
            ];
        });

        return response()->json($insuranceCompanies);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image',
        ]);

        $imageService = new ImageValidationService();

        $imagePath = $imageService->validateAndSave($request->file('image'), 'images');

        $insuranceCompany = new InsuranceCompany();

        $insuranceCompany->name = $request->name;
        $insuranceCompany->image = $imagePath;
        $insuranceCompany->user_id = Auth::id();
        $insuranceCompany->save();


        return response()->json($insuranceCompany, 201);
    }

    public function show(InsuranceCompany $insuranceCompany)
    {
        return response()->json($insuranceCompany);
    }

    public function update(Request $request, InsuranceCompany $insuranceCompany)
    {
        
    }

    public function destroy(InsuranceCompany $insuranceCompany)
    {
        $insuranceCompany->delete();
        return response()->json(null, 204);
    }
}
