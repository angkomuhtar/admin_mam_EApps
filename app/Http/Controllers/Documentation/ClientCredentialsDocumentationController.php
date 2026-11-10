<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\OpenApi\ClientCredentialsSpecification;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ClientCredentialsDocumentationController extends Controller
{
    public function index(): View
    {
        return view('documentation.client-credentials', [
            'baseUrl' => rtrim(url('/'), '/'),
        ]);
    }

    public function specification(ClientCredentialsSpecification $specification): JsonResponse
    {
        return response()->json(
            $specification->build(rtrim(url('/'), '/')),
            options: JSON_UNESCAPED_SLASHES,
        );
    }
}
