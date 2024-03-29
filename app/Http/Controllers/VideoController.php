<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VideoRequest;
use Illuminate\Support\Facades\Storage;
use App\Video;
use Response;

class VideoController extends Controller
{
    /**
     * Method to get list of videos
     * @return videos
     */
    public function index()
    {
        $token    = request()->bearerToken();
        $user     = auth()->user();
        $videos   = Video::where('user_id', $user->id)->get();
        return $videos;
    }
    /**
     * Method to create a new video
     * @param request with video data
     * @return response with the video created
     */
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
            'path'    => $data['type'] == 'true'?'https://www.youtube.com/embed/'.$data['path']:$data['path'],
            'user_id' => $user->id,
        );

        $video = Video::create($video);
        $response = Response::make(json_encode(['data'=>$video ]), 201)->header('Location', 'http://localhost/api/videos/'.$video ->id)->header('Content-Type', 'application/json');
        return $response;
    }
     /**
     * Method to get a specific video
     * @param video id
     * @return response with the video
     */
    public function show($video_id)
    {
        $video = Video::find($video_id);
        if (!$video) {
            return response()->json(['errors'=>array(['code'=> 404, 'message'=>'No se ha encontrado el video.'])], 404);
        }
        return response()->json(['status'=>'ok', 'data'=>$video], 200);
    }
    /**
     * Method to update a specific video
     * @param request with video data
     * @return response with the video updated
     */
    public function update(VideoRequest $request)
    {
        $data   = $request->all();
        $user   = auth()->user();
        if ($request->file('video')) {
            $path = Storage::disk('public')->put('videos', $request->file('video'));
            $data['path'] = asset($path);
        }
        $video = array(
            'id'      => $data['id'],
            'name'    => $data['name'],
            'type'    => $data['type'],
            'path'    => $data['type'] == 'true'?'https://www.youtube.com/embed/'.$data['path']:$data['path'],
            'user_id' => $user->id,
        );
        $video = Video::find($request->id)->update($video);
        return response()->json(['status'=>'ok','data' => $video], 200);
    }
    /**
     * Method to destroy a specific video
     * @param video id
     * @return response with status 204
     */
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
