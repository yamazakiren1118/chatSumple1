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
        <form action="{{action('RoomController@create')}}" method="post" id="form">
          <input type="text" name="name" id="room" class="form-control mt-3">
          
          {{csrf_field()}}
          <button id="room-form" class="btn mt-3">ルームを作成</button>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div id="messages" class="list-group mt-4">
          @foreach($room as $r)
            <a class="list-group-item" href="{{action('RoomController@show', $r->id)}}">{{$r->name}}</a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <script>
    var url = "{{action('RoomController@create')}}";
    $("#room-form").on('click', function(){
      $.ajax(url,
        {
          type: 'post',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: {name: $('#room').val()}
        }
      ).done(function(data){
        var a = `<a class="list-group-item" href="${data['url']}"> ${data['name']}</a>`;
        $("#messages").append(a);
        console.log(data);
      });
      return false;
    });

  </script>
  <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
