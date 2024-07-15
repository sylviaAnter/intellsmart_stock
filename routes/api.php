
<?php

use App\Models\StockSubCategory;
use App\Models\StockCategory;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// <!-- This code is defining routes for an API -->
//<!-- Routes are like addresses that tell the application what to do when a specific URL is accessed. This API has routes to get information about users, stock items, and stock sub-categories. -->
//

//When accessed, it returns information about the currently authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Stock Items Route
Route::get('/stock-items', function (Request $request) { //Define a route that listens for GET requests at /stock-items.
    $q = $request->get('q'); // Get the query parameter q from the request (used for searching).
    $Company_id = $request->get('company_id');
    if ($Company_id == null) {
        return response()->json([
            'data' => [], //If company_id is missing, return an empty data array with a 400 error code.
        ], 400);
    }
    $sub_categories =
        StockItem::where('company_id', $Company_id)
        ->where('name', 'like', "%$q%") //->where('name', 'like', "%$q%"): Filter the items to include only those whose names match the search query.
        ->orderBy('name', 'asc')
        ->limit(20)
        ->get();
    $data = [];
    foreach ($sub_categories as $sub_category) {
        // dd($sub_categories->stockCategory);
        $data[] = [
            'id' => $sub_category->id,
            'text' => $sub_category->sku . " " . $sub_category->name_text,
        ];
    }
    return response()->json([
        'data' => $data, //Return the data array as a JSON response.
    ]);
});
// rout for stock-categories
Route::get('/stock-sub-categories', function (Request $request) {
    $q = $request->get('q');
    $Company_id = $request->get('company_id');
    if ($Company_id == null) {
        return response()->json([
            'data' => [],
        ], 400);
    }
    $sub_categories =
        StockSubCategory::where('company_id', $Company_id)
        ->where('name', 'like', "%$q%")
        ->orderBy('name', 'asc')
        ->limit(20)
        ->get();
    $data = [];
    foreach ($sub_categories as $sub_category) {
        // dd($sub_categories->stockCategory);
        $data[] = [
            'id' => $sub_category->id,
            'text' => $sub_category->name_text . " ( " . $sub_category->measurement_unit . " ) ",
        ];
    }
    return response()->json([
        'data' => $data,
    ]);
});
