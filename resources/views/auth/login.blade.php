@extends('layouts.auth')

@section('title', 'Login - PT WAGS')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card border-0 shadow-lg p-4 animate-fade-in">
                <div class="card-body">
                    <div class="text-center mb-5">
                        <div class="brand-logo mx-auto mb-3" style="width: 56px; height: 56px; border-radius: 14px;">
                            <i data-lucide="gem" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h2 class="login-title mb-1">PT WAGS</h2>
                        <p class="text-muted small">Sistem Pakar Klasifikasi Material</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-semibold" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control py-2 px-3" placeholder="Masukkan email Anda" required autofocus>
                            @error('email') 
                                <div class="text-danger small mt-2 fw-medium">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="mb-5 text-start">
                            <label class="form-label fw-semibold" for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control py-2 px-3" placeholder="••••••••" required>
                            @error('password') 
                                <div class="text-danger small mt-2 fw-medium">{{ $message }}</div> 
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted small mt-4">
                &copy; {{ date('Y') }} PT Wina Alam Gunung Semesta
            </p>
        </div>
    </div>
</div>
@endsection
