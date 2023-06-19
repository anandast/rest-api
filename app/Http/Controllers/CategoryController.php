<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        $countRow = $category->count();
        if ($countRow > 0) {
            //return response()->json(['data' => $ministers]);
            $countRow = $category->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], CategoryResource::collection($category));
        } else {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $data = Category::create($request->all());
        return ApiFormatter::format(200, true, 'Data created successfully!', new CategoryResource($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $category = Category::find($id);

        if ($category) {
            $category->name = $request->name;
            $category->update();
            return ApiFormatter::format(200, true, 'Data updated successfully!', [new CategoryResource($category)]);
        } else {
            return ApiFormatter::format(404, false, 'Data not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $showDeletedData = Category::findOrFail($id);
            $category = Category::find($id);

            if ($category) {
                $category->delete();
                return ApiFormatter::format(200, true, ['Delete succesfully'], [new CategoryResource($showDeletedData)]);
            } else {
                return ApiFormatter::format(404, false, 'Data not found');
            }

            //return new MinisterDetailResource($minister);
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, ['Data was deleted! No data found to delete']);
        }
    }
}
