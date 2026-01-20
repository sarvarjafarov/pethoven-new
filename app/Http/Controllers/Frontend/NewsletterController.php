<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid email address.'
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // Check if already subscribed
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->isActive()) {
                $message = 'You are already subscribed to our newsletter!';

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }

                return back()->with('info', $message);
            }

            // Resubscribe if previously unsubscribed
            $subscriber->update([
                'unsubscribed_at' => null,
                'is_verified' => true,
                'subscribed_at' => now(),
            ]);
        } else {
            // Create new subscriber (instant verification for now)
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'is_verified' => true,
                'subscribed_at' => now(),
            ]);
        }

        $message = 'Thank you for subscribing to our newsletter!';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Email not found in our database.');
        }

        if ($subscriber->unsubscribed_at) {
            return redirect()->route('home')->with('info', 'You are already unsubscribed.');
        }

        $subscriber->unsubscribe();

        return redirect()->route('home')->with('success', 'You have been successfully unsubscribed from our newsletter.');
    }
}
