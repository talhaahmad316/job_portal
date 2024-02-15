<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // This method is for realtion with database and
    // show categorie on dropdown button
    public function createJob()
    {
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes=JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('jobs.create',[
            'categories'=>$categories,
            'jobtypes'=>$jobtypes,
        ]);
    }
    // This Method is used to save the jobs 
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
            $job->user_id=Auth::user()->id;
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
    //This method will show Jobs data in Table
    public function myJobs()
    {
        $jobs = Job::where('user_id',Auth::user()->id)->with('jobType')->paginate(10);
        return view('jobs.my-jobs',[
            'jobs'=>$jobs
        ]);
    }
    // This method is to show the edit page and show old job values and
    // Categories and Jobtypes 
    public function editJobs(Request $request, $id)
    {
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes=JobType::orderBy('name','ASC')->where('status',1)->get();
        $job=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
        ])->first();
        if($job == null){
            abort(404);
        }
        return view('jobs.edit',[
            'categories'=>$categories,
            'jobtypes'=>$jobtypes,
            'job'=>$job,
        ]);
    }
    // This method to updated jobs
    public function updateJob(Request $request, $id)
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
            $job=Job::find($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobtype;
            $job->user_id=Auth::user()->id;
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
            session()->flash('success','Job Upadted Successfully');
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
    // This Method to Delete the Jobs
    public function deleteJob(Request $request)
    {
        $job = Job::where([
            'user_id'=>Auth::user()->id,
            'id'=> $request->jobId
        ])->first();
        if($job == null){
            session()->flash('error','Either job deleted or not found');
            return response()->json([
                'status'=>false,
            ]);
        }
        Job::where('id',$request->jobId)->delete();
        session()->flash('success','Job deleted Successfully');
            return response()->json([
                'status'=>true,
            ]);
    } 
}
