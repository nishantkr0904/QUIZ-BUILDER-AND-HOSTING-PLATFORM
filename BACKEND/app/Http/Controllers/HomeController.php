<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all categories with their published quizzes
        $categories = Category::with(['quizzes' => function($query) {
            $query->where('status', 'published')
                  ->orderBy('difficulty')
                  ->orderBy('title');
        }])->get();

        // Get total counts
        $totalQuizzes = Quiz::where('status', 'published')->count();
        $totalCategories = Category::count();

        return view('home', compact('categories', 'totalQuizzes', 'totalCategories'));
    }
}
