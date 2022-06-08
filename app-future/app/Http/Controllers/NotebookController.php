<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\NotebookRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="Bearer",
 *     name="Authorization"
 * )
 */
class NotebookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\NotebookRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *  path="/v1/notebooks",
     *  summary="Get list of notebooks",
     *  tags={"Notebook"},
     *  @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      description="max result of notebooks",
     *      @OA\Schema(type="integer",)
     *  ),
     *  @OA\Parameter(
     *      name="offset",
     *      in="query",
     *      required=false,
     *      description="offset of result",
     *      @OA\Schema(type="integer",),
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="success"
     *          ),
     *          @OA\Property(
     *              property="result",
     *              type="array",
     *              @OA\Items(
     *                  @OA\AdditionalProperties(
     *                      type="string"
     *                  )
     *              )
     *          ),
     *          @OA\Property(
     *              property="nextOffset",
     *              type="integer",
     *          ),
     *          @OA\Property(
     *              property="total",
     *              type="integer",
     *          ),
     *      )
     *  ),
     * )
     */
    public function index(NotebookRequest $request) : JsonResponse
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
     * @param \App\Http\Requests\NotebookRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\POST(
     *  path="/v1/notebooks",
     *  summary="Store a newly notebook",
     *  tags={"Notebook"},
     *  description="Store a newly notebook",
     *  security={
     *      {"Bearer": {"write:notebook", "read:notebook"}}
     *  },
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="name",
     *              type="string",
     *              example="Alex"
     *          ),
     *          @OA\Property(
     *              property="phone",
     *              type="string",
     *              example="+7(999)666-55-44"
     *          ),
     *          @OA\Property(
     *              property="email",
     *              type="string",
     *              example="testing@test.com"
     *          ),
     *          @OA\Property(
     *              property="birth_date",
     *              type="date",
     *              example="1999-12-12"
     *          ),
     *          @OA\Property(
     *              property="company",
     *              type="string",
     *              example="Core"
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="success"
     *          ),
     *          @OA\Property(
     *              property="result",
     *              type="object",
     *              @OA\AdditionalProperties(
     *                  type="string"
     *              )
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthorized user",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Incorrect data",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  )
     * )
     */
    public function store(NotebookRequest $request) : JsonResponse
    {
        $notebook = Notebook::create($request->validated());

        return response()->json([
            'status' => 'success',
            'result'=> Notebook::find($notebook->id),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Notebook $notebook
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @OA\Get(
     *  path="/v1/notebooks/{notebook_id}",
     *  summary="Get notebook by id",
     *  tags={"Notebook"},
     *  description="Get notebook by id",
     *  @OA\Parameter(
     *      name="notebook_id",
     *      in="path",
     *      description="Notebook id",
     *      required=true,
     *      @OA\Schema(type="integer"),
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="success"
     *          ),
     *          @OA\Property(
     *              property="result",
     *              type="object",
     *              @OA\AdditionalProperties(
     *                  type="string"
     *              )
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="Notebook is not found",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  )
     * )
     */
    public function show(Notebook $notebook) : JsonResponse
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
     * @param \App\Http\Requests\NotebookRequest $request
     * @param \App\Models\Notebook $notebook
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @OA\PUT(
     *  path="/v1/notebooks/{notebook_id}",
     *  summary="Update notebook by id",
     *  tags={"Notebook"},
     *  description="Update notebook by id",
     *  security={
     *      {"Bearer": {"write:notebook", "read:notebook"}}
     *  },
     *  @OA\Parameter(
     *      name="notebook_id",
     *      in="path",
     *      description="Notebook id",
     *      required=true,
     *      @OA\Schema(type="integer"),
     *  ),
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="name",
     *              type="string",
     *              example="Alex"
     *          ),
     *          @OA\Property(
     *              property="phone",
     *              type="string",
     *              example="+7(999)666-55-44"
     *          ),
     *          @OA\Property(
     *              property="email",
     *              type="string",
     *              example="testing@test.com"
     *          ),
     *          @OA\Property(
     *              property="birth_date",
     *              type="date",
     *              example="1999-12-12"
     *          ),
     *          @OA\Property(
     *              property="company",
     *              type="string",
     *              example="Core"
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="successful operation",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="success"
     *          ),
     *          @OA\Property(
     *              property="result",
     *              type="object",
     *              @OA\AdditionalProperties(
     *                  type="string"
     *              )
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthorized user",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="Notebook is not found",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Incorrect data",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  )
     * )
     */
    public function update(NotebookRequest $request, Notebook $notebook) : JsonResponse
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
     *
     * @OA\DELETE(
     *  path="/v1/notebooks/{notebook_id}",
     *  summary="Delete notebook by id",
     *  tags={"Notebook"},
     *  description="Delete notebook by id",
     *  security={
     *      {"Bearer": {"write:notebook", "read:notebook"}}
     *  },
     *  @OA\Parameter(
     *      name="notebook_id",
     *      in="path",
     *      description="Notebook id",
     *      required=true,
     *      @OA\Schema(type="integer"),
     *  ),
     *  @OA\Response(
     *      response=204,
     *      description="Success delete"
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthorized user",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="status",
     *              type="string",
     *              example="failure"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          ),
     *      )
     *  )
     * )
     */
    public function destroy(Notebook $notebook) : Response
    {
        $notebook->update(['deleted' => true]);

        return response(null, 204);
    }
}
