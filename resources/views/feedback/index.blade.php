@extends('layouts.website')

@section('page-content')
{{-- Display validation errors --}}
@if ($errors->has('content'))
    <div class="alert alert-danger">
        {{ $errors->first('content') }}
    </div>
@endif

{{-- Display moderation-block flash --}}
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif



<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success text-center mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Card for Submitting Feedback -->
    <div class="card shadow-lg mb-4"
         style="background: linear-gradient(135deg, #3498DB, #1ABC9C); color: #fff;">
        <div class="card-header text-center" style="background: rgba(0,0,0,0.1);">
            <h2 class="mb-0">Submit New Feedback</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('feedback.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">Your Feedback</label>
                    <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-light btn-lg px-5">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <h3 class="text-center mb-4">All User Feedback</h3>
    @else
        <h3 class="text-center mb-4">Your Feedback</h3>
    @endif

    @forelse($feedbacks as $feedback)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <!-- Show the user name and email -->
                <h5 class="card-title">
                    {{ $feedback->user->name }}
                    <small class="text-muted ms-2">
                        ({{ $feedback->user->email }})
                    </small>
                </h5>
                <p class="card-text mt-3">{{ $feedback->content }}</p>
                <p class="text-muted mb-0">
                    <small>
                        Submitted on {{ $feedback->created_at->format('M d, Y \a\t h:i A') }}
                    </small>
                </p>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No feedback found.
        </div>
    @endforelse
</div>

<style>
    /* Subtle fade-in animation for container */
    .container {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        border-radius: 1rem;
    }

    .card-header {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
</style>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // wait 3 seconds, then close all alerts
    setTimeout(function () {
      document.querySelectorAll('.alert').forEach(function (el) {
        // Bootstrap’s JS API
        let bsAlert = new bootstrap.Alert(el);
        bsAlert.close();
      });
    }, 3000);
  });
</script>
@endpush