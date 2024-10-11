<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=Post::all();
        return view('images/index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("images.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->hasFile("cover")){
            $file=$request->file("cover");
            $imageName=time().'_'.$file->getClientOriginalName();
            $file->move(\public_path("cover/"),$imageName);

            $post =new Post([
                "title" =>$request->title,
                "author" =>$request->author,
                "body" =>$request->body,
                "cover" =>$imageName,
            ]);
           $post->save();
        }

            if($request->hasFile("images")){
                $files=$request->file("images");
                foreach($files as $file){
                    $imageName=time().'_'.$file->getClientOriginalName();
                    $request['post_id']=$post->id;
                    $request['image']=$imageName;
                    $file->move(\public_path("/images"),$imageName);
                    Image::create($request->all());

                }
            }

            return redirect('images-uploads')->with('success', 'Images Upload Successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $posts=Post::findOrFail($id);
        return view('images/edit')->with('posts',$posts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $post=Post::findOrFail($id);
     if($request->hasFile("cover")){
         if (File::exists("cover/".$post->cover)) {
             File::delete("cover/".$post->cover);
         }
         $file=$request->file("cover");
         $post->cover=time()."_".$file->getClientOriginalName();
         $file->move(\public_path("/cover"),$post->cover);
         $request['cover']=$post->cover;
     }

        $post->update([
            "title" =>$request->title,
            "author"=>$request->author,
            "body"=>$request->body,
            "cover"=>$post->cover,
        ]);

        if($request->hasFile("images")){
            $files=$request->file("images");
            foreach($files as $file){
                $imageName=time().'_'.$file->getClientOriginalName();
                $request["post_id"]=$id;
                $request["image"]=$imageName;
                $file->move(\public_path("images"),$imageName);
                Image::create($request->all());

            }
        }
        return redirect('images-uploads')->with('success', 'Images Updated Successfully');

}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $posts=Post::findOrFail($id);

        if (File::exists("cover/".$posts->cover)) {
            File::delete("cover/".$posts->cover);
        }
        $images=Image::where("post_id",$posts->id)->get();
        foreach($images as $image){
        if (File::exists("images/".$image->image)) {
           File::delete("images/".$image->image);
       }
        }
        $posts->delete();
        return back();
    }

    public function deleteimage($id){
        $images=Image::findOrFail($id);
        if (File::exists("images/".$images->image)) {
           File::delete("images/".$images->image);
       }

       Image::find($id)->delete();
       return back('images-uploads');
   }

   public function deletecover($id){
        $cover=Post::findOrFail($id)->cover;
        if (File::exists("cover/".$cover)) {
        File::delete("cover/".$cover);
    }
    return back('images-uploads');
}
}
