<?php
/**
 * WorkOrderObserver.php
 *
 * Created by Chongyi
 * Date & Time 2015/12/10 22:54
 */

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\Communication\Message;
use App\WorkOrderManagement\User;
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
        $workOrderMessage          = new WorkOrderMessage();
        $workOrderMessage->content = $this->request->input('content');
        $workOrderMessage->workOrder()->associate($model);
        $workOrderMessage->save();

        $user = \Auth::user();
        $user->myWorkOrderMessages()->save($workOrderMessage);

        // 工单发布人默认关注当前工单
        $user->involvements(User::INVOLVED_WORK_ORDER)->attach($model->id);

        foreach ($model->group->participants as $participant) {
            $url = \get_uri_path(route('host.work.work-order.show', $model->id));

            $message          = new Message();
            $message->title   = '#' . $model->id . ' 有新的动态';
            $message->content = view('host.communication.message.template.new-work-order-dynamic',
                [
                    'workOrder' => $model,
                    'url'       => $url
                ]);

            $message->sendTo($participant);
        }
    }
}