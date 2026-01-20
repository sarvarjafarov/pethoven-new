<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        // Store in database (optional - create ContactSubmission model if needed)
        // Or send email notification to admin
        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($validated, $fullName) {
                $message->to(config('mail.from.address'))
                    ->subject('Contact Form Submission')
                    ->setBody(
                        "Name: {$fullName}\n" .
                        "Email: {$validated['email']}\n\n" .
                        "Message:\n{$validated['message']}",
                        'text/plain'
                    )
                    ->replyTo($validated['email'], $fullName);
            });
        } catch (\Exception $e) {
            // Log error but don't show to user
            \Illuminate\Support\Facades\Log::error('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->route('contact')
            ->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function faq()
    {
        return view('frontend.pages.faq');
    }
}
