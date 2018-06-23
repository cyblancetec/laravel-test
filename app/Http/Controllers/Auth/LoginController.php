<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\User;
use App\OneTimePassword;
use Mail,Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::select('id','name','email','password')
                      ->where($this->username(), request()->get('email'))
                      ->first();
        if(empty($user)){
            return $this->sendFailedLoginResponse($request);    
        }else{
            if (!(Hash::check($request->get('password'), $user->password))) {
                return $this->sendFailedLoginResponse($request);    
            }else{
                if (!OneTimePassword::where('user_id', $user->id)->exists()) {
                    return $this->sendOTP($request, $user);
                }
            }
        }
        
        if ($this->attemptLogin($request)) {
            OneTimePassword::where('user_id', $user->id)->delete();
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        if($request->has('otp')){
            
            $this->validate(request(), [
                'otp' => [function ($attribute, $value, $fail) {
                    if ($value == '') {
                        $fail('The :attribute field is required.');
                    }else{
                        $otp = OneTimePassword::where('code', request()->get('otp'))
                        ->where('user_id', request()->get('user_id'))
                        ->first();
                        if(empty($otp)){
                            $fail('Please enter valid :attribute.');
                        }else{
                            $to = $otp->created_at;
                            $date = new \DateTime();
                            $from = $date->format('Y-m-d H:i:s');
                            $diff = $to->diffInMinutes($from);
                            if($diff > 5){
                                $otp->delete();
                                return redirect()->back()->with('otp-error','An OTP has been expired.');
                            }else{
                                request()->request->add(['email' => $otp->email]);
                                request()->request->add(['password' => Crypt::decryptString($otp->password)]);
                            }
                        }
                    }
                }]
            ]);

        }else{
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    public function sendOTP(Request $request, $user){

        $data['name'] = $user->name;
        $data['code'] = str_random(5);
        $otp = new OneTimePassword;
        $otp->user_id = $user->id;
        $otp->email = request()->get('email');
        $otp->password = Crypt::encryptString(request()->get('password'));
        $otp->code = $data['code'];
        $otp->save();

        Mail::send('email_template.send-otp', $data, 
            function($message) use ($data){
                $message->to(request()->get('email'))->subject('Laravel56 One Time Password');
                $message->from('info@Laravel56.com', 'Laravel56');
            }
        );

        return redirect()->back()
            ->withInput($request->only($this->username(), 'password', 'remember'))
            ->with('show-otp','An OTP has been sent to your registered email')
            ->with('user_id', $user->id);
    }
    
}
