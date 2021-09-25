<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phones';
    protected $guarded = [];

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function addresses()
    {
        return $this->morphedByMany('App\Address','phoneable');
    }

    public function addTags(Array $arr)
    {
        $sync_ids = $this->tags()->get()->pluck('id')->toArray();
        if(!$sync_ids)
            $sync_ids = [];
        foreach($arr as $item){
            if($item){
                $tag_model = \App\Tag::firstOrCreate(['content' => $item]);
                $sync_ids[] = $tag_model->id;
            }
        }
        //dd($sync_ids);
        $this->tags()->sync(array_unique($sync_ids));
    }

    public function getDndAttribute($value)
    {
        return $value ? 'Yes':'No';
    }
}
