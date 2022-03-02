<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="{{ asset('css/app.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <nav class="navbar bg-dark navbar-dark">
    <p class="navbar-brand mb-0">チャットアプリ</p>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form action="{{action('MessageController@create')}}" method="post">
          <textarea name="text" class="form-control mt-3" id="text"></textarea>
          <input type="hidden" name="room_id" value={{$id}}>
          {{csrf_field()}}
          <button id="post-form" class="btn mt-3 btn-primary">送信</button>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 offset-md-3 mt-4">
        <ul id="messages" class="row m-0 p-0">

          @foreach($message as $m)
            <div class="card no-{{$m->id}} col-md-12 p-0">
              <div class="card-body">
                <p class="card-text">{{$m->text}}</p>
              </div>
              <div class="card-footer text-muted">
                <a class="delete-link btn btn-danger btn-sm" href="{{action('MessageController@delete', ['id' => $m->id])}}" data-id="{{$m->id}}">削除</a>
              </div>
            </div>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <script src="{{asset('js/app.js')}}"></script>
  <script src="{{$url . '/socket.io/socket.io.js'}}"></script>
  <!-- <script src="https://polar-retreat-24639.herokuapp.com/socket.io/socket.io.js"></script> -->
  <script>
    // var option = {
    //   reconnection: true
    // }
    var socket = io("{{$url}}");
    // var socket = io("https://polar-retreat-24639.herokuapp.com",{transports:['websocket']});
    var id = "{{$id}}";
  </script>
  <script>
    socket.emit('room', id);
    var url = "{{action('MessageController@create')}}";
    var d_url = "{{action('MessageController@delete')}}";
    $("#post-form").on('click',function(){
      $.ajax(url,
      {
        type: 'post',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {text: $("#text").val(),room_id: id}
      }).done(function(data){
        socket.emit('chat', {text: $("#text").val(), room_id: id, id: data['id']});
      });
      return false;
    });
    $(document).on('click', ".delete-link", function(){
      $.ajax(d_url,
        {
          type: 'get',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: {id: $(this).attr('data-id')}
        }
      ).done(function(data){
        socket.emit('chat_d', {room_id: id, id: data['id']});
      });
      return false;
    });
  </script>
  <script>
    socket.on('chat', function(data){

      var x = `
      <div class="card no-${data['id']} col-md-12 p-0">
        <div class="card-body">
          <p class="card-text">${data['text']}</p>
        </div>
        <div class="card-footer text-muted">
          <a class="delete-link btn btn-danger btn-sm" href="${d_url}?id=${data['id']}" data-id="${data['id']}">削除</a>
        </div>
      </div>
      `;
      $("#messages").prepend(x);
    });
    socket.on('chat_d', function(data){
      $(`.no-${data['id']}`).remove();
    })
    socket.on('connect',function(){
      console.log('OK connection');
      socket.emit('room', id);
    });
    socket.on('disconnect',function(){
      console.log('DisConnect');
    });
  </script>
</body>
</html>