<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;

class PagesController extends Controller {

    public function index() {
        $posts = Post::orderBy('created_at', 'DESC')->limit(4)->get();
        return view('pages.welcome')->withPosts($posts);
    }

    public function about() {
        return view('pages.about');
    }

    public function getContact() {
        return view('pages.contact');
    }

    public function postContact(Request $request) {
        $this->validate($request, array(
            'email' => 'required|email',
            'subject' => 'min:3',
            'message' => 'min:3'
        ));

        $data = array(
            'email' => $request->email,
            'subject' => $request->subject,
            'body_message' => $request->message // 'message' is reserved by email templates
        );

        Mail::send('emails.contact', $data, function($msg) use ($data){
            $msg->from($data['email']);
            $msg->to('nowt835@gmail.com');
            $msg->subject($data['subject']);
        });

        Session::flash('success', 'E-Mail successfully sent!');

        return redirect(url('/'));
    }

}
