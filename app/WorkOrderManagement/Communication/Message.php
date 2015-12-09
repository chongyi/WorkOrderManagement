<?php

namespace App\WorkOrderManagement\Communication;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\WorkOrderManagement\User;

class Message extends Model
{
    /**
     * 发送人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 接收人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }

    /**
     * 获取未读列表
     *
     * @param Builder $builder
     *
     * @return $this
     */
    public function scopeUnread(Builder $builder)
    {
        return $builder->where('read', false);
    }

    /**
     * 修改当前状态为已读
     */
    public function toRead()
    {
        $this->read = true;
        $this->save();
    }
}
