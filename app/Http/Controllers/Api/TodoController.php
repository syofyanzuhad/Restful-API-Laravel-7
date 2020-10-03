<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todo = Todo::all();

        return response('todo');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = Todo::create([
            'task'      => $request->task,
            'desc'      => $request->desc,
            'image'     => $request->image,
            'is_done'   => $request->is_done
        ]);

        if (!$todo->save()) {
            return response([
                'status'    => 'error',
                'msg'       => 'update failed !'
            ]);
        }

        return response([
            'status'    => 'success',
            'msg'       => 'update success !'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        $todo = Todo::findOrFail($todo);

        return response($todo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit($todo)
    {
        $todo = Todo::findOrFail($todo);

        return response($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $todo)
    {
        $todo = Todo::findOrFail($todo);

        $todo->task     = $request->task;
        $todo->desc     = $request->desc;
        $todo->image    = $request->image;
        $todo->is_done  = $request->is_done;

        if (!$todo->save()) {
            return response([
                'status'    => 'error',
                'msg'       => 'update failed !'
            ]);
        }
        return response([
            'status'    => 'success',
            'msg'       => 'update success !'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo = Todo::findOrFail($todo);
        if(!$todo->delete()) {
            return response([
                'status'    => 'error',
                'msg'       => 'delete failed !'
            ]);
        }
        return response([
            'status'    => 'success',
            'msg'       => 'delete success !'
        ]);

    }
}
