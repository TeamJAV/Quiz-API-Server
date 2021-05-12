@extends('layouts.app')
@section('navbar')
    @include('.includes.Student.navbar')
@endsection
@section('content')
    <form action="{{ route('student.login.student-name') }}" method="post">
        @csrf
        <div class="form-group">
            <input type="text" name="student-name" class="form-control text-uppercase @error('student-name') is-invalid @enderror" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Done</button>
        </div>
    </form>
@endsection
