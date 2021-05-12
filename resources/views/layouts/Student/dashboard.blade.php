@extends('layouts.app')
@section('navbar')
    @include('.includes.Student.navbar')
@endsection
@section('content')
<h1>This is home...</h1>

<script>
    // let presenceChannel = pusher.subscribe('presence-example');
    // presenceChannel.bind('pusher_count:subscription_succeeded', function() {
    //     let me = presenceChannel.members.me;
    //     let userId = me.id;
    //     let userInfo = me.info;
    // });
</script>
@endsection
