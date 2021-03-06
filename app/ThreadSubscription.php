<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    protected $guarded =[];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread ()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @param $reply
     * @return mixed
     */
    public function notify ($reply)
    {
        return $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }
}
