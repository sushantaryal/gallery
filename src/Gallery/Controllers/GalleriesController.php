<?php

namespace Taggers\Gallery\Controllers;

use Image;
use Taggers\Gallery\Models\Photo;
use Taggers\Gallery\Models\Gallery;
use Illuminate\Http\Request;
use Taggers\Gallery\Controllers\Controller;

class GalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::with('photos')->get();

        return view('gallery::galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gallery::galleries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        Gallery::create([
            'title' => $request->input('title'),
            'status' => $request->input('status')
        ]);

        flash('Gallery has been created successfully.', 'success');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gallery = Gallery::find($id);

        return view('gallery::galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $gallery = Gallery::find($id);
        $gallery->title = $request->input('title');
        $gallery->status = $request->input('status');
        $gallery->save();

        flash('Gallery has been updated successfully.', 'success');
        return back();
    }

    /**
     * Update publish status
     * 
     * @param  $id
     * @return Response
     */
    public function updateStatus($id)
    {
        $gallery = Gallery::find($id);
        $gallery->status = ($gallery->status == 1) ? 0 : 1;
        $gallery->save();
        
        flash('Status has been updated successfully.', 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::with('photos')->find($id);

        // Delete related photos
        $gallery->photos->each(function($photo) {
            if(app('files')->exists('uploads/photos/' . $photo->filename)) {
                app('files')->delete('uploads/photos/' . $photo->filename);
            }
            if(app('files')->exists('uploads/photos/thumbs/' . $photo->filename)) {
                app('files')->delete('uploads/photos/thumbs/' . $photo->filename);
            }
            $photo->delete();
        });

        $gallery->delete();

        flash('Gallery has been deleted successfully.', 'success');
        return back();
    }

    /**
     * Upload photo to a category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadPhotos(Request $request)
    {
        $gallery = Gallery::find($request->input('gallery_id'));

        $path = 'uploads/photos/';
        $image = $request->file('file');
        $originalName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();

        $filename = generateFilename($path, $extension);

        // Upload Original
        $image = Image::make($image)->save($path . $filename);
        // Upload thumbnail
        $thumbimage = Image::make($image)->fit(500)->save($path . 'thumbs/' . $filename);

        if(!$image || !$thumbimage) {
            return response()->json([
                'error' => true,
                'message' => 'Server error while uploading',
                'code' => 500
            ], 500);
        }

        $photo = Photo::create([
            'gallery_id' => $gallery->id,
            'original_name' => $originalName,
            'filename' => $filename
        ]);

        return response()->json([
            'error' => false,
            'code' => 200
        ], 200);
    }

    /**
     * Get all photos for the category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPhotos($id)
    {
        $gallery = Gallery::with('photos')->find($id);

        $photos = $gallery->photos;

        $photoarr = [];

        foreach($photos as $photo) {
            $photoarr[] = [
                'server' => $photo->filename,
                'size' => app('files')->size(public_path('uploads/photos/' . $photo->filename)),
                'imageurl' => asset('uploads/photos/thumbs/' . $photo->filename)
            ];
        }
        return response()->json([
            'photos' => $photoarr
        ]);
    }

    /**
     * Delete photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deletePhoto(Request $request)
    {
        $photo = Photo::where('original_name', $request->input('filename'))
                        ->orWhere('filename', $request->input('filename'))
                        ->first();

        $path = 'uploads/photos/' . $photo->filename;
        $thumbpath = 'uploads/photos/thumbs/' . $photo->filename;

        if(app('files')->exists($path)) {
            app('files')->delete($path);
        }
        if(app('files')->exists($thumbpath)) {
            app('files')->delete($thumbpath);
        }

        $photo->delete();

        return response()->json([
            'error' => false,
            'code' => 200
        ], 200);
    }
}
