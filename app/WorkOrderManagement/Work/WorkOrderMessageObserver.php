<?php
/**
 * WorkOrderMessageObserver.php
 *
 * Created by Chongyi
 * Date & Time 2015/12/13 16:49
 */

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\Communication\Message;
use Illuminate\Contracts\Events\Dispatcher;

class WorkOrderMessageObserver
{
    private $event;

    public function __construct(Dispatcher $event)
    {
        $this->event = $event;
    }

    public function created(WorkOrderMessage $message)
    {
        if ($message->workOrder->messages()->count() > 1) {
            $workOrder = $message->workOrder;
            $workOrder->increment('activity');

            $sendedUsers = [];

            foreach ($workOrder->participants as $participant) {
                if ($participant->id == \Auth::id()) {
                    continue;
                }

                $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

                $message          = new Message();
                $message->title   = '工单 #' . $workOrder->id . ' 有新的动态';
                $message->content = view('host.communication.message.template.new-work-order-dynamic',
                    [
                        'workOrder' => $workOrder,
                        'url'       => $url
                    ]);

                $message->sendTo($participant);

                $sendedUsers[] = $participant->id;
            }

            // 当在一个工单下发布消息，则认为关注了该工单
            if (!$workOrder->participants()->whereId(\Auth::id())->count()) {
                $workOrder->participants()->attach(\Auth::id());
            }

            if (\Auth::id() != $workOrder->user_id && $workOrder->status == 1) {
                $workOrder->status = 2;
                $workOrder->save();
            }

            foreach ($workOrder->group->participants as $participant) {
                if ($participant->id == \Auth::id()) {
                    continue;
                }

                if (!in_array($participant->id, $sendedUsers)) {
                    $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

                    $message          = new Message();
                    $message->title   = '工单 #' . $workOrder->id . ' 有新的动态';
                    $message->content = view('host.communication.message.template.new-work-order-dynamic',
                        [
                            'workOrder' => $workOrder,
                            'url'       => $url
                        ]);

                    $message->sendTo($participant);
                }
            }

            $history           = new WorkOrderHistory();
            $history->event_id = WorkOrderHistory::EVENT_NEW_WORK_ORDER_MESSAGE;
            $history->remark   = json_encode(['message_id' => $message->id]);
            $history->user()->associate(\Auth::user());
            $history->workOrder()->associate($workOrder);
            $history->save();
        }
    }
}