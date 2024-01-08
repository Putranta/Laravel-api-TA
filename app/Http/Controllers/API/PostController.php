<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostDetail;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $post = Post::all();
        return new PostCollection($post);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();

        $file = $request->file('img');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        $post = Post::create([
            'name'  => $request->name,
            'img'   => $fileName,
            'desc'  => $request->desc,
            'status'        => 'Tersedia',
            'category_id'   => $request->category_id,
            'user_id'       => $user->id
        ]);

        $file->storeAs('public/posts/', $fileName);

        return new PostDetail(true, "Create Post Berhasil", $post);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return new PostResource($post);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if ($request->hasFile('img')) {

            //upload img
            $img = $request->file('img');
            $imgName = uniqid() . '.' . $img->getClientOriginalExtension();

            //update post with new img
            $post->update([
                'img'     => $imgName,
                'name'  => $request->name,
                'desc'  => $request->desc,
                'status'        => $post->status,
                'category_id'   => $request->category_id,
            ]);

            $img->storeAs('public/posts/', $imgName);

            //delete old img
            Storage::delete('public/posts/' . basename($post->img));
        } else {

            //update post without img
            $post->update([
                'name'  => $request->name,
                'desc'  => $request->desc,
                'status'        => $post->status,
                'category_id'   => $request->category_id,
            ]);
        }

        return new PostDetail(true, "Update Post Berhasil", $post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        Storage::delete('public/posts/' . basename($post->img));

        $post->delete($id);
        return new PostDetail(true, "Post Berhasil Dihapus", null);
    }

    public function baseCategory($id)
    {
        $post = Post::where('category_id', $id)->orderBy('id', 'desc')->get();

        return new PostCollection($post);
    }

    public function baseUser($id)
    {
        $post = Post::where('user_id', $id)->orderBy('id', 'desc')->get();

        return new PostCollection($post);
    }

    public function currentUser()
    {
        $id = Auth::user()->id;
        $post = Post::where('user_id', $id)->orderBy('id', 'desc')->get();

        return new PostCollection($post);
    }
}
