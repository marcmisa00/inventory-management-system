<?php

namespace App\Http\Controllers\Api;
use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetController extends Controller
{
   public function index()
{
    $assets = Asset::paginate(10);

    return response()->json([
        'success' => true,
        'message' => 'Assets retrieved successfully.',
        'data' => $assets
    ]);
}
}
