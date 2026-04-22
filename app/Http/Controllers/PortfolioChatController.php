<?php

namespace App\Http\Controllers;

use App\Services\PortfolioChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioChatController extends Controller
{
    public function __invoke(Request $request, PortfolioChatbotService $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        return response()->json($chatbot->respond($validated['message']));
    }
}
