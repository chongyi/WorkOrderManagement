<?php

namespace App\WorkOrderManagement\Work;

use Illuminate\Database\Eloquent\Model;
use App\WorkOrderManagement\User;

class WorkOrderMessage extends Model
{
    protected $touches = ['workOrder'];

    /**
     * 消息发布人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
