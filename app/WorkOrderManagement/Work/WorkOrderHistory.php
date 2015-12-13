<?php

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\User;
use Illuminate\Database\Eloquent\Model;

class WorkOrderHistory extends Model
{
    const EVENT_WORK_ORDER_CREATE        = 1;
    const EVENT_NEW_WORK_ORDER_MESSAGE   = 2;
    const EVENT_WORK_ORDER_STATUS_CHANGE = 3;

    /**
     * 所属的工单
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * 历史事件的产生者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
