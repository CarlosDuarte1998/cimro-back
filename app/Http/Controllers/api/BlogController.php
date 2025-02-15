<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BlogModel;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use App\Services\ImageValidationService;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = BlogModel::all();
        $blog = BlogModel::with('user')->get();
        $blog = $blog->map(function ($BlogModel) {
            return [
                'id' => $BlogModel->id,
                'image' => $BlogModel->image,
                'title' => $BlogModel->title,
                'description' => $BlogModel->description,
                'slug' => $BlogModel->title,
                'category' => $BlogModel->category,
                'user' => [
                    'id' => $BlogModel->user->id,
                    'name' => $BlogModel->user->name,
                    'email' => $BlogModel->user->email,
                ],
            ];
        });
        return response()->json($blog);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'required|image',
        ]);

        $imageService = new ImageValidationService();

        $imagePath = $imageService->validateAndSave($request->file('image'), 'images');

        $blog = new BlogModel();

        $blog->image = $imagePath;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->category = $request->category;
        $blog->slug = $request->title;
        $blog->user_id = Auth::id();
        $blog->save();


        return response()->json($blog, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogModel $blog)
    {
        return response()->json($blog);
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
