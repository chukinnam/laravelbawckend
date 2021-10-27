<?php
namespace App\Http\Controllers;use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetail;
class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            "name" => 'required|string',
            "email" => 'required|string|email|unique:users',
            "password" => array('required','string','confirmed','regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{0,}$/','min:8')
        ]);        
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);        
        $user->save();        
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);        
        $credentials = request(['email', 'password']);       
         if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'That Email or Password was incorrect. Please try again.'
            ], 401);       
        //$user = $request->user();     
        $user = User::where('email', $request->email)->first();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;     

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1); 

        $token->save();      

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
        )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();       
         return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {   
        return response()->json($request->user());
    }
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    public function getuserdetailinfo(Request $request)
    {

        $user=$request->user()->getdetailinformation;
        
        if(empty($user)){
            return response()->json([
                'message' => 'Empty user object'
            ], 404);       
        }
        return response()->json($user);

    }
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    public function updateuserdetailinfo(Request $request)
    {
        $request->validate([
            "firstname"=>"required|string",
            "lastname"=>"required|string",
            "phonenumber"=>"required|string",
            "address"=>"required|string"
        ]);
            $user=$request->user()->getdetailinformation;
            $user->firstname=$request->firstname;
            $user->lastname=$request->lastname;
            $user->phonenumber=$request->phonenumber;
            $user->address=$request->address;
            $user->save();
        return response()->json([
            'message' => 'update user detail information success'
        ]);
    }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

    public function insertuserdetailinfo(Request $request)
    {   
        $request->validate([
            "firstname"=>"required|string",
            "lastname"=>"required|string",
            "phonenumber"=>"required|string",
            "address"=>"required|string"
        ]);
        $userdetail = new UserDetail([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phonenumber' => $request->phonenumber,
            'address'=> $request->address,
            'user_id'=>$request->user()->id
        ]);        
        $userdetail->save();
        $user=$request->user()->getdetailinformation;
        return response()->json($user);     
    }
    public function usercheckout(Request $request)
    {
        $user=$request->user();
        $userid=$user->id;
        $orderarray=$request->orderdata;
        $numberarray=$request->number;
        for ($i=0; $i<count($orderarray); $i++) { 
            $user->userorder()->attach($orderarray[$i],['number' =>  $numberarray[$i],"status"=>"To Be Delivered"]);
        }
    }
    public function getuserorder(Request $request)
    {
        $user=$request->user();
        $userid=$user->id;
        $result=$user->userorder()->get();
        return response()->json($result);
    }
    
}