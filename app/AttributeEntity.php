<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeEntity extends Model
{
    protected $table = 'attribute_entity';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'entity_type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'entity_type' => 'string',
    ];
}
