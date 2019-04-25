<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Profile;
use Response;

class ProfileController extends Controller
{
    /**
     * Method to get the list of profiles
     * @return profile´s list
     */
    public function index()
    {
        $token    = request()->bearerToken();
        $user     = auth()->user();
        $profiles = Profile::where('user_id', $user->id)->get();
        return $profiles;
    }
    
    /**
     * Method to create a new profile
     * @param request with profile data
     * @return response with the profile created
     */
    public function store(ProfileRequest $request)
    {
        $user = auth()->user();
        $data = array(
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'age'       => $request->age,
            'pin'       => $request->pin,
            'user_id'   => $user->id,
        );
        $profile  = Profile::create($data);
        $response = Response::make(json_encode(['data'=>$profile ]), 201)->header('Location', 'http://localhost/api/profiles/'.$profile ->id)->header('Content-Type', 'application/json');
        return $response;
    }
    /**
     * Method to get a specific profile
     * @param profile id
     * @return response with the profile
     */
    public function show($profile_id)
    {
        $profile = Profile::find($profile_id);
        if (!$profile) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el profile.'])], 404);
        }
        return response()->json(['status'=>'ok', 'data'=>$profile], 200);
    }
    /**
     * Method to update a specific profile
     * @param request with profile data
     * @return response with the profile updated
     */
    public function update(ProfileRequest $request)
    {
        $profile = profile::find($request->id)->update($request->all());
        return response()->json(['status'=>'ok','data' => $profile], 200);
    }
    /**
     * Method to destroy a specific profile
     * @param profile id
     * @return response with status 204
     */
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
