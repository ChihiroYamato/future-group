<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\Request;
use App\Http\Requests\NotebookRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotebookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NotebookRequest $request)
    {
        $total = Notebook::where('deleted', 0)->count();
        $nextOffset = null;
        $result = [];

        if (isset($request->limit) && (int) $request->limit > 0) {
            $offset = (int) ($request->offset ?? 0);
            $result = Notebook::where('deleted', 0)->offset($offset)->limit($request->limit)->get();
            $nextOffset = (int) $request->limit + $offset;
            $nextOffset = ($total - $nextOffset > 0) ? $nextOffset : null;
        } else {
            $result = Notebook::where('deleted', 0)->get();
        }

        return response()->json([
            'status' => 'success',
            'result' => $result,
            'nextOffset' => $nextOffset,
            'total' => $total,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\NotebookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotebookRequest $request)
    {
        $notebook = Notebook::create($request->validated());

        return response()->json([
            'status' => 'success',
            'result'=> $notebook,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notebook  $notebook
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(Notebook $notebook)
    {
        if (((bool) $notebook->deleted) === true) {
            throw new ModelNotFoundException('No query results for model');
        }

        return response()->json([
            'status' => 'success',
            'result'=> $notebook,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\NotebookRequest  $request
     * @param  \App\Models\Notebook  $notebook
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(NotebookRequest $request, Notebook $notebook)
    {
        if (((bool) $notebook->deleted) === true) {
            throw new ModelNotFoundException('No query results for model');
        }

        $notebook->update($request->validated());

        return response()->json([
            'status' => 'success',
            'result'=> $notebook,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notebook  $notebook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notebook $notebook)
    {
        $notebook->update(['deleted' => true]);

        return response(null, 204);
    }
}
