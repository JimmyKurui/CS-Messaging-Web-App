<div class="chat">
  <div class="top">
    <img class="w-25" src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f" alt="">
    <div>
      <p>Jimmy Chep</p>
      <small>Online</small>
    </div>
  </div>

  <div class="messages">
    @include('messages.receive', ['message' => "Hey! What's the issue?"])
    @include('messages.broadcast', ['message' => "Ask a friend to open this link and you can chat with them!"])
  </div>

  <div class="bottom">
    <form action="">
      <input type="text" id="message" name="message" placeholder="Enter message">
      <button type="submit"></button>
    </form>
  </div>
</div>

<script>
  const pusher = new Pusher('{{config('
    broadcasting.connections.pusher.key ')}}', {
      cluster: 'eu'
    });
  const channel = pusher.subscribe('public');

  //Receive messages
  channel.bind('chat', function(data) {
    $.post("/receive", {
        _token: '{{csrf_token()}}',
        message: data.message,
      })
      .done(function(res) {
        $(".messages > .message").last().after(res);
        $(document).scrollTop($(document).height());
      });
  });

  //Broadcast messages
  $("form").submit(function(event) {
    event.preventDefault();

    $.ajax({
      url: "/messages/broadcast",
      method: 'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data: {
        _token: '{{csrf_token()}}',
        message: $("form #message").val(),
      }
    }).done(function(res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });
</script>
