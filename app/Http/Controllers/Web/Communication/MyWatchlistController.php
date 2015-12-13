<?php

namespace App\Http\Controllers\Web\Communication;

use App\WorkOrderManagement\User;
use App\WorkOrderManagement\Work\Group;
use App\WorkOrderManagement\Work\WorkOrder;
use App\Http\Controllers\Controller;

class MyWatchlistController extends Controller
{
    /**
     * 关注一个工单动态
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function putWorkOrder($id)
    {
        return $this->operating($id, User::INVOLVED_WORK_ORDER, true);
    }

    /**
     * 取消一个工单的关注
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteWorkOrder($id)
    {
        return $this->operating($id, User::INVOLVED_WORK_ORDER, false);
    }

    /**
     * 关注一个工作组
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function putGroup($id)
    {
        return $this->operating($id, User::INVOLVED_GROUP, true);
    }

    /**
     *  取消关注一个工作组
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteGroup($id)
    {
        return $this->operating($id, User::INVOLVED_GROUP, false);
    }

    private function operating($target, $type, $method)
    {
        $targetObject =
            $type == User::INVOLVED_WORK_ORDER ? WorkOrder::findOrFail($target) : Group::findOrFail($target);

        $operator = \Auth::user()->involvements($type);

        if ($method) {
            $operator->attach($targetObject->id);
        } else {
            $operator->detach($targetObject->id);
        }

        if (\Request::ajax()) {
            return response()->json(['body' => 'success']);
        }

        return redirect()->back();
    }
}
