<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCustomerRequest;
use App\Http\Requests\ExportRequest;
use App\Models\Customer;
use App\Models\Export;
use Illuminate\Http\Request;
use App\Traits\MyResponse;
use App\Http\Requests\importRequest;
use App\Http\Requests\RemoveCustomerRequest;
use App\Models\Import;
use Carbon\Carbon;

class CustomerController extends Controller
{
    use MyResponse;
    public function addCustomer(AddCustomerRequest $request)
    {
        // request [ name , phone ]
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return $this->returnData("customer", $customer, "Customer Added Successfully.");
    }
    public function removeCustomer(RemoveCustomerRequest $request)
    {
        // request [ customer_id  ]
        $customer = Customer::find($request->customer_id);
        $customer->delete();
        return $this->returnMessage("customer with this id has been removed.");
    }
    public function getAllCustomers()
    {
        return MyResponse::returnData('customers', Customer::all());
    }
    public function addExport(ExportRequest $request)
    {
       
        $export = Export::create($request->all());
        if (!isset($export)) {
            return $this->returnError("حصل خطأ ما يرجى المعاودة مرة اخرى", 300);
        }
        return $this->returnData("export", $export, "export added successfully.");
    }
    public function addImport(importRequest $request)
    {
        $import = Import::create($request->all());
        if (!isset($import)) {
            return $this->returnError("حصل خطأ ما يرجى المعاودة مرة اخرى", 309);
        }
        return $this->returnData("import", $import, "import added successfully.");
    }
}
