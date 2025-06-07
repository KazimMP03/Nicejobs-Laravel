<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Exibe os prestadores mais bem avaliados para CustomUser.
     */
    public function index()
    {
        $topProviders = Provider::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(6)
            ->get();

        // Use a view específica de custom_users
        return view('custom_users.home', compact('topProviders'));
    }
}
