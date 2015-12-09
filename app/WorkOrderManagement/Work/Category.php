<?php

namespace App\WorkOrderManagement\Work;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * 该分类下的工单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
