<div class="chat">
  <div class="top">
    <img class="w-25" src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f" alt="">
    <div>
      <p id="ownerId">{{ isset($agent) ? "Agent ".$agent->id : "User ".$user->id }}</p>
      <small>Online</small>
    </div>
  </div>

  <div class="messages">
    @if(isset($user))
      @foreach($messages as $message)
        @include('messages.broadcast', ['message' => $message->message])
      @endforeach
    @else
      @include('messages.broadcast', ['message' => "Hey, what's the issue!"])
    @endif 

  </div>

  <div class="bottom">
    <form id="messageForm" method="post">
      @csrf
      <input type="text" id="messageInput" name="message" placeholder="Enter message" >
      <button type="submit"></button>
    </form>
  </div>
</div>

<script>const owner = @json($agent ?? $user);  </script>
