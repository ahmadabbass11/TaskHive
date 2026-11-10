@extends('layouts.website')

@section('page-content')
<div class="container mt-5">
    <div class="profile-card glassmorphism p-4 shadow-lg rounded-4">
        <h2 class="profile-heading text-center mb-4">
            <i class="fas fa-user-edit me-2"></i> Update Your Profile
        </h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <div class="text-center mb-4 position-relative">
                <div class="profile-pic-wrapper">
                    <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets/taskhivelogo.png') }}"
                         class="profile-pic shadow-sm" alt="Profile Picture">
                    <label class="upload-btn">
                        <input type="file" name="profile_picture" class="d-none">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
            </div>

            <!-- Full Name -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control form-style" name="name" id="name" value="{{ auth()->user()->name }}" required>
                <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
            </div>

            <!-- Email -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control form-style" name="email" id="email" value="{{ auth()->user()->email }}" required>
                <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
            </div>

            <!-- New Password -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control form-style" name="password" id="password" placeholder="Password">
                <label for="password"><i class="fas fa-lock me-2"></i>New Password</label>
            </div>

            <!-- Confirm Password -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control form-style" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirm Password</label>
            </div>

            <!-- Save Button -->
            <button type="submit" class="btn neon-btn w-100 mb-3">
                <i class="fas fa-save me-2"></i>Save Changes
            </button>
        </form>

        <!-- Feedback Button -->
        <a href="{{ route('feedback.index') }}" class="btn btn-outline-warning w-100 mb-3">
            <i class="fas fa-comments me-2"></i>View / Submit Feedback
        </a>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>
</div>

<style>
:root {
    --glass-bg: rgba(255, 255, 255, 0.15);
    --glass-border: rgba(255, 255, 255, 0.3);
    --primary-glow: #00f2fe;
    --secondary-glow: #4facfe;
}

body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    background-size: cover;
}

/* Glassmorphism Container */
.glassmorphism {
    backdrop-filter: blur(15px);
    background-color: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    max-width: 600px;
    margin: auto;
    color: #fff;
}

/* Profile Heading */
.profile-heading {
    font-size: 2rem;
    background: linear-gradient(90deg, #00f2fe, #4facfe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 1px 1px 10px rgba(255,255,255,0.2);
}

/* Profile Picture */
.profile-pic-wrapper {
    position: relative;
    display: inline-block;
}
.profile-pic {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid white;
    object-fit: cover;
    transition: 0.3s ease;
}
.profile-pic:hover {
    transform: scale(1.05);
}
.upload-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: var(--primary-glow);
    color: #fff;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2rem;
    transition: background 0.3s;
}
.upload-btn:hover {
    background: var(--secondary-glow);
}

/* Floating Inputs */
.form-floating label {
    color: #ccc;
}
.form-style {
    background-color: rgba(255,255,255,0.07);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
}
.form-style:focus {
    border-color: var(--primary-glow);
    box-shadow: 0 0 10px var(--primary-glow);
    background-color: rgba(255,255,255,0.1);
}

/* Neon Button */
.neon-btn {
    background: linear-gradient(90deg, var(--primary-glow), var(--secondary-glow));
    border: none;
    color: #fff;
    font-weight: bold;
    border-radius: 12px;
    padding: 12px 20px;
    box-shadow: 0 0 15px var(--primary-glow);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.neon-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px var(--secondary-glow);
}

/* Outline Buttons */
.btn-outline-warning,
.btn-outline-danger {
    font-weight: bold;
    border-radius: 12px;
    border-width: 2px;
}
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
}
</style>
@endsection
