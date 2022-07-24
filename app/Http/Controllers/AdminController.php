<?php

namespace App\Http\Controllers;

use App\Helpers\Paths;
use Exception, Log, Storage, File;
use App\Models\ResourceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;

class AdminController extends Controller
{
    private $rules = [
        'title' => 'required',
        'type' => 'required',
    ];

    public function index(){
        $page = "admin";
        $data = [
            'page' => $page
        ];
        return view('admin', $data);
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
                case 'html':
                    return $this->storeHTML($request);
                    break;
                case 'pdf':
                    return $this->storePDF($request);
                    break;
                
                default:
                    $message = "Unable to complete request.";
                    return response()->json(['message' => $message], 400);
                    break;
            }
        }catch (Exception $error) {
            Log::info('AdminController@add error messages: ' . $error->getMessage());
            $message = "Unable to complete request.";
            return response()->json(['message' => $message], 500);
        }
    }

    private function storeLink($request){
        $this->rules['link'] = 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
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

    private function storeHTML($request){
        $this->rules['description'] = 'required';
        $this->rules['html'] = 'required';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
        }

        $resource = new ResourceModel();
        $resource->title = $request->title;
        $resource->type = $request->type;
        $resource->description = $request->description;
        $resource->html = $request->html;
        $resource->save();
        return response()->json([
            'message' => 'resource saved',
            'resource' => $resource
        ], 200);

    }

    private function storePDF($request){
        $this->rules['file_upload'] = 'required|mimes:pdf';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
        }

        $resource = new ResourceModel();
        $resource->title = $request->title;
        $resource->type = $request->type;
        $resource->file_name = $this->storeAndGetFileName($request->file_upload);
        $resource->save();
        return response()->json([
            'message' => 'resource saved',
            'resource' => $resource
        ], 200);

    }

    private function storeAndGetFileName($file){
        $fileName = str_replace(' ', '_',$file->getClientOriginalName());
        $mediaPath = Paths::PDF_PATH. $fileName;
        Storage::put($mediaPath, File::get($file));

        return $fileName;
    }

    public function delete(Request $request){
        try{
            $resource = ResourceModel::where('id', $request->id)->first();
            if(!$resource){
                return response()->json([
                    'status' => 'error',
                    'message' => "Unable to complete request. resource not found",
                ], 404);
            }
            $fileName = Paths::PDF_PATH. $resource->file_name;
            if(Storage::exists($fileName)){
                Storage::delete($fileName);
            }
            $resource->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Resource deleted successfully",
            ]);

        }catch(\Exception $error){
            \Log::info($error->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => "Unable to complete request.",
            ], 500);
        }
    }

    public function update(Request $request){
        try {
            switch ($request->type) {
                case 'link':
                    return $this->updateLink($request);
                    break;
                case 'html':
                    return $this->updateHTML($request);
                    break;
                case 'pdf':
                    return $this->updatePDF($request);
                    break;
                
                default:
                    $message = "Unable to complete request.";
                    return response()->json(['message' => $message], 400);
                    break;
            }
        }catch (Exception $error) {
            Log::info('AdminController@add error messages: ' . $error->getMessage());
            $message = "Unable to complete request.";
            return response()->json(['message' => $message], 500);
        }
    }

    private function updateLink($request){
        $this->rules['link'] = 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
        }

        $resource = ResourceModel::where('id', $request->id)->first();
        if(!$resource){
            return response()->json([
                'status' => 'error',
                'message' => "Unable to complete request. resource not found",
            ], 404);
        }
        $fileName = Paths::PDF_PATH. $resource->file_name;
        if(Storage::exists($fileName)){
            Storage::delete($fileName);
        }
        $resource->title = $request->title;
        $resource->type = $request->type;
        $resource->link = $request->link;
        $resource->file_name = '';
        $resource->description = '';
        $resource->html = '';
        $resource->open_in_new_tab = ($request->open_in_new_tab == 'true');
        $resource->save();

        
        return response()->json([
            'message' => 'resource updated',
            'resource' => $resource
        ], 200);

    }

    private function updateHTML($request){
        $this->rules['description'] = 'required';
        $this->rules['html'] = 'required';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
        }

        $resource = ResourceModel::where('id', $request->id)->first();
        if(!$resource){
            return response()->json([
                'status' => 'error',
                'message' => "Unable to complete request. resource not found",
            ], 404);
        }
        $fileName = Paths::PDF_PATH. $resource->file_name;
        if(Storage::exists($fileName)){
            Storage::delete($fileName);
        }
        
        $resource->title = $request->title;
        $resource->type = $request->type;
        $resource->description = $request->description;
        $resource->html = $request->html;

        $resource->link = '';
        $resource->file_name = '';
        $resource->save();
        return response()->json([
            'message' => 'resource updated',
            'resource' => $resource
        ], 200);

    }

    private function updatePDF($request){
        $this->rules['file_upload'] = 'sometimes|mimes:pdf';
        $validator = Validator::make($request->all() , $this->rules); 
        if ($validator->fails()) {
            return response()->json(['message' => 'unable to complete request', 'error messages' => $validator->errors()]);
        }

        $resource = ResourceModel::where('id', $request->id)->first();
        if(!$resource){
            return response()->json([
                'status' => 'error',
                'message' => "Unable to complete request. resource not found",
            ], 404);
        }
        $fileName = Paths::PDF_PATH. $resource->file_name;
        if($request->hasFile('file_upload')){
            if(Storage::exists($fileName)){
                Storage::delete($fileName);
            }
            $resource->file_name = $this->storeAndGetFileName($request->file_upload);
        }
       
        $resource->title = $request->title;
        $resource->type = $request->type;
        
        $resource->description = '';
        $resource->html = '';
        $resource->link = '';
        $resource->save();
        return response()->json([
            'message' => 'resource updated',
            'resource' => $resource
        ], 200);

    }
}
