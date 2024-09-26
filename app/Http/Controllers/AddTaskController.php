<?php

namespace App\Http\Controllers;

use App\Models\AddTask;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AddTaskController extends Controller
{
    use ValidatesRequests;

    public function index(Request $request): View
    {
        $search = $request->input('search');

        if ($search) {
            $tasks = AddTask::where('task', 'LIKE', "%{$search}%")->paginate(5);
        } else {
            $tasks = AddTask::latest()->paginate(5);
        }

        foreach ($tasks as $task) {
            $dueDate = Carbon::parse($task->due_date)->startOfDay();
            $currentDate = Carbon::now()->startOfDay();
            
            if ($task->status == 'Done') {
                $task->remaining_days = null; 
            } else {
                $task->remaining_days = $currentDate->diffInDays($dueDate, false);
            }
        }

        return view('dashboard.home', compact('tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'task' => 'required',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date'
        ],
        [
            'task.required' => 'Task pekerjaan tidak boleh kosong.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'due_date.date' => 'Format tanggal jatuh tempo tidak valid.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal mulai.'
        ]);

        AddTask::create([
            'task' => $request->task,
            'status' => 'On Progress',
            'start_date' => $request->start_date,
            'due_date' => $request->due_date
        ]);

        return redirect()->route('dashboard.index')->with(['success' => 'Task Berhasil Disimpan']);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'task' => 'required',
            'status' => 'required|in:Done,On Progress,Hold',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date'
        ],
        [
            'task.required' => 'Task tidak boleh kosong',
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'due_date.date' => 'Format tanggal jatuh tempo tidak valid.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal mulai.'
        ]);

        $task = AddTask::findOrFail($id);

        $task->update([
            'task' => $request->task,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Task berhasil diperbarui');
    }

    public function destroy($id): RedirectResponse
    {
        $tasks = AddTask::findOrFail($id);

        $tasks->delete();

        return redirect()->route('dashboard.index')->with('success', 'Task berhasil dihapus');
    }
}
