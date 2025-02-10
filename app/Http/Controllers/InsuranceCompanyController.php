<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use Illuminate\Http\Request;
use App\Services\ImageValidationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class InsuranceCompanyController extends Controller
{



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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048
            ',
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
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageService = new ImageValidationService();
           
            // Delete the old image if it exists
            $insuranceCompany->image = $imageService->update($request->file('image'), $insuranceCompany->image, 'images');
        }
    
        $insuranceCompany->name = $request->name;
        $insuranceCompany->save();
    
        return response()->json($insuranceCompany);
    }
    

    public function destroy(InsuranceCompany $insuranceCompany)
    {
        $imageService = new ImageValidationService();
        $imageService->delete($insuranceCompany->image);
        $insuranceCompany->delete();
        return response()->json(null, 204);
    }
}
