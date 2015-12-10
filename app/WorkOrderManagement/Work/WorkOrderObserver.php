<?php
/**
 * WorkOrderObserver.php
 *
 * Created by Chongyi
 * Date & Time 2015/12/10 22:54
 */

namespace App\WorkOrderManagement\Work;

use Illuminate\Http\Request;

class WorkOrderObserver
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function created(WorkOrder $model)
    {
        // 当一个工单保存时，并不会在工单对象中保存具体的内容，所有的工单内容都是在 WorkOrderMessage 中保存
        // 因此首次创建时会同时创建一个消息（WorkOrderMessage）
        $workOrderMessage = new WorkOrderMessage();
        $workOrderMessage->content = $this->request->input('content');
        $workOrderMessage->save();

        $model->messages()->save($workOrderMessage);
        \Auth::user()->myWorkOrderMessages()->save($workOrderMessage);
    }
}