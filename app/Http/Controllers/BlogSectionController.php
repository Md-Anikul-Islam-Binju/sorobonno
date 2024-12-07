<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class BlogSectionController extends Controller
{
    public function blog()
    {
        $siteSettings = SiteSetting::latest()->first();
        return inertia('Blog',compact('siteSettings'));
    }
}
