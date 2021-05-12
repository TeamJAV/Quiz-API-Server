@extends('layouts.app')
@section('navbar')
    @include('.includes.Teacher.navbar')
@endsection
@section('content')
    <div class="d-flex justify-content-between">
        <h1 class="text-secondary">Quizzes</h1>
        <div>
            <button name="newRoom" class="btn btn-outline-secondary"> <i class="fas fa-plus"></i> ADD QUIZ</button>
        </div>
    </div>
    <div class="content my-2 infinite-scroll">
        <form action="" method="GET" style="width: 30%"><input type="text" style="font-size:13px!important;" class="form-control text-uppercase" name="s" placeholder="Search quizzes.." value=""></form>
        <div class="row my-4">
            <div class="col col-md-3" style="border-right:1px solid #dee2e6;">
               <div class="d-flex flex-column px-4">
                   <div class="my-2">
                       <i class="far fa-folder-open text-secondary" style="font-size:20px"></i>
                       <span class="text-uppercase font-weight-bold text-secondary">Folder</span>
                   </div>
                  <div class="my-2 ml-2">
                      <i class="fa fa-signal text-secondary" style="font-size:16px"></i>
                      <span class="text-uppercase font-weight-bold text-secondary">Active</span>
                  </div>
                   <div class="my-2 ml-2">
                       <i class="far fa-trash-alt text-secondary" style="font-size:16px"></i>
                       <span class="text-uppercase font-weight-bold text-secondary">Trash</span>
                   </div>
               </div>
            </div>
            <div class="col col-md-9">
                <div class="d-flex">
                    <button class="btn">
                        <i class="far fa-trash-alt text-secondary" style="font-size:20px"></i>
                        <span class="text-uppercase text-secondary">Delete</span>
                    </button>
                    <button class="btn">
                        <i class="fa fa-layer-group text-secondary" style="font-size:20px"></i>
                        <span class="text-uppercase text-secondary">Merge</span>
                    </button>
                </div>
            </div>
        </div>


{{--        @endif--}}
    </div>
@endsection
