<?php

namespace App\Http\Controllers\Web\Communication;

use App\Http\Controllers\Web\BaseDataBootstrap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    use BaseDataBootstrap;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $messages = \Auth::user()->myReceivedMessages();

        if ($status = $request->query('status')) {
            if ($status == 1) {
                $messages->unread();
            } else {
                $messages->read();
            }
        }

        $messages = $messages->orderBy('created_at', 'desc')->paginate(8);

        $data = [];

        foreach ($messages as $message) {
            $data[] = [
                'id'             => $message->id,
                'from'           => $message->user_id ? $message->sender->name : 'system',
                'from_id'        => $message->user_id,
                'system_message' => $message->system_message,
                'title'          => $message->title,
                'content'        => $message->content,
                'read'           => $message->read,
                'send_time'      => $message->created_at->format('Y-m-d H:i:s'),
                'send_timestamp' => $message->created_at->getTimestamp()
            ];
        }

        if ($request->ajax()) {
            return response()->json([
                'body' => [
                    'list'       => $data,
                    'pagination' => [
                        'current'       => $messages->currentPage(),
                        'total'         => $messages->total(),
                        'count'         => $messages->count(),
                        'per_page'      => $messages->perPage(),
                        'last_page'     => $messages->lastPage(),
                        'has_more_page' => $messages->hasMorePages()
                    ]
                ]
            ]);
        }

        return view('host.communication.message.index');
    }

    public function status(Request $request)
    {
        $body = [];

        if ($request->query('unread')) {
            $body = \Auth::user()->myReceivedMessages()->unread()->count();
        }

        return response(['body' => $body]);
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
        $message = \Auth::user()->myReceivedMessages()->whereId($id)->firstOrFail();

        $message->read = $request->input('status');

        $message->save();

        if ($request->ajax()) {
            return response()->json(['body' => 'success']);
        }

        return redirect()->back();
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
