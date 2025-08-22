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
        $todos = Task::where('user_id', Auth::id())->get();
        $theme = $request->cookie('theme', 'light');

        return view('todos.index', compact('todos', 'theme'));
    }

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
