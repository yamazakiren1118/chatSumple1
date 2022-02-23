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
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form action="{{action('MessageController@create')}}" method="post">
          <textarea name="text" class="form-control mt-3" id="text"></textarea>
          <input type="hidden" name="room_id" value={{$id}}>
          {{csrf_field()}}
          <button id="post-form" class="btn mt-3">送信</button>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 offset-md-3 mt-4">
        <ul id="messages" class="list-group">
          @foreach($message as $m)
            <li class="list-group-item">
              {{$m->text}}
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <script src="{{asset('js/app.js')}}"></script>
  <script src="http://localhost:3000/socket.io/socket.io.js"></script>
  <script>
    var socket = io("http://localhost:3000");
    var id = "{{$id}}";
  </script>
  <script>
    socket.emit('room', id);
    var url = "{{action('MessageController@create')}}";
    $("#post-form").on('click',function(){
      $.ajax(url,
      {
        type: 'post',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {text: $("#text").val(),room_id: id}
      }).done(function(data){
        socket.emit('chat', {text: $("#text").val(),room_id: id});
      });
      return false;
    });
  </script>
  <script>
    socket.on('chat', function(data){
      $("#messages").append(`<li class='list-group-item'>${data}</li>`);
    });
  </script>
</body>
</html>