<?php

namespace App\Http\Controllers\Api;

use App\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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

        return response($todo);
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
        $validator = Validator::make($request->all(), [
            'task'      => 'required|string',
            'desc'      => 'required|string',
            'image'     => 'required|mimes:jpg,jpeg,png,svg,webp,gif',
            'is_done'   => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($request->file('image') != null) {
            $image    = $request->file('image');
            $new_name = time()."_".$image->getClientOriginalName();
            $image->move(public_path("img"), $new_name);
		}

        $todo = Todo::create([
            'task'      => $request->task,
            'desc'      => $request->desc,
            'image'     => $new_name,
            'is_done'   => $request->is_done
        ]);

        if (!$todo->save()) {
            return response([
                'status'    => 'error',
                'msg'       => 'create failed !'
            ]);
        }

        return response([
            'status'    => 'success',
            'msg'       => 'create success !'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo, $id)
    {
        $todo = Todo::findOrFail($id);

        return response($todo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo, $id)
    {
        $todo = Todo::findOrFail($id);

        return response($todo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo, $id)
    {
        $validator = Validator::make($request->all(), [
            'task'      => 'required|string',
            'desc'      => 'required|string',
            'image'     => 'required|mimes:jpg,jpeg,png,svg,webp,gif',
            'is_done'   => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $todo = Todo::findOrFail($id);

        if ($request->file('image') != null) {
			if ($todo->image != 'testing.png') {
				$todo_image = public_path("img/{$todo->image}"); // get previous image from folder
				if (File::exists($todo_image)) { // unlink or remove previous image from folder
                    unlink($todo_image);
				}
				$image    = $request->file('image');
				$new_name = time()."_{$todo->id}_".$image->getClientOriginalName();
				$image->move(public_path("img"), $new_name);
				$todo->image = $new_name;
				$photo = asset('img/'.$new_name);
			} else {
				$image    = $request->file('image');
				$new_name = time()."_{$todo->id}_".$image->getClientOriginalName();
				$image->move(public_path("img"), $new_name);
				$todo->image = $new_name;
				$photo = asset('img/'.$new_name);
			}
		}

        $todo->task     = $request->task;
        $todo->desc     = $request->desc;
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
    public function destroy(Todo $todo, $id)
    {
        $todo = Todo::findOrFail($id);
        if(!$todo->delete()) {
            return response([
                'status'    => 'error',
                'msg'       => 'delete failed !'
            ]);
        }
        $todo_image = public_path("img/{$todo->image}"); // get previous image from folder
        if (File::exists($todo_image)) { // unlink or remove previous image from folder
            unlink($todo_image);
        }
        return response([
            'status'    => 'success',
            'msg'       => 'delete success !'
        ]);

    }
}
