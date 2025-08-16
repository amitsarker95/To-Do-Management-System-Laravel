<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Services\TodoSessionStore;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    private TodoSessionStore $store;

    public function __construct()
    {
        $this->middleware('auth');
        $this->store = new TodoSessionStore(auth()->id());
    }

    public function index(Request $request)
    {
        $todos = $this->store->all();
        $theme = $request->cookie('theme', 'light');
        return view('todos.index', compact('todos', 'theme'));
    }

    public function store(TodoRequest $request)
    {
        $todos = $this->store->all();

        $todos[] = [
            'id'          => $this->store->nextId(),
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'pending',
        ];

        $this->store->putAll($todos);
        return back()->with('success', 'Task added!');
    }

    public function edit(int $id, Request $request)
    {
        $todo = $this->store->find($id);
        abort_if(!$todo, 404);
        $theme = $request->cookie('theme', 'light');
        return view('todos.edit', compact('todo', 'theme'));
    }

    public function update(TodoRequest $request, int $id)
    {
        $todos = $this->store->all();

        foreach ($todos as &$t) {
            if ($t['id'] === $id) {
                $t['title'] = $request->title;
                $t['description'] = $request->description;
                // Update status
                $t['status'] = in_array($request->status, ['pending', 'done', 'Completed']) 
                                ? $request->status 
                                : 'pending';
                break;
            }
        }

        $this->store->putAll($todos);
        return redirect()->route('todos.index')->with('success', 'Task updated!');
    }

    public function destroy(int $id)
    {
        $filtered = array_values(array_filter($this->store->all(), fn ($t) => $t['id'] !== $id));
        $this->store->putAll($filtered);
        return back()->with('success', 'Task deleted!');
    }

    public function toggle(int $id)
    {
        $todos = $this->store->all();
        foreach ($todos as &$t) {
            if ($t['id'] === $id) {
                $t['status'] = $t['status'] === 'done' ? 'pending' : 'done';
                break;
            }
        }
        $this->store->putAll($todos);
        return back()->with('success', 'Status changed!');
    }

    public function clearAll()
    {
        $this->store->putAll([]);
        return back()->with('success', 'All tasks cleared!');
    }
}
