<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Task;
use Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $taskList = Task::where('user_id', $user_id)->get();
        return response()->json(compact('taskList'));
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
        $task = $request->all();

        if ($newTask = Task::create($task)) {
            $message = "success";
        }
        else{
            $message = "failed";
        }

        return response()->json(compact('message', 'newTask'));     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user()->id;
        $task = Task::find($id);
        $input = $request->all();

        if ($task->user_id == $user) {
            if($task->update($input)){
                $message = "success";
            }
            else{
                $message = "failed";
            }
        }
        else{
            $message = "failed";
        }

        return response()->json(compact('message', 'input')); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user()->id;
        $task = Task::find($id);

        if ($task->user_id == $user) {
            if($task->delete()){
                $message = "success";
            }
            else{
                $message = "failed";
            }
        }
        else{
            $message = "failed";
        }

        return response()->json(compact('message'));  
    }
}
