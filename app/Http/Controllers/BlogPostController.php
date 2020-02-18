<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBlogPost;
use App\BlogPost;
use App\Subscriber;
use Mail;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogposts = BlogPost::paginate(9);

        //$blogposts = DB::table('blog_posts')->paginate(9);

        return view('blogposts.index',['blogposts'=>$blogposts]);



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogposts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogPost $request)
    {
         // $blogPost = new BlogPost;
         // $blogPost->title = $request->title;
         // $blogPost->author = $request->author;
         // $blogPost->content = $request->content;
         // $blogPost->view = 0;
         // $blogPost->save();

        BlogPost::create(['title'=>$request->title,'author'=>$request->author,'content'=>$request->content]);
        $users = Subscriber::All();
        session()->flash('status', 'Congratulations! New post is announced.');
        

        foreach($users as $user) {
            $data = array('title'=>$request->title,'author'=>$request->author,"content"=>$request->content,'name'=>$user->name);
            Mail::send('mail', $data, function($message) use ($user) {
                $message->to($user->email,$user->name)->subject
                   ('Subscription Confirmation Mail');
                $message->from('thetphoolaravel@gmail.com','ITVisionHub Feed Zone');
             });
        }
        

        return redirect()->route('blog-posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      $blogPost = BlogPost::find($id);

      BlogPost::where('id',$id)->update(['view'=>$blogPost->view + 1]);

      return view('blogposts.show',['post'=>BlogPost::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('blogposts.edit',['post'=>BlogPost::find($id)]);
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
      BlogPost::where('id',$id)->update(['title'=>$request->title,'author'=>$request->author,'content'=>$request->content]);
      return redirect()->route('blog-posts.show',['blog_post'=>$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blogPost = BlogPost::find($id);
        $blogPost->delete();
        session()->flash('status', 'Post id('.$id.')  is deleted .');
        return redirect()->route('blog-posts.index');
    }
}
