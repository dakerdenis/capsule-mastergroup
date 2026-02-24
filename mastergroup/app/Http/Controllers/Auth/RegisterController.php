<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function choose()
    {
        return view('auth.register', ['title' => 'Create account']);
    }

    public function showUser()
    {
        return view('auth.register_user', ['title' => 'Register as Individual']);
    }

    public function showCompany()
    {
        return view('auth.register_company', ['title' => 'Register as Company']);
    }


    public function store(Request $request)
{
    Log::info('[REGISTER] incoming', [
        'ip' => $request->ip(),
        'ua' => $request->userAgent(),
        'url' => $request->fullUrl(),
        'client_type' => $request->input('client_type'),
        'has_files' => [
            'identity_photo' => $request->hasFile('identity_photo'),
            'profile_photo' => $request->hasFile('profile_photo'),
            'company_logo'  => $request->hasFile('company_logo'),
        ],
        'payload_keys' => array_keys($request->except(['password', 'password_confirmation'])),
    ]);

    $clientType = $request->input('client_type');
    if (!in_array($clientType, ['individual', 'company'], true)) {
        Log::warning('[REGISTER] invalid client_type', ['client_type' => $clientType]);
        abort(422, 'Invalid client type.');
    }

    // birth_date normalize BEFORE validation
    if ($request->filled('birth_date')) {
        try {
            $request->merge([
                'birth_date' => Carbon::createFromFormat('m/d/Y', $request->birth_date)->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            Log::warning('[REGISTER] birth_date parse failed', [
                'birth_date_raw' => $request->birth_date,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['birth_date' => 'Invalid birth date format.'])->withInput();
        }
    }

    $common = [
        'client_type' => ['required', 'in:individual,company'],
        'full_name'   => ['required', 'string', 'max:255'],
        'birth_date'  => ['required', 'date'],
        'gender'      => ['required', 'in:male,female,other'],
        'country'     => ['required', 'string', 'max:100'],
        'phone'       => ['required','regex:/^\+[0-9]{8,15}$/'],
        // временно рекомендую убрать dns, но хотя бы лог покажет:
        'email'       => ['required','email','max:255','unique:users,email'],
        'password'    => ['required', 'string', 'min:8', 'confirmed'],
        'instagram'   => ['nullable','string','max:255'],
    ];

    $forIndividual = [
        'workplace'       => ['required', 'string', 'max:255'],
        'identity_photo'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'profile_photo'   => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];

    $forCompany = [
        'workplace'     => ['nullable', 'string', 'max:255'],
        'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        'company_logo'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];

    $rules = array_merge($common, $clientType === 'individual' ? $forIndividual : $forCompany);

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        Log::warning('[REGISTER] validation failed', [
            'client_type' => $clientType,
            'errors' => $validator->errors()->toArray(),
            'workplace' => $request->input('workplace'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);
        return back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated();

    try {
        $profilePath  = $request->file('profile_photo')->store('users/profile', 'public');

        $identityPath = $clientType === 'individual'
            ? $request->file('identity_photo')->store('users/identity', 'public')
            : null;

        $logoPath     = $clientType === 'company'
            ? $request->file('company_logo')->store('users/company_logo', 'public')
            : null;

        Log::info('[REGISTER] files stored', [
            'profile' => $profilePath,
            'identity' => $identityPath,
            'logo' => $logoPath,
        ]);

        $user = User::create([
            'name'                => $data['full_name'],
            'client_type'         => $clientType,
            'full_name'           => $data['full_name'],
            'birth_date'          => $data['birth_date'],
            'gender'              => $data['gender'],
            'country'             => $data['country'],
            'phone'               => $data['phone'],
            'email'               => $data['email'],
            'password'            => Hash::make($data['password']),
            'profile_photo_path'  => $profilePath,
            'identity_photo_path' => $identityPath,
            'company_logo_path'   => $logoPath,
            'workplace'           => $data['workplace'] ?? null,
            'instagram'           => $data['instagram'] ?? null,
            'status'              => 'pending',
        ]);

        Log::info('[REGISTER] user created', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        event(new Registered($user));
        Log::info('[REGISTER] Registered event fired', ['user_id' => $user->id]);

        return redirect()->route('auth.login')
            ->with('registration_success', true)
            ->with('status', 'Your registration is submitted. You will receive an email and SMS after admin approval.');

    } catch (\Throwable $e) {
        Log::error('[REGISTER] exception', [
            'message' => $e->getMessage(),
            'trace' => substr($e->getTraceAsString(), 0, 4000),
        ]);
        return back()->withErrors(['error' => 'Server error. Please try again later.'])->withInput();
    }
}
}
