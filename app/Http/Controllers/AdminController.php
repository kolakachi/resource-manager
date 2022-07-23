<?php

namespace App\Http\Controllers;

use Exception, Log;
use App\Models\ResourceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    private $rules = [
        'title' => 'required',
        'type' => 'required',
    ];

    public function index(){
        return view('admin');
    }

    public function list(){
        $resources = ResourceModel::all();
        return response()->json([
            'resources' => $resources
        ], 200);
    }

    public function store(Request $request){
        try {
            switch ($request->type) {
                case 'link':
                    return $this->storeLink($request);
                    break;
                
                default:
                    $message = "Unable to complete request.";
                    return response()->json(['message' => $message], 400);
                    break;
            }
        }catch (Exception $error) {
            Log::info('AdminController@add error message: ' . $error->getMessage());
            $message = "Unable to complete request.";
            return response()->json(['message' => $message], 500);
        }
    }

    private function storeLink($request){
        $this->rules['link'] = 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'there is been an error', 'error message' => $validator->errors()]);
        }

        $resource = new ResourceModel();
        $resource->title = $request->title;
        $resource->type = $request->type;
        $resource->link = $request->link;
        $resource->open_in_new_tab = ($request->open_in_new_tab == 'true');
        $resource->save();
        return response()->json([
            'message' => 'resource saved',
            'resource' => $resource
        ], 200);

    }
}
