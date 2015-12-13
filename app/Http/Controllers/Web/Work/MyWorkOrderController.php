<?php

namespace App\Http\Controllers\Web\Work;

use App\Http\Controllers\Web\BaseDataBootstrap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MyWorkOrderController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workOrders = \Auth::user()->myWorkOrders();

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
                    'publish_time'      => $workOrder->created_at->format('Y-m-d H:i:s'),
                    'publish_timestamp' => $workOrder->created_at->getTimestamp(),
                    'update_time'       => $workOrder->updated_at->format('Y-m-d H:i:s'),
                    'update_timestamp'  => $workOrder->updated_at->getTimestamp(),
                    'show_url'          => route('host.work.work-order.show', $workOrder->id),
                    'is_involved'       => $workOrder->participants()->whereId(\Auth::id())->count() ? true : false
                ];
            }

            return response()->json([
                'body' => [
                    'list'      => $data,
                    'pagination' => [
                        'current'       => $workOrders->currentPage(),
                        'total'         => $workOrders->total(),
                        'count'         => $workOrders->count(),
                        'per_page'      => $workOrders->perPage(),
                        'last_page'     => $workOrders->lastPage(),
                        'has_more_page' => $workOrders->hasMorePages()
                    ],
                ],
            ]);
        } else {
            return view('host.work.work-order.index')->with('workOrders', $workOrders)->with('myOrders', true);
        }
    }
}
