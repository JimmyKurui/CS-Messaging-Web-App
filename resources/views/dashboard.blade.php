@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid my-3">
    <div class="row">
        <!-- User Column 1: Vertical Menu -->
        @if(isset($user))
        <div class="col-md-3 sidebar">
            <h3>User Menu</h3>
            <ul>
                <li>Accounts</li>
                <li>Loans</li>
                <li>Payments</li>
                <li>FAQs and Guides</li>
                <!-- Add more menu items here -->
            </ul>
        </div>
        @elseif(isset($agent))
        <div class="col-md-3 sidebar">
            <h3>Agent Menu</h3>
            <ul>
                <li>Tickets</li>
                <li>Customers</li>
                <li>Knowledge Hub</li>
                <li>Analytics</li>
                <!-- Add more menu items here -->
            </ul>
        </div>
        @endif

        <!-- User/Agent Column 2: Message Box -->
        <div class="col-md-6 bg-secondary">
            <h3>Messages</h3>
            <div class="message-box">
                @include('messages.index')
            </div>  
        </div>

        <!-- Agent Column 3: Ticket Controls -->
        @if(isset($agent))
        <div class="col-md-3 sidebar">
            <h3>Ticket Controls</h3>
            <form action="#" method="post">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" id="priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" class="form-control" id="tags" placeholder="Enter tags...">
                </div>
                <div class="form-group">
                    <label for="start-time">Start Time</label>
                    <input type="datetime-local" class="form-control" id="start-time">
                </div>
                <div class="form-group">
                    <label for="end-time">End Time</label>
                    <input type="datetime-local" class="form-control" id="end-time">
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter ticket title...">
                </div>
                <button type="submit" class="btn btn-primary">Create Ticket</button>
            </form>
        </div>
        @endif
    </div>
</div>

@endsection