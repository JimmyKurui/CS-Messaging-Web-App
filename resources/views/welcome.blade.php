    @extends('layouts.app')

    @section('content')

    <section class="hero">
        <h2>Welcome to Customer Support !</h2>
        <p class="mb-2">Please choose below your service for this test application. No authentication is needed for this</p>
    </section>
    <section class="call-to-action">
        <ul class="nav nav-tabs centered-buttons" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="agent-tab" data-toggle="tab" href="#agent" role="tab" aria-controls="agent" aria-selected="false">Agent</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                <p>Prompt for user to select a numerical ID:</p>
                <form method="POST" action="/support" id="userForm">
                    @csrf
                    <select class="form-control" name="user-id" id="userSelect">
                        @foreach($users as $user)
                        <option value="{{ $user }}">{{ $user->id }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="tab-pane fade" id="agent" role="tabpanel" aria-labelledby="agent-tab">
                <p>Prompt for agent to select a numerical ID:</p>
                <form method="POST" action="/dashboard" id="agentForm">
                    @csrf
                    <select class="form-control" name="agent-id" id="agentSelect">
                        @foreach($agents as $agent)
                        <option value="{{ $agent }}">{{ $agent->id }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </section>


<script>
    document.getElementById('agentSelect').addEventListener('change', function() {
        document.getElementById('agentForm').submit();
    });
    document.getElementById('userSelect').addEventListener('change', function() {
        document.getElementById('userForm').submit();
    });
</script>

@endsection