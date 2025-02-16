<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BlogModel;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use App\Services\ImageValidationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'created_at' => $BlogModel->created_at,
                'updated_at' => $BlogModel->updated_at,
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
        Log::info($request);
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
    public function update(Request $request, BlogModel $blogModel)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageService = new ImageValidationService();
           
            // Delete the old image if it exists
            $blogModel->image = $imageService->update($request->file('image'), $blogModel->image, 'images');
        }
    
        $blogModel->title = $request->title;
        $blogModel->description = $request->description;
        $blogModel->category = $request->category;
        $blogModel->slug = $request->title;

        $blogModel->save();
    
        return response()->json($blogModel);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogModel $blogModel)
    {
        $imageService = new ImageValidationService();
        $imageService->delete($blogModel->image);
        $blogModel->delete();
        return response()->json(null, 204);
    }
}
