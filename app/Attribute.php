<?php
// Extended from Rivex Attribute for additional accessors and mutators
namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'group',
        'type',
        'field_type',
        'is_required',
        'is_unique',
        'is_editor',
        'is_system',
        'is_collection',
        'default',
        'entities',
    ];

    protected $casts = [
        'slug' => 'string',
        'sort_order' => 'integer',
        'group' => 'string',
        'type' => 'string',
        'is_required' => 'boolean',
        'is_collection' => 'boolean',
        'default' => 'string',
    ];

    protected $table = 'attributes';


    public function getIsRequiredAttribute($val)
    {
        return $val ? 'Yes': 'No';
    }

    public function getIsSystemAttribute($val)
    {
        return $val ? 'Yes': 'No';
    }

    public function getIsUniqueAttribute($val)
    {
        return $val ? 'Yes': 'No';
    }

    public function getIsEditorAttribute($val)
    {
        return $val ? 'Yes': 'No';
    }

    public function options()
    {
        return $this->hasMany('App\AttributeOption','attribute_id');
    }

    //get entities attached to this attribute
    public function entities()
    {
        return $this->hasMany('App\AttributeEntity', 'attribute_id', 'id');
    }

    public function getEntitiesAttribute(): array
    {
        return $this->entities()->pluck('entity_type')->toArray();
    }

    public function setEntitiesAttribute($entities): void
    {
        static::saved(function ($model) use ($entities) {
            $this->entities()->delete();
            ! $entities || $this->entities()->createMany(array_map(function ($entity) {
                return ['entity_type' => $entity];
            }, $entities));
        });
    }
}
