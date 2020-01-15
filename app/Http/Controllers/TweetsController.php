<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tweet;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // 一覧表示 追加
    public function index(Tweet $tweets)
    {
        //
        $user = auth()->user(); //追加
        $tweets = Tweet::orderBy('created_at', 'desc')->get(); //追加

        return view('tweets.index',[
            'user'  => $user,
            'tweets' => $tweets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // 新規ツイート入力画面 追加
    public function create()
    {
        //
        $user = auth()->user();

        return view('tweets.create', [
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     // 新規ツイート投稿処理 追加
    public function store(Request $request, Tweet $tweet)
    {
        //
        $user = auth()->user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'text' => ['required', 'string', 'max:140']
        ]);

        $validator->validate();
        $tweet->tweetStore($user->id, $data);

        return redirect('tweets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // ツイート詳細画面 追加
    public function show(Tweet $tweet, $user_id)
    {
        //
        $user = auth()->user();
        $tweet = Tweet::findOrFail($user_id);

        return view('tweets.show', [
            'user'     => $user,
            'tweet'    => $tweet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // ツイート編集画面 追加
    public function edit(Tweet $tweet, $user_id)
    {
        $user = auth()->user();
        $tweets = Tweet::findOrFail($user_id);

        return view('tweets.edit', [
            'user'   => $user,
            'tweets' => $tweets
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // ツイート編集処理 追加
    public function update(Request $request, $user_id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'text' => ['required', 'string', 'max:140']
        ]);

        $tweets = Tweet::findOrFail($user_id);

        $tweets->fill($validator)->validate()->save();

        return redirect()->route('tweets.show', ['tweet' => $tweet]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // ツイート削除処理 追加
    public function destroy(Tweet $tweet, $id)
    {
        $user = auth()->user();
        $tweet->tweetDestroy($user->id, $tweet->id);

        return back();
    }
}
