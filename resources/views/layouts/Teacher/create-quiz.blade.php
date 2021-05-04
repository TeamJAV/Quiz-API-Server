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
        <div class="card" style='border-radius:5px; border:none'>
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

<div>
            <form method="post" id="quiz">
                {% csrf_token %}
                <div class="row">
                    <div class="col-xl d-inline-flex p-2">
                        <div class="p-2 position-relative quiz-title-area">
                            <label for="quiz-title"><i class="fas fa-pen" style="font-size: 20px; color: #ababab"></i></label>
                            <input type="text" name="quiz-title" id="quiz-title" class="quiz_title"
                                   placeholder="Title" required>

                        </div>
                    </div>
                    <div class="col-xl p-2">
                        <div class="p-2 float-right">
                            <button type="submit" id="saveandexit" class="btn btn-primary"
                                    style="border-radius: 20px; font-size:26px">
                                Save and exit
                            </button>
                        </div>
                    </div>
                </div>
                <br><br>

                {#form question#}
                <div>
                    {# question title #}
                    <div class="form_question" id="form_question"></div>
                    <br>

                    <div class="text-center"><h2>Add a question</h2></div>


                    <div class="d-flex justify-content-center">
                            <div class="form_question" id="form_question"></div>
                                    <br>
                            <div class="text-center">
                                <button type="button" id="add_question" class="btn-add-question" style="font-size:15px; margin-right: 15px">Multiple Choice
                                </button>
                            </div>

                            <div class="text-center">
                                <button type="button" id="add_question" class="btn-add-question" style="font-size:15px; margin-right: 15px">True False
                                </button>
                            </div>

                            <div class="text-center">
                                <button type="button" id="add_question" class="btn-add-question" style="font-size:15px">Short Answer
                                </button>
                            </div>

                    </div>
                </div>

            </form>
        </div>
        <script>
            const qtnList = document.getElementById("form_question");
            const addQtnBtn = document.getElementById("add_question");
            const delQtnBtn = document.querySelector(".remove_question");

            addQtnBtn.addEventListener("click", () => {

                let qtnId = 0;

                if (qtnList.lastChild !== null) {
                    qtnId = parseInt(qtnList.lastChild.id) + 1;
                }

                const qtnForm = document.createElement("div");
                qtnForm.setAttribute("class", "qtn-form");
                qtnForm.setAttribute("id", `${qtnId}`)
                const qtnFormContent = `
                        <div class="row">
                            <div class="col-xl-1 qs_label">
                                <label for="question_title" class="incr">${qtnId + 1}</label>
                            </div>
                            <div class="col-xl-7 qs">
                                <input type="text" id="question_title" name="question_title"
                                       placeholder="Have a multiple-choice question to ask?"
                                       class="question_title form-control inline">
                                <br>
                                <div class="question_wrapper" id="all_answer"></div>
                                <button class="add_selector" type="button">&#43;Add Answer</button>
                                <div class="explain">
                                    <input type="text" class="question_explain" id="exp"
                                           placeholder="An explaination, if you like">
                                    <label for="exp" class="label_explain">i</label>
                                </div>
                            </div>
                            <div class="col-xl-4 qs">
                            <div class="upload-images">
                                <div class="wrapper">
                                    <div class="image">
                                    <img src="" alt="">
                                    </div>
                                    <div class="content">
                                    <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <div class="text">No file chosen!</div>
                                    </div>
                                    <div id="cancel-btn"><i class="fas fa-times"></i></div>
                                </div>
                                <button onclick="defaultBtnActive()" id="custom-btn">Upload image</button>
                                <input id="default-btn" type="file" hidden>
                            </div>

                                    <a href="javascript:void(0);" for="question_title" class="remove_question"></a>
                            </div>
                            <div class="end-question"></div>
                        </div>
        `;

                qtnForm.innerHTML = qtnFormContent;
                qtnList.appendChild(qtnForm);

            })
            qtnList.addEventListener("click", (event) => {
                const target = event.target;
                const targetClass = target.className;
                if (targetClass === null) return;

                if (targetClass === "remove_question") {
                    console.log(target)
                    deleteQuestion(target);
                }
                if (targetClass === "add_selector") {
                    addAnswer(target);
                }
                if (targetClass === "remove_button") {
                    deleteAnswer(target);
                }
            })

            const deleteQuestion = (target) => {
                const currentQtn = target.parentNode.parentNode.parentNode;
                console.log(currentQtn)
                let lastQtn = qtnList.lastChild;

                while (lastQtn !== currentQtn) {
                    const prevQtn = lastQtn.previousSibling;
                    lastQtn.setAttribute("id", `${prevQtn.id}`);
                    lastQtn.querySelector(".incr").textContent = `${parseInt(prevQtn.id) + 1}`;
                    lastQtn = prevQtn;
                }
                qtnList.removeChild(currentQtn);
            }

            const addAnswer = (target) => {
                const currentQtn = target.parentNode.parentNode;
                const ansList = currentQtn.querySelector(".question_wrapper");
                if (ansList.childElementCount < 26) {
                    let ansId = "A";
                    if (ansList.lastChild !== null) {
                        ansId = nextChar(ansList.lastChild.id); //Khi vượt quá "z", hàm này sẽ trả về "{", "}"...
                    }
                    const ansForm = document.createElement("div");
                    ansForm.setAttribute("class", "test");
                    ansForm.setAttribute("id", `${ansId}`)
                    const ansFormContent = `
            <label for="slt" class="incr2">${ansId}</label>
            
            <label class="round qs_correct">
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>
            <input type="text" id="slt" name="question" placeholder="Answer..." class="question_selector inline sel_data" style="width=60%">
            <a class="remove_button"></a>

        `;

                    ansForm.innerHTML = ansFormContent;
                    ansList.appendChild(ansForm);
                }

            }

            const deleteAnswer = (target) => {
                const currentAns = target.parentNode;
                console.log(currentAns)
                const ansList = currentAns.parentNode;
                let lastAns = ansList.lastChild;
                while (lastAns !== currentAns) {
                    const prevAns = lastAns.previousSibling;
                    lastAns.setAttribute("id", `${prevAns.id}`);
                    lastAns.querySelector(".incr2").textContent = `${prevAns.id}`;
                    lastAns = prevAns;
                }
                ansList.removeChild(currentAns);
            }


            /** ------------------------------------------------------- */
            const nextChar = (c) => {
                return String.fromCharCode(c.charCodeAt(0) + 1).toUpperCase();
            }


            const wrapper = document.querySelector(".wrapper");
            const fileName = document.querySelector(".file-name");
            const defaultBtn = document.querySelector("#default-btn");
            const customBtn = document.querySelector("#custom-btn");
            const cancelBtn = document.querySelector("#cancel-btn i");
            const img = document.querySelector("img");
            let regExp = /[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
            function defaultBtnActive(){
                defaultBtn.click();
            }
            defaultBtn.addEventListener("change", function(){
                const file = this.files[0];
                if(file){
                const reader = new FileReader();
                reader.onload = function(){
                    const result = reader.result;
                    img.src = result;
                    wrapper.classList.add("active");
                }
                cancelBtn.addEventListener("click", function(){
                    img.src = "";
                    wrapper.classList.remove("active");
                })
                reader.readAsDataURL(file);
                }
                if(this.value){
                let valueStore = this.value.match(regExp);
                fileName.textContent = valueStore;
                }
            });
	
        </script>
        
@endsection
