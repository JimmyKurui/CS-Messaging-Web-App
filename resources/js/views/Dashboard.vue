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
                    <MessageBox 
                    :messages="userChatHistory" 
                    :cookie="cookie"
                    @update:userChatHistory="(newMessage) => {this.$emit('update:userChatHistory', newMessage)}" />
                </div>
            </div>

            <!-- Agent: Ticket Controls -->
            <div  class="col-md-3 right-menu">
                <v-form v-if="cookie.agent_id" v-model="valid"  @submit.prevent="saveTicket">
                    <v-container>
                    <v-row>
                        <v-col cols="12" class="pa-0" >
                            <v-text-field
                                v-model="openOrPendingTicket.title"
                                label="First name"
                                required
                                dense
                            variant="outlined"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" class="pa-0">
                            <v-select
                                :items="statuses"
                                v-model="openOrPendingTicket.status_id"
                                item-value="value"
                                item-title="name"
                                label="Status"
                                required
                            ></v-select>
                        </v-col>
                        <v-col cols="12" class="pa-0">
                            <v-select
                                :items="priorities"
                                v-model="openOrPendingTicket.priority_id"
                                item-value="value"
                                item-title="name"
                                label="Priority"
                                required
                            ></v-select>
                        </v-col>

                        <v-col cols="12" class="pa-0">
                            <v-select
                                :items="categories"
                                v-model="openOrPendingTicket.category_id"
                                item-value="value"
                                item-title="name"
                                label="Category"
                                required
                            ></v-select>
                        </v-col> 

                       <v-col cols="12" class="pa-0">
                            <v-text-field
                                v-model="openOrPendingTicket.description"
                                label="Description"
                                dense
                                variant="outlined"
                            ></v-text-field>
                        </v-col> 

                        <v-col cols="12" class="pa-0" md="6">
                            <div class="form-group">
                                <label for="start-time">Start Time</label>
                                <input type="datetime-local" class="form-control" id="start-time" required>
                            </div>
                        </v-col>

                        <v-col cols="12" class="pa-0" md="6">
                            <div class="form-group">
                                <label for="start-time">End Time</label>
                                <input type="datetime-local" class="form-control" id="end-time" disabled required>
                            </div>
                        </v-col>

                        <v-col cols="12" class="pa-0">
                            <v-btn type="submit" color="success">Save</v-btn>
                        </v-col>
                    </v-row>
                    </v-container>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
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
        },
        ticketData: {
            type: Object,
            default: {}
        },
    },
    data: () => ({
        valid: false,
        statuses: [
            {name :'Open', value: 1},  
            {name :'Pending', value: 2},  
            {name :'Closed', value: 3}, 
        ],
        priorities: [
            {name :'Low', value: 1},  
            {name :'Medium', value: 2},  
            {name :'High', value: 3}, 
        ],
        categories: [
            {name :'General', value: 1},  
            {name :'Finance', value: 2},  
            {name :'Admin', value: 3}, 
        ],
        ticket: {
            title: 'xx',
            status_id: 1,
            priority_id: 1,
            category_id: 1,
            description: '',
            start_time: new Date(moment.utc().format('YYYY-MM-DD H:m:s')),
            end_time: moment.utc().format('YYYY-MM-DD H:m:s'),
        },
        innerTicketData: {}
    }),
    computed: {
        innerTicketData: {
            get() { return this.ticketData},
            set(newVal) {
                // this.tick
            } 
        },
        formattedDate() {
            return this.ticket.start_time.toLocaleDateString('en-US')
        },
        openOrPendingTicket() {
            const descTickets =  [...this.userChatHistory].reverse()
            const firstTicket = descTickets.find((message) => message.end_time == null && message.status_id != 3 )
            return firstTicket
        },
    },
    methods: {
        saveTicket() {
            console.log('saved ticket', this.ticket.start_time)
        },
    },
    created() {

    },
    mounted() {
        console.log('openOrPendingTicket', this.openOrPendingTicket)
        console.log('mounted dashboard', this.userChatHistory, this.ticketData)
    },
    watch: {
        ticketData(newVal) {
            console.log('dashboard watch ticket ', newVal)
            newVal.start_time =  new Date(newVal.start_time)
            newVal.end_time =  new Date(newVal.end_time)
            this.ticket = newVal
            console.log('updated ticket', this.ticket)
        },
        userChatHistory(newVal) {
            console.log('dashboard new history', newVal)
            
        } 
    }
}
</script>