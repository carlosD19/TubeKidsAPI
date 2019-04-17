<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Profile;
use Response;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::where('user_id', $ID_DEL_USUARIO)->get();
        return response()->json(['data' => $profiles], 200);
    }
    
    public function store(ProfileRequest $request)
    {
        $profile = Profile::create($request->all());
        $response = Response::make(json_encode(['data'=>$profile ]), 201)->header('Location', 'http://localhost/api/profiles/'.$profile ->id)->header('Content-Type', 'application/json');
        return $response;
    }

    public function show($profile_id)
    {
        $profile = Profile::find($profile_id);
        if (!$profile) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el profile.'])], 404);
        }
        return response()->json(['status'=>'ok', 'data'=>$profile], 200);
    }

    public function update(ProfileRequest $request)
    {
        $profile = profile::find($request->profile_id)->update($request->all());
        return response()->json(['status'=>'ok','data' => $profile], 200);
    }

    public function destroy($profile_id)
    {
        $profile = Profile::find($profile_id);
        if (!$profile) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el profile.'])], 404);
        }
        $profile->delete();
        return response()->json(null, 204);
    }
}
