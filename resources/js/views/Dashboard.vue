<template>
    <div class="container-fluid my-3">
        <div class="row">
            <!-- <User: Vertical Menu  -->
            <div class="col-md-3 sidebar">
                <div v-if="cookie.agent_id" class="agent-menu">
                    <h3>Agent Menu</h3>
                    <ul>
                        <li>Tickets</li>
                        <li>Customers</li>
                        <li>Knowledge Hub</li>
                        <li>Analytics</li>
                    </ul>
                </div>
                <div v-else class="user-menu">
                    <h3>User Menu</h3>
                    <ul>
                        <li>Accounts</li>
                        <li>Loans</li>
                        <li>Payments</li>
                        <li>FAQs and Guides</li>
                    </ul>
                </div>
            </div>

            <!-- Shared Message Box -->
            <div class="col-md-6 bg-secondary">
                <h3>Messages</h3>
                <div class="message-box">
                    <MessageBox :messages="userChatHistory" :cookie="cookie" />
                </div>
            </div>

            <!-- Agent: Ticket Controls -->
            <div  class="col-md-3 right-menu">
                <div v-if="cookie.agent_id" class="ticket-menu">
                    <h3>Ticket Controls</h3>
                    <form action="#" method="post" id="ticketForm">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" placeholder="Enter ticket title..." value=""
                                required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" required>
                                <!-- @foreach($statuses as $status) -->
                                <!-- <option value="{{ $status->id}}">{{ $status -> name }}</option> -->
                                <!-- @endforeach -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control" id="priority" required>
                                <!-- @foreach($priorities as $priority) -->
                                <!-- <option value="{{$priority->id }}">{{ $priority -> name }}</option> --> -->
                                <!-- @endforeach -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority">Category</label>
                            <select class="form-control" id="priority">
                                <!-- @foreach($categories as $category) -->
                                <!--  <option value="{{ $category->id }}">{{ $category -> name }}</option> -->
                                <!-- @endforeach -->
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
            </div>
        </div>
    </div>
</template>

<script>
import MessageBox from '../components/messages/MessageBox.vue'

export default {
    components: { MessageBox},
    props: {
        userChatHistory: {
            type: Array,
            default: [],
        },
        cookie: {
            type: Object,
            default: {}
        }
    },    
    mounted() {
        console.log('mounted dashboard', this.cookie, this.userChatHistory)
    },
    watch: {
        cookie(val) {
            console.log('dashboard watch cookie ', this.cookie, val)
        },
        userChatHistory(newval) {
            console.log('dashboard new history', newval)
            
        } 
    }
}
</script>