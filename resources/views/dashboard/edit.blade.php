<!-- Modal -->
@foreach ($tasks as $task)
    <div class="modal fade" id="edit_task{{ $task->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel{{ $task->id }}">Edit Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('/dashboard', $task->id) }}" method="POST" id="formEdit">
                    @method('PUT')
                    @csrf
                    <div class="modal-body" id="modal-content-edit">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('task') is-invalid @enderror" name="task" value="{{ old('task', $task->task) }}" required>
                            <label for="floatingInput">Edit Task</label>
                            @error('task')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', $task->start_date) }}">
                            <label for="floatingStartDate">Start Date</label>

                            @error('start_date')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date', $task->due_date) }}">
                            <label for="floatingDueDate">Due Date</label>

                            @error('due_date')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control @error('status') is-invalid @enderror status-select" name="status" required>
                                <option value="On Progress" {{ old('status', $task->status) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="Done" {{ old('status', $task->status) == 'Done' ? 'selected' : '' }}>Done</option>
                                <option value="Hold" {{ old('status', $task->status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                            </select>
                            <label for="floatingSelect">Status</label>
                            @error('status')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
