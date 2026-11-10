@extends('layouts.website')

@section('page-content')
    <div class="container mt-5">
        <!-- Project Header -->
        <div class="project-header text-center mb-4 p-4 rounded shadow-lg"
            style="background: linear-gradient(135deg, #1264A3, #4A154B); color: white;">
            <h2 class="fw-bold"><i class="fas fa-folder-open"></i> {{ $project->name }}</h2>
            <p class="lead">{{ $project->description }}</p>
            <p><strong>Deadline:</strong> <span class="badge bg-warning text-dark">{{ $project->deadline }}</span></p>
            <p><strong>Priority:</strong>
                <span
                    class="badge {{ strtolower($project->priority) == 'high' ? 'bg-danger' : (strtolower($project->priority) == 'medium' ? 'bg-warning' : 'bg-success') }}">
                    {{ ucfirst($project->priority) }}
                </span>
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container my-3">
            <div class="progress animated-progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                    style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0"
                    aria-valuemax="100">
                    {{ $project->progress }}%
                </div>
            </div>
        </div>

        <!-- Tasks Section -->
        <h3 class="text-primary mt-4 text-center fw-bold"><i class="fas fa-tasks"></i> Project Tasks</h3>
        <div class="row">
            @foreach ($project->tasks as $task)
                <div class="col-md-6">
                    <div class="card task-card shadow-lg mb-4 border-0">
                        <div class="card-header text-white fw-bold text-center"
                            style="background: linear-gradient(135deg, #F39C12, #D35400);">
                            <h5 class="mb-0"><i class="fas fa-tasks"></i> {{ $task->title }}</h5>
                        </div>
                        <div class="card-body bg-light">
                            <p class="card-text"><strong>Description:</strong> {{ $task->description }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-secondary">{{ $task->status }}</span></p>
                            <p><strong>Due Date:</strong> <span class="badge bg-primary">{{ $task->due_date }}</span></p>
                            <p><strong>Priority:</strong>
                                <span
                                    class="badge {{ strtolower($task->priority) == 'high' ? 'bg-danger' : (strtolower($task->priority) == 'medium' ? 'bg-warning' : 'bg-success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </p>

                            <!-- File Downloads -->
                            @if ($task->files && $task->files->isNotEmpty())
                                <div class="file-container mt-3">
                                    @foreach ($task->files as $file)
                                        <a href="{{ route('files.download', $file->id) }}" class="btn btn-info btn-sm shadow-sm">
                                            <i class="fas fa-download"></i> Download File
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Delete Task -->
                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this task?');" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                    <i class="fas fa-trash-alt"></i> Delete Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* Animated Progress Bar */
        .animated-progress .progress-bar {
            background: linear-gradient(90deg, #28a745, #17a2b8);
            transition: width 1s ease-in-out;
            font-weight: bold;
        }

        /* Task Card */
        .task-card {
            border-radius: 12px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Priority Labels */
        .badge {
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
        }

        /* File Download Button */
        .file-container a {
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            font-weight: bold;
        }

        .file-container a:hover {
            transform: scale(1.1);
            background: #0a58ca !important;
            color: white !important;
        }

        /* Button Hover Effects */
        .btn:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }

        /* Project Header Styling */
        .project-header {
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>

@endsection