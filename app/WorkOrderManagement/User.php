<?php

namespace App\WorkOrderManagement;

use App\WorkOrderManagement\Work\Group;
use App\WorkOrderManagement\Work\WorkOrder;
use App\WorkOrderManagement\Work\WorkOrderMessage;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    const INVOLVED_WORK_ORDER = 0;
    const INVOLVED_GROUP      = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * 我发出的短消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mySentMessages()
    {
        return $this->hasMany(Communication\Message::class);
    }

    /**
     * 我收到的短消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myReceivedMessages()
    {
        return $this->hasMany(Communication\Message::class, 'target_id', 'id');
    }

    /**
     * 我发布的工单
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myWorkOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * 我在工单中发出的消息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myWorkOrderMessages()
    {
        return $this->hasMany(WorkOrderMessage::class);
    }

    /**
     * 获取参与、关注的工作内容对象（工单或工作组）
     *
     * @param int $object
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function involvements($object = 0)
    {
        $objects = [
            WorkOrder::class,
            Group::class,
        ];

        return $this->morphedByMany($objects[$object], 'involvement');
    }
}
