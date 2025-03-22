<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        
        $credentials = request(['email', 'password']);
      
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        //return $request->all();
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|between:2,255',
                'last_name'  => 'required|string|between:2,255',
                'email'      => 'required|string|email|max:100|unique:users',
                'password'   => 'required|string|min:6',
                'confirm_password'   => 'required|string|same:password',
                'image'      => 'sometimes|string',
                'gender'     => 'required|string',
                'date_of_birth' => 'nullable|date',
                'academic_year' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password'=> bcrypt($request->password)]
            ));
            

            // Log in the newly created user and generate a token
          

            return response()->json([
                'message' => 'User successfully registered',
                'user'    => $user,
                
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user()); // Returns the authenticated user
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        auth()->logout();
        return response()->json(['message' => 'Succesfully logged out']);
    } catch (\Exeption $e) {
        return response()->json(['error' => $e->getMessage()], 500); 
    }
}

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
      try {
            $oldToken = JWTAuth::getToken(); //token i skadum ose i paskadum
            $newToken = auth()->refresh();

            if($oldToken){
                 try {
                    JWTAuth::invalidate($oldToken);
                 } catch (\Exeption $e) {
                    \Log::warning("Tokencould not be invalidated");
                 }
            }
            return $this->respondWithToken($newToken);
      } catch (\Exeption $e) {
        return response()->json(['error' => 'Could not refresh token ', "message" => $e->getMessage()], 401);
      }
    }


    
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
} 
