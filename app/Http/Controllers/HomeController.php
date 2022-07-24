<?php

namespace App\Http\Controllers;

use Storage;
use App\Helpers\Paths;
use App\Models\ResourceModel;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(){
        $page = "home";
        $data = [
            'page' => $page
        ];
        return view('home', $data);
    }

    public function list(){
        $resources = ResourceModel::all();
        return response()->json([
            'resources' => $resources
        ], 200);
    }

    public function download($resourceId){
        $resource = ResourceModel::where('id', $resourceId)->first();
        if(!$resource){
            abort(404);
        }

        $file = Storage::path(Paths::PDF_PATH. $resource->file_name);
        return \Response::download($file);
    }
}
