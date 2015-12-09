<?php

namespace App\WorkOrderManagement\Work;

use Illuminate\Database\Eloquent\Model;

class WorkOrderHistory extends Model
{
    /**
     * 所属的工单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
