<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Episodes;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rows = Episodes::withCount('parts')->paginate(10);

        return view('index-general', [
            'rows' => $rows,
            'pageName' => 'Episodes',
            'nextRelName' => 'Parts',
            'nextRelCounter' => 'parts_count',
            'showRouteName' => 'episodes.show'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ep = Episodes::findOrFail($id);
        $rows = $ep->parts()->withCount('items')->paginate(10);

        return view('show-general', [
            'ep' => $ep,
            'rows' => $rows,
            'pageName' => 'Episode: '.$ep->name,
            'relSectionName' => 'Parts',
            'nextRelName' => 'Items',
            'nextRelCounter' => 'items_count',
            'showRouteName' => 'parts.show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
