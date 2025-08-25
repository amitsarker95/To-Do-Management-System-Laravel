<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
    $todos = Task::where('user_id', Auth::id())->paginate(10); 
    $theme = $request->cookie('theme', 'light');

    return view('todos.index', compact('todos', 'theme'));
    }



    // AJAX Requirments Start
    public function ajaxList(Request $request){
        $task = Task::where('user_id', Auth::id())->orderByDesc('id')->paginate(10);
        return response()->json(['todos' => $task]);
    }

    public function ajaxStore(Request $request){
        $validated = request()->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|max:255',
        ]);

        $task = Task::create([
            'title'=> $validated['title'],
            'description'=> $validated['description'],
            'status' => 'Pending',
            'user_id' => Auth::id(),

        ]);
        return response()->json(['todos' => $task, 'message' => 'Task has been successfully created.']);
    }


    public function ajaxUpdate(Request $request, Task $todo){
        $this->authorize('update', $todo);
        $validated = $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|max:255',
            'status' => 'required|in:Pending,Completed',
        ]);
        $todo->update($validated);
        return response()->json(['todos'=> $todo,'message'=> 'Task Update successfully.']);
    }

    public function ajaxDelete(Request $request, Task $todo){
        $this->authorize('delete', $todo);
        $todo->delete();
        return response()->json(['message'=> 'Task Delete successfully.']);
    }

    //AJEX Requirments End



    public function store(TodoRequest $request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Pending',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('todos.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $todo, Request $request)
    {
        $this->authorize('update', $todo);
        $theme = $request->cookie('theme', 'light');
        return view('todos.edit', compact('todo', 'theme'));
    }


    public function update(TodoRequest $request, Task $todo)
    {
        $this->authorize('update', $todo);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? $todo->status,
        ]);

        return redirect()->route('todos.index')->with('success', 'Task updated successfully!');
    }

public function destroy(Task $todo)
{
    $this->authorize('delete', $todo);
    $todo->delete();

    return redirect()->route('todos.index')->with('success', 'Task deleted successfully!');
}

public function toggle(Task $todo)
{
    $this->authorize('update', $todo);
    $todo->status = $todo->status === 'Pending' ? 'Completed' : 'Pending';
    $todo->save();

    return redirect()->route('todos.index')->with('success', 'Task status changed!');

}

    public function clearAll()
    {
        Task::where('user_id', Auth::id())->delete();
        return back()->with('success', 'All tasks cleared!');
    }
}
