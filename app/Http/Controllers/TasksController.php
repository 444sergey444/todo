<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

/**
 * Class TasksController
 *
 * @package App\Http\Controllers
 */
class TasksController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $tasks = auth()->user()->tasks();
        return view('dashboard', compact('tasks'));
    }

    /**
     * @return Application|Factory|View
     */
    public function add()
    {
        return view('add');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, ['description' => 'required']);
        $task = new Task();
        $task->description = $request->description;
        $task->user_id = auth()->user()->id;
        $task->save();
        return redirect('/dashboard');
    }

    /**
     * @param Task $task
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function edit(Task $task)
    {
        if (auth()->user()->id == $task->user_id) {
            return view('edit', compact('task'));
        } else {
            return redirect('/dashboard');
        }
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(Request $request, Task $task)
    {
        if (isset($_POST['delete'])) {
            $task->delete();
            return redirect('/dashboard');
        } else {
            $this->validate($request, ['description' => 'required']);
            $task->description = $request->description;
            $task->save();
            return redirect('/dashboard');
        }
    }
}
