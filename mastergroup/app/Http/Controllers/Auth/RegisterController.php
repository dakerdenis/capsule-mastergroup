<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $clientType = $request->input('client_type');
        if (!in_array($clientType, ['individual','company'], true)) {
            abort(422, 'Invalid client type.');
        }

        $common = [
            'client_type' => ['required','in:individual,company'],
            'full_name'   => ['required','string','max:255'],
            'birth_date'  => ['required','date'],
            'gender'      => ['required','in:male,female,other'],
            'country'     => ['required','string','max:100'],
            'phone'       => ['required','string','max:50'],
            'email'       => ['required','email','max:255','unique:users,email'],
            'password'    => ['required','string','min:8','confirmed'],
            'instagram'   => ['nullable','string','max:255'],
        ];

        $forIndividual = [
            'workplace'       => ['required','string','max:255'],
            'identity_photo'  => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'profile_photo'   => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];

        $forCompany = [
            'workplace'     => ['nullable','string','max:255'],
            'profile_photo' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'company_logo'  => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];

        $data = $request->validate(array_merge(
            $common,
            $clientType === 'individual' ? $forIndividual : $forCompany
        ));

        $profilePath  = $request->file('profile_photo')->store('users/profile', 'public');
        $identityPath = $clientType === 'individual'
            ? $request->file('identity_photo')->store('users/identity', 'public')
            : null;
        $logoPath     = $clientType === 'company'
            ? $request->file('company_logo')->store('users/company_logo', 'public')
            : null;

        $user = User::create([
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
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice')->with('status', 'Please verify your email address.');
    }
}
