<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Models\Tag;

class PostController extends Controller
{
    /**
     * 一覧画面
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Post::with('user')->latest('id')->paginate(20);
        return view('back.posts.index', compact('posts'));
    }

    /**
     * 登録画面
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $tags = Tag::pluck('name', 'id')->toArray();
        return view('back.posts.create', compact('tags'));
    }

    /**
     * 登録処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());
        //タグを追加
        $post->tags()->attach($request->tags);


        if ($post) {
            return redirect()
                ->route('back.posts.edit', $post)
                ->withSuccess('データを登録しました。');
        } else {
            return redirect()
                ->route('back.posts.create')
                ->withError('データの登録に失敗しました。');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 編集処理
     *
     * @param Post $post
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Post $post)
    {
        return view('back.posts.edit', compact('post'));
    }

     /**
     * 更新処理
     *
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PostRequest $request, Post $post)
    {
        // タグを更新
        $post->tags()->sync($request->tags);

        if ($post->update($request->all())) {
            $flash = ['success' => 'データを更新しました。'];
        } else {
            $flash = ['error' => 'データの更新に失敗しました'];
        }

        return redirect()
            ->route('back.posts.edit', $post)
            ->with($flash);
    }

    /**
     * 削除処理
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        // タグを削除
        $post->tags()->detach();

        if ($post->delete()) {
            $flash = ['success' => 'データを削除しました。'];
        } else {
            $flash = ['error' => 'データの削除に失敗しました'];
        }

        return redirect()
            ->route('back.posts.index')
            ->with($flash);
    }
}
