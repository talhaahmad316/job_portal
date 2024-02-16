<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Job;

class HomeController extends Controller
{
    // This Method will show home page
    // Also show jobs categoriies featuredjobs and latestjobs on home page
    public function index()
    {
        $categories = Category::where('status',1)
                                ->orderBy('name','ASC')
                                ->take(8)->get();

        $featuredJobs = Job::where('status',1)
                             ->orderBy('created_at','DESC')
                             ->with('jobType')
                             ->where('isFeatured',1)
                             ->take(6)->get();
        $latestJobs = Job::where('status',1)
                             ->with('jobType')
                             ->orderBy('created_at','DESC')
                             ->take(6)->get();

        return view('welcome',[
            'categories'=>$categories,
            'featuredJobs'=>$featuredJobs,
            'latestJobs'=>$latestJobs,
        ]);
    }
}
