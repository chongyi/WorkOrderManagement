<?php

namespace App\Http\Controllers\Web\Work;

use App\Http\Controllers\Web\BaseDataBootstrap;
use App\WorkOrderManagement\Work\Group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = new Group();

        if ($keywords = $request->query('keywords')) {
            $groups->where('display_name', 'like', "%$keywords%");
        }

        if ($user = $request->query('user')) {
            $groups->where(function ($query) use ($user) {
                $query->where('user_id', $user)
                    ->orWhereHas('creator', function ($query) use ($user) {
                        $query->where('email', 'like', "%$user%")->orWhere('name', 'like', "%$user%");
                    });
            });
        }

        $groups = $groups->enable()->orderBy('created_at', 'desc')->paginate(8);

        if ($request->ajax()) {
            $data = [];
            foreach ($groups as $group) {
                $data[] = [
                    'id'               => $group->id,
                    'display_name'     => $group->display_name,
                    'description'      => $group->description,
                    'user_id'          => $group->user_id,
                    'user'             => $group->creator->name,
                    'user_email'       => $group->creator->email,
                    'create_time'      => $group->format('Y-m-d H:i:s'),
                    'create_timestamp' => $group->getTimestamp(),
                    'update_time'      => $group->format('Y-m-d H:i:s'),
                    'update_timestamp' => $group->getTimestamp(),
                ];
            }

            return response()->json([
                'list'      => $data,
                'paginator' => [
                    'current'  => $groups->currentPage(),
                    'total'    => $groups->total(),
                    'count'    => $groups->count(),
                    'per_page' => $groups->perPage(),
                ],
            ]);
        } else {
            return view('host.work.group.index')->with('groups', $groups);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('host.work.group.create');
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
        $validator = \Validator::make($request->all(), [
            'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $group = new Group();
        $group->display_name = $request->input('display_name');
        $group->description = $request->input('description', '');

        $group->save();
        $group->creator()->associate(\Auth::user());

        return redirect()->route('host.work.group.show', [$group->id])->with('group.create-success', true);
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
        return view('host.work.group.show')->with('enableGroup', Group::findOrFail($id));
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
        return view('host.work.group.edit')->with('group', Group::findOrFail($id));
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
        $validator = \Validator::make($request->all(), [
            'display_name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $group = Group::findOrFail($id);
        $group->display_name = $request->input('display_name');
        $group->description = $request->input('description', '');

        $group->save();

        return redirect()->back()->with('group.update-success', true);
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
