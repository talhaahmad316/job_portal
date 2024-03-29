<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AuthController extends Controller
{
    // This method will show user registretion page
    public function register()
    {
        return view("auth.register");
    }
    // This method will save a user
    public function processregistretion(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|same:confirm_password',
            'confirm_password'=>'required',
        ]);
        if($validator->passes()){
            $user=new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=$request->password;
            $user->save();
            session()->flash('success','Registered Successfully');
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
    // This method will show user login page
    public function login()
    {
        return view("auth.login");

    }
    // This method will login a user
    public function auth(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required',
        ]);
        if($validator->passes()){
            if(Auth::attempt(['email'=> $request->email, 
                              'password'=>$request->password])){
                return redirect()->route('user.profile');                

            } else{
                return redirect()->route('user.login')->with('error','Invalid User Details');
            }

        } else{
            return redirect()->route('user.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    // This method will show user profile
    public function profile()
    {
        $id = Auth::user()->id;
        $user=USer::where('id',$id)->first();

        return view('auth.profile',[
            'user'=>$user,
        ]);
    }
    // This method will Update Name ,Email,Drsignation,mobile
    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:5|max:20',
            'email'=>'required|email|unique:users,email,'.$id.',id',
        ]); 
        if($validator->passes()){
            $user=User::find($id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->designation=$request->designation;
            $user->mobile=$request->mobile;
            $user->save();
            session()->flash('success','Profile Updated Successfully');

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
    // This method will show user profile
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('user.login')->with('success','User Logout Succsssfully');
    }
    // This Method will Upload profile pic
    public function updateProfilePic(Request $request)
    {
        $id = Auth::user()->id;

        $validator=Validator::make($request->all(),[
            'image'=>'required|image',
        ]);
        if($validator->passes()){
            $image=$request->image;
            $imageName=$id.'-'.time().'.'.$request->image->extension();
            $image->move(public_path('/profile_pic'),$imageName);
            // Create a Small Thumbnail
            $sourcePath=public_path('profile_pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);
            // crop the best fitting 5:3 (150x150) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('profile_pic/thumb/'.$imageName));
            // Delete old Profile pic
            File::delete(public_path('profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('profile_pic/'.Auth::user()->image));

            User::where('id',$id)->update(['image'=>$imageName]); 
            session()->flash('success','Profile Pic Uploaded Successfully');
            return response()->json([
                'status'=>true,
                'errors'=>[],
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }
}
