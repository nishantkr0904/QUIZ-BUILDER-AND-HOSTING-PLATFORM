<?php
namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::where('user_id', Auth::id())->get();
        return view('user.quizzes', compact('results'));
    }
}
