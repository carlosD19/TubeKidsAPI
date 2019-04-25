<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VideoRequest;
use Illuminate\Support\Facades\Storage;
use App\Video;
use Response;

class VideoController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $videos = Video::where('user_id', $user->id)->get();
        return response()->json(['data' => $videos], 200);
    }
    
    public function store(VideoRequest $request)
    {
        $data   = $request->all();
        $user   = auth()->user();
        if ($request->file('video')) {
            $path = Storage::disk('public')->put('videos', $request->file('video'));
            $data['path'] = asset($path);
        }
        $video = array(
            'name'    => $data['name'],
            'type'    => $data['type'],
            'path'    => $data['path'],
            'user_id' => $user->id,
        );

        $video = Video::create($video);
        $response = Response::make(json_encode(['data'=>$video ]), 201)->header('Location', 'http://localhost/api/videos/'.$video ->id)->header('Content-Type', 'application/json');
        return $response;
    }
    
    public function show($video_id)
    {
        $video = Video::find($video_id);
        if (!$video) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el video.'])], 404);
        }
        return response()->json(['status'=>'ok', 'data'=>$video], 200);
    }

    public function update(VideoRequest $request)
    {
        $video = Video::find($request->video_id)->update($request->all());
        return response()->json(['status'=>'ok','data' => $video], 200);
    }

    public function destroy($video_id)
    {
        $video = Video::find($video_id);
        if (!$video) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el video.'])], 404);
        }
        $video->delete();
        return response()->json(null, 204);
    }
}
