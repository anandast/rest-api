<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Party;
use App\Models\Status;
use App\Models\Category;
use App\Models\Minister;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MinisterResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MinisterDetailResource;

class MinisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ministers = Minister::all();
        $countRow = $ministers->count();
        if ($countRow > 0) {
            //return response()->json(['data' => $ministers]);
            $countRow = $ministers->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers->loadMissing('status:id,name')->loadMissing('ministry:id,name')->loadMissing('party:id,name')->loadMissing('category:id,name')));
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
            'position' => 'required|max:255',
            'name' => 'required|max:255',
            'file' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'start_date' => 'required|date',
            'end_date' => '',
            'status_id' => 'required|integer',
            'party_id' => 'required|integer',
            'ministry_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $image = null;
        if ($request->file) {
            $filename = $filename = $request->file->getClientOriginalName();
            $image = $filename;
            Storage::putFileAs('public', $request->file, $image);
        }
        $request['image'] = $image;
        $data = Minister::create($request->all());
        return ApiFormatter::format(200, true, 'Data created successfully!', new MinisterDetailResource($data->loadMissing('status:id,name')->loadMissing('ministry:id,name')->loadMissing('party:id,name')->loadMissing('category:id,name')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Minister  $minister
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if ($id < 1) {
                return ApiFormatter::format(404, false, "ID must be greater than 0");
            } else {
                $minister = Minister::with('status:id,code,name')->with('party:id,name')->with('ministry:id,name')->findOrFail($id);
                $countRow = Minister::where('id', '=', $id)->count();
                return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], [new MinisterDetailResource($minister)]);
            }
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }

    public function showYear($year)
    {
        $minister = Minister::with('status:id,name')->with('party:id,name')->with('ministry:id,name')->whereYear('start_date', '=', $year)->get();
        $countRow = Minister::whereYear('start_date', '=', $year)->count();
        return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($minister));
    }
    public function showBetweenYear($start, $end)
    {
        $minister = Minister::with('status:id,name')->with('party:id,name')->with('ministry:id,name')->whereYear('start_date', '=', $start)->whereYear('end_date', '=', $end)->get();
        $countRow = $minister->count();
        return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($minister));
    }

    public function showActive($active)
    {
        try {
            if ($active == 'Y' || $active == 'N' || $active == 'y' || $active == 'n') {
                $status = Status::where('code', '=', $active)->get();
                $ministers = Minister::with('status:id,name')->whereBelongsTo($status)->get();
                $countRow = $ministers->count();
                return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers));
            } else {
                return ApiFormatter::format(404, false, "Active field must be Y/N");
            }
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }
    public function showParty($party)
    {
        try {
            if ($party < 1) {
                return ApiFormatter::format(404, false, "Party ID must be greater than 0");
            }
            $partyId = Party::where('id', '=', $party)->get();
            $ministers = Minister::with('party:id,name')->whereBelongsTo($partyId)->get();
            $countRow = $ministers->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers));
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }
    public function showCategory($category)
    {
        try {
            if ($category < 1) {
                return ApiFormatter::format(404, false, "Category ID must be greater than 0");
            }
            $categoryId = Category::where('id', '=', $category)->get();
            $ministers = Minister::with('ministry:id,name')->whereBelongsTo($categoryId)->get();
            $countRow = $ministers->count();
            return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers));
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }

    public function showActiveCategory($category, $active)
    {
        try {
            if ($category > 0 && ($active == 'Y' || $active == 'N' || $active == 'y' || $active == 'n')) {
                $categoryId = Category::where('id', '=', $category)->get();
                $status = Status::where('code', '=', $active)->get();
                $ministers = Minister::with('status:id,name')->with('ministry:id,name')->whereBelongsTo($categoryId)->whereBelongsTo($status)->get();
                $countRow = $ministers->count();
                return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers));
            } else {
                return ApiFormatter::format(404, false, "Category ID must be greater than 0 && Active field must be Y/N");
            }
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }

    public function showActiveParty($party, $active)
    {
        try {
            if ($party > 0 && ($active == 'Y' || $active == 'N' || $active == 'y' || $active == 'n')) {
                $partyId = Party::where('id', '=', $party)->get();
                $status = Status::where('code', '=', $active)->get();
                $ministers = Minister::with('status:id,name')->with('party:id,name')->whereBelongsTo($partyId)->whereBelongsTo($status)->get();
                $countRow = $ministers->count();
                return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow],  MinisterDetailResource::collection($ministers));
            } else {
                return ApiFormatter::format(404, false, "Party ID must be greater than 0 && Active field must be Y/N");
            }
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, "Data not found");
        }
    }

    public function page()
    {
        $ministers = Minister::with('status:id,name')->with('party:id,name')->with('category:id,name')->with('ministry:id,name')->paginate(10);
        $page = $ministers->currentPage();
        return ApiFormatter::format(200, true, ['current page: ' . $page], MinisterDetailResource::collection($ministers));
    }

    public function search($search)
    {
        $ministers = Minister::where('name', 'LIKE', '%' . $search . '%')->get();
        $countRow = $ministers->count();
        return ApiFormatter::format(200, true, ['rows_returned: ' . $countRow], MinisterDetailResource::collection($ministers));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Minister  $minister
     * @return \Illuminate\Http\Response
     */
    public function edit(Minister $minister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Minister  $minister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required|max:255',
            'name' => 'required|max:255',
            'file' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'start_date' => 'required|date',
            'end_date' => '',
            'status_id' => 'required|integer',
            'party_id' => 'required|integer',
            'ministry_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return ApiFormatter::format(400, false, $validator->errors());
        }
        $minister = Minister::find($id);

        if ($minister) {
            $minister->position = $request->position;
            $minister->name = $request->name;
            $image = null;
            if ($request->file) {
                $filename = $request->file->getClientOriginalName();
                $image = $filename;
                Storage::putFileAs('images/update', $request->file, $image);
            }
            $minister->image = $request['image'] = $image;
            $minister->start_date = $request->start_date;
            $minister->end_date = $request->end_date;
            $minister->status_id = $request->status_id;
            $minister->party_id = $request->party_id;
            $minister->ministry_id = $request->ministry_id;
            $minister->category_id = $request->category_id;
            $minister->update();
            return ApiFormatter::format(200, true, 'Data updated successfully!', new MinisterDetailResource($minister->loadMissing('status:id,name')->loadMissing('ministry:id,name')->loadMissing('party:id,name')->loadMissing('category:id,name')));
        } else {
            return ApiFormatter::format(404, false, 'Data not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Minister  $minister
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $showDeletedData = Minister::with('status:id,name')->with('party:id,name')->with('ministry:id,name')->findOrFail($id);
            $minister = Minister::find($id);

            if ($minister) {
                $minister->delete();
                return ApiFormatter::format(200, true, ['Delete succesfully'], [new MinisterDetailResource($showDeletedData)]);
            } else {
                return ApiFormatter::format(404, false, 'Data not found');
            }

            //return new MinisterDetailResource($minister);
        } catch (Exception $e) {
            return ApiFormatter::format(404, false, ['Data was deleted! No data found to delete']);
        }
    }
}
