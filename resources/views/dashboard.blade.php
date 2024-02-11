@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid my-3">
    <div class="row">
        <!-- User: Vertical Menu -->
        @if(isset($user))
        <div class="col-md-3 sidebar">
            <h3>User Menu</h3>
            <ul>
                <li>Accounts</li>
                <li>Loans</li>
                <li>Payments</li>
                <li>FAQs and Guides</li>
            </ul>
        </div>
        <!-- Support Agent: Vertical Menu -->
        @elseif(isset($agent))
        <div class="col-md-3 sidebar">
            <h3>Agent Menu</h3>
            <ul>
                <li>Tickets</li>
                <li>Customers</li>
                <li>Knowledge Hub</li>
                <li>Analytics</li>
            </ul>
        </div>
        @endif

        <!-- Shared Message Box -->
        <div class="col-md-6 bg-secondary">
            <h3>Messages</h3>
            <div class="message-box">
            @if(isset($user))
                @include('messages.index', ['user' => $user])
            @elseif(isset($agent))
                @include('messages.index', ['agent' => $agent])
            @endif
            </div>  
        </div>

        <!-- Agent: Ticket Controls -->
        @if(isset($agent))
        <div class="col-md-3 sidebar">
            <h3>Ticket Controls</h3>
            <form action="#" method="post" id="ticketForm">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter ticket title..." value="" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" required>
                        @foreach($statuses as $status)
                        <option value="{{ $status->id}}">{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" id="priority" required>
                        @foreach($priorities as $priority)
                        <option value="{{$priority->id }}">{{$priority->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Category</label>
                    <select class="form-control" id="priority">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Description</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter ticket title...">
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" id="tags" placeholder="Enter tags...">
                </div>
                <div class="form-group">
                    <label for="start-time">Start Time</label>
                    <input type="datetime-local" class="form-control" id="start-time" required>
                </div>
                <div class="form-group">
                    <label for="end-time">End Time</label>
                    <input type="datetime-local" class="form-control" id="end-time">
                </div>
                <button type="submit" class="btn btn-primary">Create Ticket</button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>const chatHistory = @json($messages);
</script>
@endsection