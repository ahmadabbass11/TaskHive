<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class CallController extends Controller
{
    public function generateToken(Request $request)
    {
        $request->validate([
            'room' => 'required|string|max:255',
        ]);

        \Log::info('Generating Twilio Token for user: ' . auth()->user()->id);

        $identity = auth()->user()->id;
        $room = $request->input('room');

        $token = new AccessToken(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_API_KEY'),
            env('TWILIO_API_SECRET'),
            3600,
            $identity
        );

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($room);

        $token->addGrant($videoGrant);

        return response()->json([
            'token' => $token->toJWT(),
            'room' => $room,
            'identity' => $identity,
        ]);
    }
}
