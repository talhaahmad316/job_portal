<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function createJob()
    {
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes=JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('jobs.create',[
            'categories'=>$categories,
            'jobtypes'=>$jobtypes,
        ]);
    }
    public function saveJob(Request $request)
    {
        $rules=[
            'title'=>'required|min:5|max:200',
            'category'=>'required',
            'jobtype'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|min:3|max:75',
        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->passes())
        {
            $job=new Job();
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobtype;
            $job->vacancy=$request->vacancy;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->save();
            session()->flash('success','Job Posted Successfully');
            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function myJobs()
    {
        return view('jobs.my-jobs');
    }
}
