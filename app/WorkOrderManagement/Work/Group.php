<?php

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * 当前工作组底下的所有工单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * 该工作组的创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 获取该工作组的参与人、关注人
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function participants()
    {
        return $this->morphToMany(User::class, 'involvement');
    }

    /**
     * 获取可用的工作组
     *
     * @param Builder $builder
     *
     * @return $this
     */
    public function scopeEnable(Builder $builder)
    {
        return $builder->where('status', true);
    }
}
