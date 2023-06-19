<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Resources\PartyResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StatusResource;
use App\Models\Party;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Status::all();
        $countRow = $status->count();
        if ($countRow > 0) {
            //return response()->json(['data' => $ministers]);
            $countRow = $status->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], StatusResource::collection($status));
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
            'code' => 'required|max:1',
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $data = Status::create($request->all());
        return ApiFormatter::format(200, true, 'Data created successfully!', new StatusResource($data));
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
            'code' => 'required|max:1',
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $status = Status::find($id);
        if ($status) {
            $status->code = $request->code;
            $status->name = $request->name;
            $status->update();
            return ApiFormatter::format(200, true, 'Data updated successfully!', new StatusResource($status));
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
            $showDeletedData = Status::findOrFail($id);
            $status = Status::find($id);
            if ($status) {
                $status->delete();
                return ApiFormatter::format(200, true, ['Delete succesfully'], [new StatusResource($showDeletedData)]);
            } else {
                return ApiFormatter::format(404, false, 'Data not found');
            }
            //return new MinisterDetailResource($minister);
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, ['Data was deleted! No data found to delete']);
        }
    }
}
