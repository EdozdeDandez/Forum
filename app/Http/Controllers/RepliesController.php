<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use Illuminate\Http\Request;
use App\Thread;
use Mockery\Exception;

class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return mixed
     */
    public function index ($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        try{
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }

        return $reply->load('owner');
//        if (request()->expectsJson()) {
//            return $reply->load('owner');
//        }
//        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy (Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if (\request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    /**
     * @param Reply $reply
     */
    public function update (Reply $reply)
    {
        $this->authorize('update', $reply);
        try{
            $this->validateReply();
            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }
}
