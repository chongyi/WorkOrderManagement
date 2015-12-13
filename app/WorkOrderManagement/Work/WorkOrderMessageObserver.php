<?php
/**
 * WorkOrderMessageObserver.php
 *
 * Created by Chongyi
 * Date & Time 2015/12/13 16:49
 */

namespace App\WorkOrderManagement\Work;

use App\WorkOrderManagement\Communication\Message;

class WorkOrderMessageObserver
{
    public function created(WorkOrderMessage $message)
    {
        if ($message->workOrder->messages()->count() > 1) {
            $workOrder = $message->workOrder;
            $workOrder->increment('activity');

            $sendedUsers = [];

            foreach ($workOrder->participants as $participant) {
                $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

                $message          = new Message();
                $message->title   = '#' . $workOrder->id . ' 有新的动态';
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

            foreach ($workOrder->group->participants as $participant) {
                if (!in_array($sendedUsers, $participant->id)) {
                    $url = \get_uri_path(route('host.work.work-order.show', $workOrder->id));

                    $message          = new Message();
                    $message->title   = '#' . $workOrder->id . ' 有新的动态';
                    $message->content = view('host.communication.message.template.new-work-order-dynamic',
                        [
                            'workOrder' => $workOrder,
                            'url'       => $url
                        ]);

                    $message->sendTo($participant);
                }
            }
        }
    }
}