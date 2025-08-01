<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class PublicHomeController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function index()
    {
        $categories = Category::with(['quizzes' => function($query) {
            $query->orderBy('difficulty');
        }])->orderBy('name')->get();

        $quizStats = [
            'total_quizzes' => Quiz::count(),
            'total_categories' => Category::count()
        ];

        return view('home', compact('categories', 'quizStats'));
    }
}
