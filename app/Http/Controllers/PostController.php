<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Session;
use Purifier;
use Image;
use Storage;

class PostController extends Controller
{
    const image_w = 750;
    const image_h = 400;

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id','desc')->paginate(8);

        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the data
        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id' => 'required|integer',
            'featured_image' => 'sometimes|image',
            'body' => 'required'
        ));

        // Store in the database
        $post = new Post;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = Purifier::clean($request->body);

        // Save the image
        if ($request->hasFile('featured_image')) {
            $image = $request->featured_image;
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = public_path('images/'.$filename);

            Image::make($image)->fit(self::image_w, self::image_h)->save($location);

            $post->image = $filename;
        }

        $post->save();

        // Attach tags (clear associations first: false)
        $post->tags()->sync($request->tags, false);

        Session::flash('success','The blog post was successfully saved!');

        // Redirect
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        $categories = array('' => '-- Select a Category --');
        foreach (Category::all() as $category) {
            $categories[$category->id] = $category->name;
        }

        $tags = array();
        foreach (Tag::all() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return view('posts.edit')->withPost($post)->withCategories($categories)->withTags($tags);
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
        // Validate the data
        $post = Post::find($id);

        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug,'.$id,
            'category_id' => 'required|integer',
            'featured_image' => 'sometimes|image',
            'body' => 'required'
        ));

        $post = Post::find($id);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->body = Purifier::clean($request->body);

        // Save the image
        if ($request->hasFile('featured_image')) {
            $image = $request->featured_image;
            $filename = time().'.'.$image->getClientOriginalExtension();
            $location = public_path('images/'.$filename);

            Image::make($image)->fit(self::image_w, self::image_h)->save($location);

            // Keep a copy of the original filename
            $old_filename = $post->image;

            // Update the DB with the new filename
            $post->image = $filename;

            // Delete the old image (check filesystems.php config for default root path)
            Storage::delete($old_filename);
        }

        $post->save();

        // Attach tags
        $post->tags()->sync($request->tags);

        Session::flash('success','Successfully updated the blog post!');

        // Redirect
        return redirect()->route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->tags()->detach();
        Storage::delete($post->image);
        $post->delete();

        Session::flash('success','The blog post was successfully deleted!');

        // Redirect
        return redirect()->route('posts.index');
    }
}
