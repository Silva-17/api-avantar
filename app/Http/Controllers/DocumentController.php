<?php

namespace App\Http\Controllers;

use App\Models\QuoteDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Handle a request to download a protected document.
     *
     * @param Request $request
     * @param QuoteDocument $document
     * @return StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function download(Request $request, QuoteDocument $document)
    {
        if (!$this->authorizeView($request, $document)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!Storage::disk('public')->exists($document->caminho_arquivo)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::disk('public')->download($document->caminho_arquivo, $document->nome_original);
    }

    /**
     * Handle a request to view a protected document inline.
     *
     * @param Request $request
     * @param QuoteDocument $document
     * @return StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function view(Request $request, QuoteDocument $document)
    {
        if (!$this->authorizeView($request, $document)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!Storage::disk('public')->exists($document->caminho_arquivo)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        $path = $document->caminho_arquivo;
        $mimeType = Storage::disk('public')->mimeType($path);

        return Storage::disk('public')->response($path, $document->nome_original, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->nome_original . '"',
        ]);
    }

    /**
     * Check if the user is authorized to view the document.
     *
     * @param Request $request
     * @param QuoteDocument $document
     * @return bool
     */
    private function authorizeView(Request $request, QuoteDocument $document): bool
    {
        $user = $request->user();
        $quote = $document->quote;

        return $user->role === 'admin' || $quote->user_id === $user->id;
    }
}
