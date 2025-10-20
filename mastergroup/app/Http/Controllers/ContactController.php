<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactRequestMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'message' => ['required','string','min:5','max:2000'],
        ], [
            'message.required' => 'Message is required.',
            'message.min'      => 'Message is too short.',
            'message.max'      => 'Message is too long.',
        ]);

        $to = 'contact@capsuleppf.com'; // целевой ящик

        try {
            Mail::to($to)->send(new ContactRequestMail(
                user: $user,
                body: $data['message'],
                ip: $request->ip(),
                ua: (string) $request->header('User-Agent')
            ));
        } catch (\Throwable $e) {
            Log::error('Contact form email failed', [
                'user_id' => $user->id ?? null,
                'error'   => $e->getMessage(),
            ]);

            // ответ для ajax
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'message' => 'Failed to send. Try later.'], 500);
            }
            return back()->with('contact_status', 'Failed to send. Try later.')->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'Message sent. We will contact you soon.']);
        }

        return back()->with('contact_status', 'Message sent. We will contact you soon.');
    }
}
