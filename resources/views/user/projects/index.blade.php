@extends('layouts.website')

@section('page-content')
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2 class="text-uppercase fw-bold text-light bg-gradient bg-dark py-3 rounded shadow-lg">
                <i class="fa fa-folder-open"></i> Your Projects
            </h2>
        </div>

        <div class="row justify-content-center">
            @foreach($projects as $project)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card project-card border-0 shadow-lg rounded-3 overflow-hidden position-relative">
                        <div class="card-header text-light fw-bold text-center py-3"
                            style="background: linear-gradient(135deg, #4e54c8, #8f94fb);">
                            <h5 class="mb-0">{{ $project->name }}</h5>
                        </div>
                        <div class="card-body bg-light text-center position-relative">
                            <p class="text-muted">{{ Str::limit($project->description, 80) }}</p>
                            <a href="{{ route('user.projects.show', $project->id) }}"
                                class="btn btn-primary w-100 shadow-sm animate-button">
                                <i class="fa fa-folder-open"></i> View Project
                            </a>
                        </div>
                        <div class="project-ribbon text-light">Task</div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($projects->isEmpty())
            <div class="alert alert-warning text-center">
                <i class="fa fa-exclamation-circle"></i> You have no projects assigned yet.
            </div>
        @endif
    </div>

    <style>
        /* Card Hover Effect */
        .project-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Button Animation */
        .animate-button {
            transition: background 0.3s, transform 0.2s ease-in-out;
        }

        .animate-button:hover {
            transform: scale(1.05);
            background: #4e54c8 !important;
        }

        /* Ribbon Effect */
        .project-ribbon {
            position: absolute;
            top: 12px;
            right: -20px;
            background: #ff4757;
            padding: 5px 20px;
            font-size: 12px;
            font-weight: bold;
            transform: rotate(30deg);
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection