<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Uploads;
use Illuminate\Http\JsonResponse;

class MediaLibraryController extends Controller
{
    /**
     * Mengembalikan daftar gambar yang pernah diunggah ke pustaka media.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'items' => Uploads::listImages(),
        ]);
    }
}
