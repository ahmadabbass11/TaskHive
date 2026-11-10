@extends('layouts.website')

@section('page-content')
    <div class="container mt-5">
        <!-- Project Title -->
        <div class="text-center">
            <h2 class="text-uppercase fw-bold bg-gradient bg-dark text-light py-4 rounded shadow">
                {{ $project->name }}
            </h2>
            <p class="lead text-muted">{{ $project->description }}</p>
        </div>

        <!-- Milestones Section -->
        <div class="mt-5">
            <h3 class="text-primary fw-bold"><i class="fa fa-flag-checkered"></i> Milestones</h3>
            <div class="row">
                @foreach($milestones as $milestone)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-0 shadow-sm rounded-3 bg-light p-3">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $milestone->title }}</h5>
                                <p class="text-muted small">📅 {{ $milestone->start_date }} - {{ $milestone->end_date }}</p>

                                <!-- Animated Progress Bar -->
                                <div class="progress mt-2">
                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                        role="progressbar" style="width: {{ rand(40, 100) }}%;" aria-valuenow="70"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Assigned Tasks Section -->
        <div class="mt-5">
            <h3 class="text-success fw-bold"><i class="fa fa-tasks"></i> Your Assigned Tasks</h3>
            <div class="row">
                @foreach($tasks as $task)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div
                            class="card shadow-sm rounded-3 task-card border-0 p-3 
                                        {{ strtolower($task->status) === 'to-do' ? 'bg-danger text-white' : (strtolower($task->status) === 'in progress' ? 'bg-warning text-dark' : 'bg-success text-white') }}">
                            <div class="card-body">
                                <h5 class="fw-bold">{{ $task->title }}</h5>
                                <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                                    {{ ucfirst($task->status) }}
                                </span>

                                <!-- Task Progress Indicator -->
                                <div class="progress mt-3">
                                    <div class="progress-bar {{ strtolower($task->status) === 'to-do' ? 'bg-danger' : (strtolower($task->status) === 'in progress' ? 'bg-warning' : 'bg-success') }} progress-bar-striped progress-bar-animated"
                                        role="progressbar"
                                        style="width: {{ strtolower($task->status) === 'to-do' ? '10%' : (strtolower($task->status) === 'in progress' ? '50%' : '100%') }};"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- File Upload Form -->
                                <form action="{{ route('task.file.upload', $task->id) }}" method="POST"
                                    enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <div class="input-group">
                                        <input type="file" name="file" class="form-control file-input">
                                        <button type="submit" class="btn btn-outline-light"><i class="fa fa-upload"></i>
                                            Submit</button>
                                    </div>
                                    <p class="small text-light mt-2 file-name"></p>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($tasks->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="fa fa-exclamation-circle"></i> No tasks assigned yet.
                </div>
            @endif
        </div>
    </div>

    <script>
        // Show selected file name for upload field
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function () {
                const fileName = this.files[0] ? this.files[0].name : 'No file selected';
                this.closest('.input-group').nextElementSibling.innerText = fileName;
            });
        });
    </script>

@endsection