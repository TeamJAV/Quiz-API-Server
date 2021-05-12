@extends('layouts.app')
@section('navbar')
{{--    @include('.includes.Student.navbar')--}}
@endsection
@section('content')
        <form action="{{ route('student.login.room') }}" method="post">
            @csrf
            <div class="form-group">
                <input type="text" name="room-name" class="form-control text-uppercase @error('room-name') is-invalid @enderror" placeholder="Enter room">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Join</button>
            </div>
        </form>
@endsection
