<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(){
        $feedbacks = Feedback::get();
        return view('feedback.list',compact('feedbacks'));
    }
}
