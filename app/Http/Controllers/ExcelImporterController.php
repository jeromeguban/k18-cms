<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 3600);
use App\Models\Customer;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Imports\CustomerImport;
use App\Imports\QuantityUploader;
use App\Imports\StartingAmountUploader;
use App\Imports\PostingImagesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ViewingDetailImport;
use App\Imports\CustomerAddressesImport;
use App\Imports\CustomerLoginCredentialImport;


class ExcelImporterController extends Controller
{
    public function customer(Request $request) 
    {
       
        Excel::import(new CustomerImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }


    public function customerCredential(Request $request) 
    {
       
        Excel::import(new CustomerLoginCredentialImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function customerAddresses(Request $request) 
    {
       
        Excel::import(new CustomerAddressesImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function productImport(Request $request) 
    {
       
        Excel::import(new ProductImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function uploadImages(Request $request) 
    {
        Excel::import(new PostingImagesImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function uploadUsers(Request $request) 
    {
        Excel::import(new UserImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function updateViewingDetails(Request $request) 
    {
        Excel::import(new ViewingDetailImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }


    public function updateProductQuantities(Request $request) 
    {
        Excel::import(new UserImport, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

     public function quantityUpload(Request $request) 
    {
        Excel::import(new QuantityUploader, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

     public function startingAmount(Request $request) 
    {
        Excel::import(new StartingAmountUploader, $request->file('file'));
        return redirect('/')->with('success', 'All good!');
    }
}
