<?php
/**
 * Created by PhpStorm.
 * User: eadande
 * Date: 8/2/17
 * Time: 10:53 PM
 */

namespace App;


use Illuminate\Support\Facades\App;

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach(static::getActivitiesToRecord() as $event){
            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });
        }
        static::deleting(function ($model){
            $model->activity()->delete();
        });
    }
    public static function getActivitiesToRecord()
    {
        return ['created'];
    }
    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return  "{$event}_{$type}";
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}