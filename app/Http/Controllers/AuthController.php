<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\User;
use App\Notifications\SignupActivate;
use App\Customer;
use GuzzleHttp\Client;

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
        'name'                => 'required|unique:users',
        'email'               => 'required|email|unique:users',
        'password'            => 'required|min:6|confirmed',
        'avatar'              => '',
    ], [
        'password.confirmed'  => 'The password does not match.'
    ]);

    $avatar = $request->avatar;

    $user = $this->create($request->all());
    ($user && $avatar) && $user->saveImage($avatar, 'avatar');
    try {
      $user->notify(new SignupActivate($user));
    } catch (\Exception $e) {
      // $user->active = 1;
      // $user->save();
    }

    $tokenResult = $user->createToken('UAT');
    $token = $tokenResult->token;
    if ($request->remember_me)
        $token->expires_at = Carbon::now()->addWeeks(1);
    $token->save();

    return response()->json([
        'message' => 'Successfully created user!',
        'user' => $user,
        'access_token' => $tokenResult->accessToken,
        'token_type' => 'Bearer',
        'expires_at' => Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString()
    ], 201);
  }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activation_token' => Str::random(60)
        ]);
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
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        $credentials['active'] = 0;
        // $credentials['deleted_at'] = null;

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'credentials does not match our records',
                'status' => false,
            ], 401);

        $user = $request->user();
        $user->withImageUrl(null, 'avatar');
        $tokenResult = $user->createToken('UAT');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user' => $user,
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

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $user;
    }

  /**
   * Register customer
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] message
   */
  public function register(Request $request)
  {
    $request->validate([
        // 'name' => 'required|unique:customers',
        'email'       => 'required|email',
        'password'    => 'required|min:6|confirmed',
        'firstname'   => 'required|min:3|max:30',
        'lastname'    => 'required|min:3|max:30',
        'avatar'      => '',
    ], [
        'password.confirmed' => 'The password does not match.'
    ]);
    $email = $request->email;
    $avatar = $request->avatar;

    // check email exists
    $customer = Customer::where('email', $email)->first();
    if($customer){
      // check password is set
      if ($customer->password) {
        // respond email exists and option to reset password
        return ['status' => false, 'message' => 'email exists please try the reset password option to reset your password'];
      } else {
        // send email Confirmation with required to input password
      }
      // register the customer
    } else {
      $customer = $this->createCustomer($request->all());
      ($customer && $avatar) && $customer->saveImage($avatar, 'avatar');
      try {
        // $customer->notify(new SignupActivate($customer));
      } catch (\Exception $e) {
        // $user->active = 1;
        // $user->save();
      }
      $tokenResult = $customer->createToken('PAT');
      $token = $tokenResult->token;
      if ($request->remember_me)
          $token->expires_at = Carbon::now()->addWeeks(1);
      $token->save();

      return response()->json([
          'status' => true,
          'message' => 'Successfully created user!',
          'user' => $customer,
          'access_token' => $tokenResult->accessToken,
          'token_type' => 'Bearer',
          'expires_at' => Carbon::parse(
              $tokenResult->token->expires_at
          )->toDateTimeString()
      ], 201);
    }
  }

  protected function createCustomer(array $data)
  {
    $data['password'] = bcrypt($data['password']);
    $data['activation_token'] = Str::random(60);

      return Customer::create($data);
  }

  /**
   * Login customer and create token
   *
   * @param  [string] email
   * @param  [string] password
   * @param  [boolean] remember_me
   * @return [string] access_token
   * @return [string] token_type
   * @return [string] expires_at
   */
  public function signin(Request $request)
  {
    $request->validate([
        'email'       => 'required|email',
        'password'    => 'required|string',
        'remember_me' => 'boolean'
    ]);
    $email        = $request->email;
    $password     = $request->password;

    $user = Customer::where('email', $email)->first();

    if ($user) {
      if (Hash::check($password, $user->password)) {
        $user->withImageUrl(null, 'avatar');
        $token = $user->grantMeToken();
        return [
          'access_token'    => $token['access_token'],
          'token_type'      => $token['token_type'],
          'user'            => $user,
          'expires_at'      => $token['expires_at']
        ];
      } else {
        throw ValidationException::withMessages([
          'password'      => trans('validation.password')
        ]);
      }
    } else {
      return ['status' => false, 'message' => trans('msg.user.not_exists')];
    }
  }

  /**
   * Logout customer (Revoke the token)
   *
   * @return [string] message
   */
  public function signout(Request $request)
  {
      $request->user()->token()->revoke();
      return response()->json([
          'message' => 'Successfully logged out'
      ]);
  }
}
