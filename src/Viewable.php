<?php

namespace SajadHasanzadeh\LaravelViewCounter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Copyright (C) 2014 Robert Conner
 *
 * @method static Builder whereViewedBy($userId=null)
 * @property Collection|View[] views
 * @property Viewd viewed
 * @property integer ViewCount
 */
trait Viewable
{
    public static function bootViewable()
    {
        if (static::removeViewsOnDelete()) {
            static::deleting(function ($model) {
                /** @var Viewable $model */
                $model->removeViews();
            });
        }
    }

    /**
     * Populate the $model->views attribute
     */
    public function getViewsCountAttribute()
    {
        return $this->viewCounter ? $this->viewCounter->count : 0;
    }

    /**
     * Add a view for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function view($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $view = $this->views()
                ->where('user_id', '=', $userId)
                ->first();

            if ($view) {
                return;
            }

            $view = new View();
            $view->user_id = $userId;
            $this->views()->save($view);
        }

        $this->incrementViewCount();
    }

    /**
     * Remove a view from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function unview($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ($userId) {
            $view = $this->views()
                ->where('user_id', '=', $userId)
                ->first();

            if (!$view) {
                return;
            }

            $view->delete();
        }

        $this->decrementViewCount();
    }

    /**
     * Has the currently logged in user already "viewed" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function viewed($userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return (bool) $this->views()
            ->where('user_id', '=', $userId)
            ->count();
    }

    /**
     * Should remove viewes on model row delete (defaults to true)
     * public static removeViewsOnDelete = false;
     */
    public static function removeViewsOnDelete()
    {
        return isset(static::$removeViewsOnDelete)
            ? static::$removeViewsOnDelete
            : true;
    }

    /**
     * Delete views related to the current record
     */
    public function removeViews()
    {
        $this->views()->delete();
        $this->viewCounter()->delete();
    }


    /**
     * Collection of the viewes on this record
     * @access private
     */
    public function viewes()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Did the currently logged in user view this model
     * Example : if($book->viewed) { }
     * @return boolean
     * @access private
     */
    public function getViewedAttribute()
    {
        return $this->viewed();
    }

    /**
     * Counter is a record that stores the total viewes for the
     * morphed record
     * @access private
     */
    public function viewCounter()
    {
        return $this->morphOne(ViewCounter::class, 'viewable');
    }

    /**
     * Private. Increment the total view count stored in the counter
     */
    private function incrementViewCount()
    {
        $counter = $this->viewCounter()->first();

        if ($counter) {
            $counter->count++;
            $counter->save();
        } else {
            $counter = new ViewCounter();
            $counter->count = 1;
            $this->viewCounter()->save($counter);
        }
    }

    /**
     * Private. Decrement the total view count stored in the counter
     */
    private function decrementViewCount()
    {
        $counter = $this->viewCounter()->first();

        if ($counter) {
            $counter->count--;
            if ($counter->count) {
                $counter->save();
            } else {
                $counter->delete();
            }
        }
    }


    /**
     * Fetch records that are viewed by a given user.
     * Ex: Book::whereViewedBy(123)->get();
     * @access private
     */
    public function scopeWhereViewedBy($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        return $query->whereHas('viewes', function ($q) use ($userId) {
            $q->where('user_id', '=', $userId);
        });
    }

    /**
     * Fetch the primary ID of the currently logged in user
     * @return mixed
     */
    private function loggedInUserId()
    {
        return auth()->id();
    }
}