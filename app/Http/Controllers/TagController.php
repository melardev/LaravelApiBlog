<?php

namespace App\Http\Controllers;


use App\Models\Tag;

class TagController extends Controller
{
    public function __construct() {
        $this->middleware('role:admin')->except(['index', 'show']);
    }

    public function index()// : View
    {
        $tags = Tag::all();
        return view('tags.index')->withTags($tags);
    }


    public function show(Tag $tag)//: View
    {
        return view('tags.show')->withTag($tag);
    }

}