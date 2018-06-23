<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->paginate(6);

        return view('home',compact('posts'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function post(Post $post){

        return view('post',compact('post'));
    }

    public function editProfile(User $user){
        
        return view('editProfile',compact('user'));
    }

    public function updateProfile(Request $request, User $user){

        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id.',id',
            'phone' => 'nullable|regex:/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = date('mdYHis').uniqid().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/avtars');
            $imagePath = $destinationPath. "/".  $imageName;
            $image->move($destinationPath, $imageName);
            $user->photo = $imageName;
        }
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->save();

        return redirect()->back()->with('success','Profile has been updated.');
    }
}
