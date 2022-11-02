<?php

namespace SajadHasanzadeh\LaravelViewCounter;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @mixin \Eloquent
 * @property Viewable viewable
 * @property string user_id
 * @property string viewable_id
 * @property string viewable_type
 */
class View extends Eloquent
{
    public $timestamps = true;

    protected $fillable = ['viewable_id', 'viewable_type', 'user_id'];

    /**
     * @access private
     */
    public function viewable()
    {
        return $this->morphTo();
    }
}