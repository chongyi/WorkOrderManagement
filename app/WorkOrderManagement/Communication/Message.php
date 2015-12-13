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
        return $this->belongsTo(User::class, 'user_id', 'id');
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
     * 获取已读列表
     *
     * @param Builder $builder
     *
     * @return $this
     */
    public function scopeRead(Builder $builder)
    {
        return $builder->where('read', true);
    }

    /**
     * 修改当前状态为已读
     */
    public function toRead()
    {
        $this->read = true;
        $this->save();
    }

    /**
     * 将该消息发送至
     *
     * @param User      $user
     * @param bool|true $system
     */
    public function sendTo(User $user, $system = true)
    {
        $this->receiver()->associate($user);

        if ($system) {
            $this->system_message = true;
            $this->sender()->associate(\Auth::user());
        }

        $this->save();
    }
}
