<?php

namespace App\Http\Controllers;

use App\Repositories\MerchantRepository;
use function compact;
use function dd;
use function view;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = MerchantRepository::getCategories(10);

        return view('welcome', compact('categories'));
    }
}
