@extends('layouts.app')
@section('navbar')
    @include('.includes.Teacher.navbar')
@endsection
{{--@push('head')--}}
{{--    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">--}}
{{--    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>--}}

{{--@endpush--}}
@section('content')
    <div class="d-flex justify-content-between">
        <form action="" method="post">
            <input type="text" class="form-control" value="Untitled Quiz" style="font-size:30px; padding: .75rem 1rem; font-weight:bold">
        </form>
        <div>
            <button name="newRoom" class="btn btn-outline-secondary"> <i class="fas fa-plus"></i> ADD QUIZ</button>
        </div>
    </div>
    <!-- <div id="editor"></div> -->
    <div class="content my-2">
        <div class="card" style='border-radius:5px'>
            <div class="card-body d-flex flex-row" style="background-color: rgba(245, 247, 248 ,1); border-radius:5px">
                <div class="col col-md-9 col-sm-10">
                    <div class="d-flex flex-row align-items-end justify-content-start">
                        <label for="" class="font-weight-bold" style="font-size:22px;">1.</label>
                        <input type="text" class="form-control mx-2 flex-grow-1" placeholder="Have a multiple choice question to ask?">
                        <input type="number" min="1" value="1" class=form-control placeholder="point" style="width:5rem">
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-start my-2">
                        <label for="" class="font-weight-bold" style="font-size:22px">A</label>
                        <input type="checkbox" class=" mx-2">
                        <div style="width:100%">
                            <p class="text-secondary p-form" style="width:100%; display:block; padding:.375rem .75rem;font-size:1rem, line-height:1.5, color:#495057; background-color:#fff;background-clip:padding-box; border:1px solid #ced4da; border-radius: .25rem; height:calc(1.6em + 0.75rem + 2px);">Answer...</p>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-start my-2">
                        <label for="" class="font-weight-bold" style="font-size:22px">B</label>
                        <input type="checkbox" class=" mx-2">
                        <div style="width:100%">
                        <p class="text-secondary p-form" style="width:100%; display:block; padding:.375rem .75rem;font-size:1rem, line-height:1.5, color:#495057; background-color:#fff;background-clip:padding-box; border:1px solid #ced4da; border-radius: .25rem; height:calc(1.6em + 0.75rem + 2px);">Answer...</p>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-start my-2">
                        <label for="" class="font-weight-bold" style="font-size:22px">C</label>
                        <input type="checkbox" class=" mx-2">
                        <div style="width:100%">
                        <p class="text-secondary p-form" style="width:100%; display:block; padding:.375rem .75rem;font-size:1rem, line-height:1.5, color:#495057; background-color:#fff;background-clip:padding-box; border:1px solid #ced4da; border-radius: .25rem; height:calc(1.6em + 0.75rem + 2px);">Answer...</p>
                        </div>
                    </div>
                </div>
                <div class="col col-md-3 col-sm-2">
                    <div class="d-flex align-items-start justify-content-around">
                        <div style="border: 1px solid none; background-color:white; padding:0.2rem; height:12rem; width:12rem; border-radius:3px;">
                            <a href="javascript:void(0)" class="link">
                                <i class="fas fa-image fa-4x" style="opacity:0.5; position:absolute; top:50%; transform:translate(85%, -50%);"></i>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="javascript:void(0)" class="link mx-3">
                                <i class="fa fa-trash fa-2x btn-trash"></i>
                            </a>
                            <a href="javascript:void(0)" class="link m-3">
                                <i class="fa fa-copy fa-2x btn-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="d-flex justify-content-center">
            <div>
                <button class="btn btn-warning">
                    <div class="d-flex flex-column">
                        <span>MC</span>
                    </div>
                    
                </button>
                <p>Multiple Choice</p>
            </div>
            <div>
                <button class="btn btn-success">
                    <div class="d-flex flex-column">
                        <span>TF</span>
                    </div>
                    
                </button>
                <p>True / False</p>
            </div>
            <div>
                <button class="btn btn-danger">
                    <div class="d-flex flex-column">
                        <span>SA</span>
                    </div>
                    
                </button>
                <p>Short Answer</p>
            </div>
        </div>
    </div>

<script>
    let toolbarOptions = [
        ['bold', 'italic', 'underline'],          
        ['blockquote', 'code-block'],

        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'direction': 'rtl' }],

        [{ 'size': ['small', false, 'large', 'huge'] }], 

        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }],
        ['clean']
    ];

    $('p.p-form').click(function (event) {
        let old_input = $(this);
        let label_current = $(old_input).parent().parent().find("label").text();
        let div_close = $(this).parent().html(`<div class='editor editor-${label_current}'></div>`);
        let editor = new Quill(".editor.editor-" + label_current, {
            theme: 'snow',
            placeholder: 'Answer...',
            debug: 'info',
            modules: {
                toolbar: toolbarOptions
            }
        });
    })
    
    $('body').click(function(event){
        if (!$(event.target).closest("p").lenght && !$(event.target).is("p") &&
            !$(event.target).closest(".ql-editor").lenght && !$(event.target).is(".ql-editor") && 
            !$(event.target).closest(".editor").lenght && !$(event.target).is(".editor")){
            let editor = $(".editor");
            let text = $(editor).find(".ql-editor").text();
            console.log($(text));
        }
    })
</script>
@endsection
