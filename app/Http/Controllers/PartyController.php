<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Party;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PartyResource;
use Illuminate\Support\Facades\Validator;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $party = Party::all();
        $countRow = $party->count();
        if ($countRow > 0) {
            //return response()->json(['data' => $ministers]);
            $countRow = $party->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], PartyResource::collection($party));
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
        $data = Party::create($request->all());
        return ApiFormatter::format(200, true, 'Data created successfully!', new PartyResource($data));
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
        $party = Party::find($id);
        if ($party) {
            $party->name = $request->name;
            $party->update();
            return ApiFormatter::format(200, true, 'Data updated successfully!', new PartyResource($party));
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
            $showDeletedData = Party::findOrFail($id);
            $party = Party::find($id);

            if ($party) {
                $party->delete();
                return ApiFormatter::format(200, true, ['Delete succesfully'], [new PartyResource($showDeletedData)]);
            } else {
                return ApiFormatter::format(404, false, 'Data not found');
            }

            //return new MinisterDetailResource($minister);
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, ['Data was deleted! No data found to delete']);
        }
    }
}
