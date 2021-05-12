<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center">
                    <a target="_blank" href="{{ route('student.login.room') }}" class="btn btn-outline-primary btn-block">Student Login</a>
                    <a target="_blank" href="{{ route('login') }}" class="btn btn-outline-warning btn-block">Teacher Login</a>
                    <hr>
                    <h5 class="text-center">Don't have an account ?</h5>
                    <a href="{{ route('register') }}" class="text-center">Sign up now!</a>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
