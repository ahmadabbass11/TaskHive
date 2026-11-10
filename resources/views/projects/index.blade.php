@extends('layouts.website')

@section('page-content')
    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4 dashboard-title">
            <i class="fas fa-tasks"></i> Project Dashboard
        </h2>

        <!-- Success & Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Add Project Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                <i class="fas fa-plus"></i> Add Project
            </button>
        </div>

        <!-- Project Cards -->
        <div class="row">
            @foreach ($projects as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card project-card shadow-lg">
                        <div class="card-header text-white fw-bold text-center py-3"
                            style="background: linear-gradient(135deg, #1264A3, #4A154B);">
                            <h4>
                                <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none text-white">
                                    <i class="fas fa-folder-open"></i> {{ $project->name }}
                                </a>
                            </h4>
                        </div>
                        <div class="card-body bg-light text-center">
                            <p class="text-muted">{{ Str::limit($project->description, 80) }}</p>
                            <p><strong>Team:</strong> {{ $project->team->name }}</p>
                            <p><strong>Deadline:</strong> {{ $project->deadline }}</p>

                            <!-- Progress Bar -->
                            <div class="progress mt-2" style="height: 10px;">
                                <div class="progress-bar progress-animated" role="progressbar"
                                    style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ $project->progress }}%
                                </div>
                            </div>

                            <div class="d-flex justify-content-around mt-3">
                                <!-- Add Task Button -->
                                <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#addTaskModal-{{ $project->id }}">
                                    <i class="fas fa-tasks"></i> Add Task
                                </button>

                                <!-- Delete Project -->
                                <form method="POST" action="{{ route('projects.destroy', $project->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @include('projects.partials.add_task_modal', ['project' => $project])
            @endforeach
        </div>
    </div>

    @include('projects.partials.add_project_modal')

    <style>
        /* Project Card */
        .project-card {
            border-radius: 10px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .dashboard-title {
            font-size: 2.5rem;
            background: linear-gradient(90deg, #ff5733, #ffbd69, #28a745, #17a2b8, #6f42c1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 10px rgba(255, 105, 180, 0.7);
            animation: glowing 2s infinite alternate;
        }

        @keyframes glowing {
            from {
                text-shadow: 2px 2px 10px rgba(255, 105, 180, 0.7),
                    0 0 20px rgba(255, 223, 186, 0.8);
            }

            to {
                text-shadow: 2px 2px 15px rgba(255, 105, 180, 1),
                    0 0 25px rgba(255, 223, 186, 1);
            }
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Animated Progress Bar */
        .progress-animated {
            background: linear-gradient(90deg, #28a745, #17a2b8);
            transition: width 1s ease-in-out;
        }

        /* Button Effects */
        .btn:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }

        /* Alerts */
        .alert {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

@endsection