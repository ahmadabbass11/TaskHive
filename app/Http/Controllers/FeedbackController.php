<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display feedback.
     * Admins see all feedback; non-admins see only their own.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin can see all feedback
            $feedbacks = Feedback::with('user')->latest()->get();
        } else {
            // Regular user sees only their own feedback
            $feedbacks = $user->feedbacks()->with('user')->latest()->get();
        }

        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Store new feedback from the currently authenticated user.
     */
 
   public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $apiKey   = config('services.huggingface.api_key');
    $modelUrl = 'https://api-inference.huggingface.co/models/unitary/toxic-bert';
    $client   = new \GuzzleHttp\Client();

    try {
        $response = $client->post($modelUrl, [
            'verify'  => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => ['inputs' => $request->input('content')],
        ]);

        $result       = json_decode($response->getBody(), true);
        \Log::info('Moderation result: ' . print_r($result, true));
        $labels       = $result[0] ?? [];
        $blockedLabels= ['toxic','severe_toxic','obscene','insult','threat','identity_hate'];

        $isAppropriate = true;
        foreach ($labels as $labelData) {
            if (in_array($labelData['label'], $blockedLabels) 
                && $labelData['score'] > 0.7
            ) {
                $isAppropriate = false;
                break;
            }
        }
    } catch (\Exception $e) {
        \Log::error('Moderation API error: ' . $e->getMessage());
        $isAppropriate = true;
    }

    if (! $isAppropriate) {
        // use a flash message so you can display it explicitly
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Your feedback contains content that is not allowed. Please adjust your submission.');
    }

    // Save the feedback
    auth()->user()->feedbacks()->create([
        'content' => $request->input('content'),
    ]);

    return redirect()->route('feedback.index')
                     ->with('success', 'Feedback submitted successfully!');
}

}
