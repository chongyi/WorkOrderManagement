<?php

namespace App\WorkOrderManagement\Work;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\WorkOrderManagement\User;

class WorkOrder extends Model
{
    /**
     * 工单发布人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 工单所属的工作组
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * 工单所属的分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 工单下面的所有信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(WorkOrderMessage::class);
    }

    /**
     * 工单的事件历史
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(WorkOrderHistory::class);
    }

    /**
     * 获取可见的工单
     *
     * @param Builder $builder
     *
     * @return $this
     */
    public function scopeDisplay(Builder $builder)
    {
        return $builder->where('display', true);
    }

    /**
     * 获取该工单的参与者、关注人
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function participants()
    {
        return $this->morphToMany(User::class, 'involvement');
    }
}
