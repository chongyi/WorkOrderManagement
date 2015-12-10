<?php

namespace App\Http\Controllers\Web\Work;

use App\Http\Controllers\Web\BaseDataBootstrap;
use App\WorkOrderManagement\Work\WorkOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WorkOrderController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workOrders = new WorkOrder();

        if ($group = $request->query('group_id')) {
            $workOrders->where('group_id', $group);
        }

        if (in_array($status = $request->query('status', -1), [0, 1, 2, 3])) {
            $workOrders->where('status', $status);
        }

        $workOrders = $workOrders->display()->orderBy('sort', 'desc')->orderBy('created_at', 'desc')->paginate(8);

        if ($request->ajax()) {
            $data = [];
            foreach ($workOrders as $workOrder) {
                $data[] = [
                    'id'                => $workOrder->id,
                    'subject'           => $workOrder->subject,
                    'group_id'          => $workOrder->group_id,
                    'group'             => $workOrder->group->display_name,
                    'user_id'           => $workOrder->user_id,
                    'user'              => $workOrder->publisher->name,
                    'user_email'        => $workOrder->publisher->email,
                    'category_id'       => $workOrder->category_id,
                    'category'          => $workOrder->category->display_name,
                    'activity'          => $workOrder->activity,
                    'status'            => $workOrder->status,
                    'publish_time'      => $workOrder->format('Y-m-d H:i:s'),
                    'publish_timestamp' => $workOrder->getTimestamp(),
                    'update_time'       => $workOrder->format('Y-m-d H:i:s'),
                    'update_timestamp'  => $workOrder->getTimestamp()
                ];
            }

            return response()->json([
                'body' => [
                    'list'      => $data,
                    'paginator' => [
                        'current'  => $workOrders->currentPage(),
                        'total'    => $workOrders->total(),
                        'count'    => $workOrders->count(),
                        'per_page' => $workOrders->perPage(),
                    ],
                ],
            ]);
        } else {
            return view('host.work.work-order.index')->with('workOrders', $workOrders);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $workOrder = WorkOrder::findOrFail($id);

        return view('host.work.work-order.show')->with('enableGroup', $workOrder->group)->with('workOrder', $workOrder);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
