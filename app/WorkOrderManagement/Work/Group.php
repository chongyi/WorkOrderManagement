<?php

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\User;
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
        return $this->belongsTo(User::class);
    }
}
