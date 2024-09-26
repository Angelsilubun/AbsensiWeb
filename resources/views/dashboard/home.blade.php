@extends('layouts.main')

@section('title', 'Home To Do List')

@section('container')

<main class="flex-shrink-0 mt-custom">
    <div class="container">
        <h2 class="text-center mt-5 mb-5"><i class="bi bi-list-task"></i> To Do List</h2>

        <div class="card shadow-sm rounded">
          <div class="card-body">
            <form action="{{ url('/dashboard') }}" method="POST">

            @csrf
              <div class="form-floating mb-3">
                <input type="text" class="form-control @error('task') is-invalid @enderror" name="task" value="{{ old('task') }}" placeholder="Add new task">
                <label for="floatingInput">Add New Task</label>

                @error('task')
                  <div class="alert alert-danger mt-2">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-floating mb-3">
                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" id="start_date">
                <label for="floatingStartDate">Start Date</label>

                @error('start_date')
                <div class="alert alert-danger mt-2">
                    {{ $message }}
                </div>
                @enderror
              </div>

              <div class="form-floating mb-3">
                  <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}" id="due_date">
                  <label for="floatingDueDate">Due Date</label>

                  @error('due_date')
                  <div class="alert alert-danger mt-2">
                      {{ $message }}
                  </div>
                  @enderror
              </div>
              <button type="submit" class="btn btn-primary btn-sm">Save Task</button>
              <button type="reset" class="btn btn-secondary btn-sm">Reset</button>
            </form>
          </div>
        </div>

        <div class="card card2 mt-4">
          <div class="card-body">
            <form class="d-flex mb-3" role="search" method="GET" action="{{ url('/dashboard') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Please input the keyword" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <table class="table table-bordered">
              <thead>
                  <tr class="text-center">
                      <th scope="col">Task Pekerjaan</th>
                      <th scope="col">Start Date</th>
                      <th scope="col">Due Date</th>
                      <th scope="col">Sisa Hari</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($tasks as $task)
                  <tr class="text-center">
                      <td scope="row">{{ $task->task }}</td>
                      <td>{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M Y') : '-' }}</td>
                      <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}</td>
                      <td style="color: {{ is_numeric($task->remaining_days) && $task->remaining_days < 0 ? 'red' : 'black' }};">
                          @if ($task->status == 'Done')
                              -
                          @elseif (is_numeric($task->remaining_days) && $task->remaining_days < 0)
                              Terlambat {{ abs($task->remaining_days) }} hari
                          @else
                              Sisa {{ $task->remaining_days }} hari
                          @endif
                      </td>
                      <td>
                          @if ($task->status == 'Done')
                              <span class="badge rounded-pill text-bg-success status-badge">Done</span>
                          @elseif ($task->status == 'On Progress')
                              <span class="badge rounded-pill text-bg-warning status-badge">On Progress</span>
                          @else
                              <span class="badge rounded-pill text-bg-secondary status-badge">Hold</span>
                          @endif
                      </td>
                      <td>
                          <form onsubmit="return confirm('Apakah anda yakin?');" action="{{ route('dashboard.destroy', $task->id)}}" method="POST">
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" href="#edit_task{{ $task->id }}" nowrap>
                                  <i class="bi bi-pencil-square"></i>
                              </button>
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-secondary" nowrap><i class="bi bi-trash"></i></button>
                          </form>        
                      </td>
                  </tr>
                  @empty
                  <tr>
                      <td colspan="6" class="text-center">
                          <div class="alert alert-danger">Data task belum tersedia</div>
                      </td>
                  </tr>
                  @endforelse 
              </tbody>
            </table>
              <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
              {{ $tasks->links() }}
            </div>
          </div>
        </div>
    </div>
    @include('dashboard.edit')

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show date picker on focus
            document.getElementById('start_date').addEventListener('focus', function() {
                this.showPicker();
            });
            document.getElementById('due_date').addEventListener('focus', function() {
                this.showPicker();
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 1000, // 1 detik
                    showConfirmButton: false
                });
            @elseif(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}",
                    timer: 1000, // 1 detik
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush

</main>

@endsection