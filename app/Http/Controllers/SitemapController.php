<?php

namespace App\Http\Controllers;

use App\Repositories\MerchantRepository;
use function compact;

class SitemapController extends Controller
{
    public function index()
    {
        $merchants = MerchantRepository::getList();
        return view('merchants', compact('merchants'));
    }
}
