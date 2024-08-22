<?php
namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\User;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class AuthController extends Controller
{
    public function __construct(
        protected readonly User $user,
        protected readonly Customer $customer
    )
    {}
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|max:15',
            'email' => 'required|email',
            'address' => 'required',
//            'role' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 'customer';
        $user = User::create($input);
        $customer = $this->customer->create([
            "name" => $input['name'],
            "phone_number" => $input['phone_number'],
            "address" => $input['address'],
            "user_id" => $user->id,
        ]);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        if ($input['role'] == 'customer') {
            $success =  $this->customer->with('customer_user')->where('user_id', $user->id)->get();
        } else {
            $success['data'] =  $user;
        }
        return ApiResponse::success(
            [
                "data" => [
                "user" => $success,
                ]
            ], 'Register', 'User');
    }
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            if (Auth::user()->role == 'customer') {
                $user = $this->customer->with('customer_user')->where('user_id', Auth::user()->id)->get();
            } else {
                $user = Auth::user();
            }
            $success['user'] =  $user;
            return ApiResponse::success( ["data" => $success], 'Login', 'User');
        }else{
            return ApiResponse::error( 'Login', 'User');
        }
    }
}
