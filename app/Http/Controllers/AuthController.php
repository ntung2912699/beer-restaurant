<?php

namespace App\Http\Controllers;

use App\Mail\NotifyAdminNewUser;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function indexLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Thông tin đăng nhập không chính xác'], 401);
        }

        if (!$user->is_approved) {
            return response()->json(['message' => 'Tài khoản của bạn chưa được phê duyệt bởi quản trị viên. Vui lòng liên hệ để được hỗ trợ.'], 403);
        }

        auth()->login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // Hiển thị form đăng ký
    public function indexRegister()
    {
        return view('auth.register');
    }

    public function registerRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tạo mã OTP
        $otp = random_int(100000, 999999);
        $key = 'otp_' . $request->email;

        // Lưu vào Cache 5 phút
        Cache::put($key, [
            'otp' => $otp,
            'data' => $request->only('name', 'email', 'password')
        ], now()->addMinutes(5));

        // Gửi email OTP
        Mail::to($request->email)->send(new SendOtpMail($otp));

        return response()->json(['message' => 'Mã xác thực đã được gửi đến email!']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $key = 'otp_' . $request->email;
        $otpData = Cache::get($key);

        if (!$otpData || $otpData['otp'] != $request->otp) {
            return response()->json(['message' => 'Mã xác thực không đúng hoặc đã hết hạn.'], 422);
        }

        // Lấy dữ liệu user từ cache
        $data = $otpData['data'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'roles' => 'USER',
            'is_approved' => false,
        ]);

        Cache::forget($key);

        // Gửi email thông báo admin có user mới chờ duyệt
        $adminEmails = User::where('roles','ADMIN')->get('email');
        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new NotifyAdminNewUser($user));
        }

        return response()->json(['message' => 'Đăng ký thành công! Vui lòng chờ quản trị viên phê duyệt tài khoản.']);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete(); // Chỉ xóa token hiện tại, không xóa toàn bộ
        }

        // Nếu có session web (không cần thiết nếu bạn chỉ dùng API)
        Auth::guard('web')->logout();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

}
