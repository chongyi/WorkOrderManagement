<?php

namespace App\Http\Controllers\Web\Work;

use App\Http\Controllers\Web\BaseDataBootstrap;
use App\WorkOrderManagement\Work\WorkOrder;
use App\WorkOrderManagement\Work\WorkOrderMessage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WorkOrderMessageController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $messages = WorkOrder::findOrFail($id)->messages()->get();

        $data = [];

        foreach ($messages as $message) {
            $data[] = [
                'id'                   => $message->id,
                'publisher'            => $message->publisher->name,
                'publisher_email'      => $message->publisher->email,
                'publisher_id'         => $message->user_id,
                'is_publisher_message' => $message->workOrder->publisher->id === $message->user_id,
                'content'              => $message->content,
                'publish_time'         => $message->created_at->format('Y-m-d H:i:s'),
                'publish_timestamp'    => $message->created_at->getTimestamp()
            ];
        }

        if (\Request::ajax()) {
            return response()->json(['body' => ['list' => $data]]);
        }

        return view('host.work.work-order.message.index')->with('messages', $messages);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function store(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        return \DB::transaction(function () use ($id, $request) {
            $workOrder = WorkOrder::findOrFail($id);

            $message          = new WorkOrderMessage();
            $message->content = $request->input('content');
            $message->workOrder()->associate($workOrder);
            $message->publisher()->associate(\Auth::user());
            $message->save();

            if ($request->ajax()) {
                return response()->json([
                    'body' => $message->id
                ]);
            }

            return redirect()->back()->with('create-success', 'work-order-message');
        });
    }
}
