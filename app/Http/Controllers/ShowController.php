<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Export;
use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\MyResponse;

class ShowController extends Controller
{
    public function showAllOperation(Request $request)
    {
        $dateFormat = "d-m-Y";
        // request may has date called op_date 
        $requestDate = Carbon::createFromFormat($dateFormat, date($dateFormat))->startOfDay();
        // info($requestDate);
        $isNow = true;
        if (isset($request->op_date)) {
            $requestDate = Carbon::createFromFormat($dateFormat, $request->op_date)->startOfDay();
            $isNow = false;
        }
        $nextDay = $requestDate->getTimestampMs() + (24 * 60 * 60 * 1000);
        $nextDayCarbon = Carbon::createFromTimestampMs($nextDay);
        // info($nextDayCarbon);
        // return response()->json([
        //     'sended_date' => $requestDate->timestamp,
        //     'next_date' => $nextDayCarbon->timestamp,
        //     "isNow" => $isNow

        // ], 200);
        $imports = Import::whereBetween(
            "opration_date",
            [$requestDate->toDateString(), $nextDayCarbon->toDateString()]
        )->orderBy('created_at', "DESC")
            ->get()->map->format();
        // DB::enableQueryLog();
        $exports = Export::whereBetween(
            "opration_date",
            [$requestDate->toDateString(), $nextDayCarbon->toDateString()]
        )->orderBy('created_at', "DESC")
            ->get()->map->format();
        // Log::debug(DB::getQueryLog());
        return MyResponse::returnData('data', [
            'imports' => $imports,
            'exports' => $exports
        ]);
    }

    public function showCustomerOperationsById(int $id)
    {
        $customer = Customer::find($id);
        if (!isset($customer)) return MyResponse::returnError('customer id must be in customers table', 200);

        $imports = Import::where(
            "customer_id",
            $id
        )->orderBy('created_at', "DESC")
            ->get()->map->format();
        // DB::enableQueryLog();
        $exports = Export::where(
            "customer_id",
            $id
        )->orderBy('created_at', "DESC")
            ->get()->map->format();
        // Log::debug(DB::getQueryLog());
        return MyResponse::returnData('user_operations', [
            'imports' => $imports,
            'exports' => $exports
        ]);
    }
    public function showCustomerOperationsByName(string $name)
    {
        $customers = Customer::where('name', 'like', "%$name%")->with('imports.customer', 'exports.customer')->get()->map->format2();
        if (count($customers) == 0) return MyResponse::returnError('there is no customer with this name.', 301);
        // Log::debug(DB::getQueryLog());

        return MyResponse::returnData('customers_operations', $customers);
    }
    public function showImportsForCustomer(Request $request){
        return MyResponse::returnData('imports',Import::all());
    }
}
