<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Midjourney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MidjourneyController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function webhook(Request $request)
    {
        Midjourney::webhook($request);

        return response()->json([
            'data' => 'success',
        ]);
    }
}
