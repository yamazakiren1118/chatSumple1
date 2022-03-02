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
        <form action="{{action('RoomController@create')}}" method="post" id="form">
          <input type="text" name="name" id="room" class="form-control mt-3">
          
          {{csrf_field()}}
          <button id="room-form" class="btn mt-3 btn-primary">ルームを作成</button>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div id="messages" class="list-group mt-4">

          @foreach($room as $r)
            <div class="card item-{{$r->id}}">
              <a href="{{action('RoomController@show', $r->id)}}" class="card-body">
                <p class="card-text">{{$r->name}}</p>
              </a>
              <div class="card-footer">
                <a class="delete-link text-danger" href="{{action('RoomController@delete', ['id' => $r->id])}}"  data-id="{{$r->id}}">削除</a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <script>
    var url = "{{action('RoomController@create')}}";
    var d_url = "{{action('RoomController@delete')}}";
    $("#room-form").on('click', function(){
      $.ajax(url,
        {
          type: 'post',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: {name: $('#room').val()}
        }
      ).done(function(data){
        var a = 
        `
        <div class="card item-${data['id']}">
          <a href="${data['url']}" class="card-body">
            <p class="card-text">${data['name']}</p>
          </a>
          <div class="card-footer">
            <a class="delete-link text-danger" href="${d_url}?id=${data['id']}"  data-id="${data['id']}">削除</a>
          </div>
        </div>
        `;
        $("#messages").prepend(a);
        console.log(data);
      });
      return false;
    });

    $(document).on('click', '.delete-link', function(){
      $.ajax($(this).attr('href'),
        {
          type: 'delete',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: {id: $(this).attr('data-id')}
        }
      ).done(function(data){
        $('.item-' + data['id']).remove();
      })
        return false;
    })
  </script>
  <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
