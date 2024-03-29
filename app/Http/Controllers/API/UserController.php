<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;




class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            
            return User::latest()->paginate(10);
        }

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users',
            'password'=>'required|string|min:6'


        ]);
        // return $request->all();
        return User::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password'=>Hash::make($request['password']),
            'type'=> $request['type'],
            'bio'=> $request['bio'],
            'photo'=> $request['photo'],
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {


        $user= auth('api')->user();
        $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'=>'sometimes|required|min:6'


        ]);
        $curentphoto=$user->photo;
        //  return ['message'=>'success'];
     if($request->photo != $curentphoto ) {





         //njebdo extention men base64 and unique name
         $name= time().'.'.explode('/',explode(':', substr($request->photo,0,strpos($request->photo ,';')))[1])[1];
         //upload in php intervention composer package
         Image::make($request->photo)->save(public_path('img/profile/').$name);

         //change photo in database
         $request->merge(['photo'=>$name]);
         $userphoto=public_path('img/profile/').$curentphoto;
         if(file_exists( $userphoto)){
             @unlink($userphoto);
         }

     }

     if(!empty($request->password)){

        $request->merge(['password' => Hash::make($request['password'])]);


     }
     $user->update($request->all());
     return ['message'=>'success'];


    }
    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return ['messaget'=>'update User information'];
        $user= User::findOrFail($id);
        $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'=>'sometimes|min:6'


        ]);

        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user=User::findOrFail($id);
        //delet the user
        $user->delete();

    }
}
