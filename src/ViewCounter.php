<?php

namespace SajadHasanzadeh\LaravelViewCounter;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @mixin \Eloquent
 * @property Viewable viewable
 */
class ViewCounter extends Eloquent
{
    public $timestamps = false;

    protected $fillable = ['viewable_id', 'viewable_type', 'count'];

    /**
     * @access private
     */
    public function viewable()
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     */
    public static function rebuild($modelClass)
    {
        if (empty($modelClass)) {
            throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $builder = View::query()
            ->select(\DB::raw('count(*) as count, viewable_type, viewable_id'))
            ->where('viewable_type', $modelClass)
            ->groupBy('viewable_id');

        $results = $builder->get();

        $inserts = $results->toArray();

        \DB::table((new static)->table)->insert($inserts);
    }
}