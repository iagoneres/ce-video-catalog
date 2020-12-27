<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreStoreRequest;
use App\Http\Requests\GenreUpdateRequest;
use App\Models\Genre;

class GenreController extends Controller
{

    public function index()
    {
        return Genre::all();
    }

    public function store(GenreStoreRequest $request)
    {
        return Genre::create($request->all());
    }

    public function show(Genre $genre)
    {
        return $genre;
    }
    
    public function update(GenreUpdateRequest $request, Genre $genre)
    {
        $genre->update($request->all());
        return $genre;
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->noContent();
    }
}
