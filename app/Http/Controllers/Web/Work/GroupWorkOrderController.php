<?php

namespace App\Http\Controllers\Web\Work;

use App\Http\Controllers\Web\BaseDataBootstrap;
use App\WorkOrderManagement\User;
use App\WorkOrderManagement\Work\Category;
use App\WorkOrderManagement\Work\Group;
use App\WorkOrderManagement\Work\WorkOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupWorkOrderController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $groupId)
    {
        $workOrders = Group::findOrFail($groupId)->workOrders();

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
                    'list'       => $data,
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
            return view('host.work.work-order.index')->with('workOrders', $workOrders);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($groupId)
    {
        return view('host.work.group.work-order.create')
            ->with('enableGroup', Group::findOrFail($groupId))
            ->with('categories', Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $groupId)
    {
        $validator = \Validator::make($request->all(), [
            'subject'     => 'required',
            'sort'        => 'required|numeric|min:0|max:5',
            'content'     => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        return \DB::transaction(function () use ($request, $groupId) {
            $group     = Group::findOrFail($groupId);
            $category  = Category::findOrFail($request->input('category_id'));
            $workOrder = new WorkOrder();

            $workOrder->subject = $request->input('subject');
            $workOrder->sort    = $request->input('sort');

            $workOrder->category()->associate($category);
            $workOrder->group()->associate($group);
            $workOrder->publisher()->associate(\Auth::user());

            $workOrder->save();

            return redirect()
                ->route('host.work.work-order.show', $workOrder->id)
                ->with('create-success', 'work-order');
        });
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
