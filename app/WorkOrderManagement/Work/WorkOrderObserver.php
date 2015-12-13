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
            if ($participant->id == \Auth::id()) {
                continue;
            }

            $url = \get_uri_path(route('host.work.work-order.show', $model->id));

            $message          = new Message();
            $message->title   = '一个新的工单 #' . $model->id . ' 发布了';
            $message->content = view('host.communication.message.template.new-work-order-dynamic',
                [
                    'workOrder' => $model,
                    'url'       => $url
                ]);

            $message->sendTo($participant);
        }

        // 创建历史
        $history           = new WorkOrderHistory();
        $history->event_id = WorkOrderHistory::EVENT_WORK_ORDER_CREATE;
        $history->workOrder()->associate($model);
        $history->user()->associate(\Auth::user());
        $history->save();
    }

    public function updated(WorkOrder $workOrder)
    {
        $sendedUsers = [];

        $messageData = [];
        switch ($workOrder->status) {
            case 0:
                $messageData = [
                    'title' => '工单 #' . $workOrder->id . ' 已关闭',
                    'template' => 'host.communication.message.template.closed'
                ];
                break;
            case 2:
                $messageData = [
                    'title' => '工单 #' . $workOrder->id . ' 已被受理',
                    'template' => 'host.communication.message.template.accept'
                ];
                break;
            case 3:
                $messageData = [
                    'title' => '工单 #' . $workOrder->id . ' 已被标记为解决',
                    'template' => 'host.communication.message.template.solved'
                ];
                break;
        }

        foreach ($workOrder->participants as $participant) {
            if ($participant->id == \Auth::id()) {
                continue;
            }

            $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

            $message          = new Message();
            $message->title   = $messageData['title'];
            $message->content = view($messageData['template'],
                [
                    'workOrder' => $workOrder,
                    'url'       => $url
                ]);

            $message->sendTo($participant);

            // 当工单解决或关闭则取消关注
            if ($workOrder->status == 0 || $workOrder->status == 3) {
                $workOrder->participants()->detach($participant->id);
            }

            $sendedUsers[] = $participant->id;
        }

        foreach ($workOrder->group->participants as $participant) {
            if ($participant->id == \Auth::id()) {
                continue;
            }

            if (!in_array($participant->id, $sendedUsers)) {
                $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

                $message          = new Message();
                $message->title   = $messageData['title'];
                $message->content = view($messageData['template'],
                    [
                        'workOrder' => $workOrder,
                        'url'       => $url
                    ]);

                $message->sendTo($participant);
            }
        }

        // 创建历史
        $history           = new WorkOrderHistory();
        $history->event_id = WorkOrderHistory::EVENT_WORK_ORDER_STATUS_CHANGE;
        $history->remark   = json_encode(['status' => $workOrder->status]);
        $history->workOrder()->associate($workOrder);
        $history->user()->associate(\Auth::user());
        $history->save();
    }
}