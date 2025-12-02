@extends('templates.app')

@section('content')
    <div class="container my-5">
        <form method="POST" action="{{ route('sign_up') }}">
            {{-- kunci yang di minta server --}}
            @csrf
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Card for sign-up form -->
                <div class="card shadow-lg border-0 rounded-4" style="background: linear-gradient(135deg, #db2777, #3b82f6);">
                    <div class="card-body p-5">
                        <h2 class="text-center text-white mb-4 fw-bold">Sign Up for Goers</h2>

                        <!-- Success and Error Alerts -->
                        @if (Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Sign-Up Form -->
                        <form method="POST" action="">
                            @csrf

                            <!-- First and Last Name -->
                            <div class="row mb-4">
                                <div class="col">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="form3Example1" class="form-control form-control-lg rounded-pill @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required />
                                        <label class="form-label text-white" for="form3Example1">First Name</label>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="form3Example2" class="form-control form-control-lg rounded-pill @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required />
                                        <label class="form-label text-white" for="form3Example2">Last Name</label>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="form3Example3" class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required />
                                <label class="form-label text-white" for="form3Example3">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="password" id="form3Example4" class="form-control form-control-lg rounded-pill @error('password') is-invalid @enderror" name="password" required />
                                <label class="form-label text-white" for="form3Example4">Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Checkbox -->
                            <div class="form-check d-flex justify-content-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33" checked />
                                <label class="form-check-label text-white" for="form2Example33">
                                    Subscribe to Goers Newsletter
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button data-mdb-ripple-init type="submit" class="btn btn-warning btn-lg btn-block rounded-pill mb-4 fw-bold">Sign Up</button>

                            <!-- Social Sign-Up -->
                            <div class="text-center text-white">
                                <p>Already a member? <a href="{{ route('login') }}" class="text-warning fw-bold text-decoration-underline">Login</a></p>
                                <p>or sign up with:</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button data-mdb-ripple-init type="button" class="btn btn-outline-light btn-floating">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>
                                    <button data-mdb-ripple-init type="button" class="btn btn-outline-light btn-floating">
                                        <i class="fab fa-google"></i>
                                    </button>
                                    <button data-mdb-ripple-init type="button" class="btn btn-outline-light btn-floating">
                                        <i class="fab fa-twitter"></i>
                                    </button>
                                    <button data-mdb-ripple-init type="button" class="btn btn-outline-light btn-floating">
                                        <i class="fab fa-github"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Sharp Styling -->
    @push('script')
        <style>
            body {
                background-color: #f1f5f9;
                font-family: 'Roboto', sans-serif;
            }
            .card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
            }
            .form-control {
                background-color: rgba(255, 255, 255, 0.9);
                border: none;
                transition: all 0.3s ease;
            }
            .form-control:focus {
                background-color: #fff;
                box-shadow: 0 0 10px rgba(219, 39, 119, 0.5); /* Pink glow */
            }
            .form-outline .form-label {
                color: #fff !important;
                transition: all 0.2s ease;
            }
            .form-outline .form-label.active {
                color: #fff !important;
                transform: translateY(-1.5rem) scale(0.8);
            }
            .btn-warning {
                background-color: #facc15; /* Vibrant yellow */
                border: none;
                color: #1e1e1e; /* Dark text for contrast */
            }
            .btn-warning:hover {
                background-color: #eab308; /* Darker yellow */
                box-shadow: 0 5px 15px rgba(250, 204, 21, 0.4); /* Yellow glow */
            }
            .btn-outline-light {
                border-color: #fff;
                color: #fff;
            }
            .btn-outline-light:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: #fff;
            }
            .invalid-feedback {
                font-size: 0.9rem;
                color: #ff4d4f;
            }
            .text-warning {
                color: #facc15 !important; /* Yellow for links */
            }
            .text-decoration-underline:hover {
                color: #eab308 !important; /* Darker yellow on hover */
            }
            .alert-success {
                background-color: rgba(219, 39, 119, 0.1); /* Light pink background */
                border-color: #db2777; /* Pink border */
                color: #db2777; /* Pink text */
            }
            .alert-danger {
                background-color: rgba(255, 75, 75, 0.1);
                border-color: #ff4d4f;
                color: #ff4d4f;
            }
        </style>
    @endpush
@endsection
