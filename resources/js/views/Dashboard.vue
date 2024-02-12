<template>
    <div class="container-fluid my-3">
        <div class="row">
            <!-- <User: Vertical Menu  -->
            <div class="col-md-3 sidebar">
                <div v-if="cookie.agent_id" class="agent-menu">
                    <h3>Agent Menu</h3>
                    <ul>
                        <li>Customers</li>
                        <li>Knowledge Hub</li>
                        <li>Analytics</li>
                    </ul>
                    <br><br>
                    <!-- <v-select v-model="selectedTicket" :items="agentTickets" item-value="user_id"
                        :item-title="item => item.user_id" label="Your Tickets">
                    </v-select> -->
                        <label for="your-tickets">Your Tickets'</label>
                        <select class="form-select form-select-lg" id="your-tickets" 
                        v-model="selectedTicket" 
                        @change="changeTicket">
                            <option selected disabled value="Here">Your Tickets</option>
                            <option v-for="agentTicket in agentTickets" :value="agentTicket.user_id">
                                {{ agentTicket.user_id }} 
                                <span class="bg-secondary">
                                    {{ getStatus(agentTicket) }}
                                    {{ getPriority(agentTicket) }}
                                </span>
                            </option>
                        </select>

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
            <div class="col-md-6 bg-secondary" 
            style="display: flex; flex-direction: column-reverse;">
                <h3>Messages</h3>
                <div class="message-box">
                    <MessageBox :messages="userChatHistory" :cookie="cookie"
                        @update:userChatHistory="(newMessage) => { this.$emit('update:userChatHistory', newMessage) }" />
                </div>
            </div>

            <!-- Agent: Ticket Controls -->
            <div class="col-md-3 right-menu">
                <v-form v-if="cookie.agent_id" v-model="valid" @submit.prevent="saveTicket">
                    <v-container>
                        <h2>Ticket Controls</h2>
                        <v-row>
                            <v-col cols="12" class="pa-0">
                                <v-text-field v-model="openOrPendingTicket.title" label="First name" required dense
                                    variant="outlined"></v-text-field>
                            </v-col>

                            <v-col cols="12" class="pa-0">
                                <v-select :items="statuses" v-model="openOrPendingTicket.status_id" item-value="value"
                                    item-title="name" label="Status" required></v-select>
                            </v-col>
                            <v-col cols="12" class="pa-0">
                                <v-select :items="priorities" v-model="openOrPendingTicket.priority_id" item-value="value"
                                    item-title="name" label="Priority" required></v-select>
                            </v-col>

                            <v-col cols="12" class="pa-0">
                                <v-select :items="categories" v-model="openOrPendingTicket.category_id" item-value="value"
                                    item-title="name" label="Category" required></v-select>
                            </v-col>

                            <v-col cols="12" class="pa-0">
                                <v-text-field v-model="openOrPendingTicket.description" label="Description" dense
                                    variant="outlined"></v-text-field>
                            </v-col>

                            <v-col cols="12" class="pa-0" lg="6">
                                <div class="form-group">
                                    <label for="start-time">Start Time</label>
                                    <input type="datetime-local" class="form-control" id="start-time"
                                        v-model="openOrPendingTicket.start_time" required>
                                </div>
                            </v-col>

                            <v-col cols="12" class="pa-0" lg="6">
                                <div class="form-group">
                                    <label for="start-time">End Time</label>
                                    <input type="datetime-local" class="form-control" id="end-time"
                                        v-model="openOrPendingTicket.end_time" disabled required>
                                </div>
                            </v-col>

                            <v-col cols="12" class="pa-0">
                                <v-btn type="button" color="primary" @click="saveTicket('update')">Update Ticket</v-btn>
                                <v-btn type="button" color="success" @click="saveTicket('close')">Close Ticket</v-btn>
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
    components: { MessageBox },
    props: {
        userChatHistory: {
            type: Array,
            default: [],
        },
        cookie: {
            type: Object,
            default: {}
        },
    },
    data: () => ({
        valid: false,
        statuses: [
            { name: 'Open', value: 1 },
            { name: 'Pending', value: 2 },
            { name: 'Closed', value: 3 },
        ],
        priorities: [
            { name: 'Low', value: 1 },
            { name: 'Medium', value: 2 },
            { name: 'High', value: 3 },
        ],
        categories: [
            { name: 'General', value: 1 },
            { name: 'Finance', value: 2 },
            { name: 'Admin', value: 3 },
        ],
        ticket: {
            title: 'xx',
            statusId: 1,
            priorityId: 1,
            categoryId: 1,
            description: '',
            startTime: new Date(moment.utc().format('YYYY-MM-DD H:m:s')),
            endTime: moment.utc().format('YYYY-MM-DD H:m:s'),
        },
        agentTickets: [],
        selectedTicket: ''
    }),
    computed: {
        openOrPendingTicket() {
            const descTickets = [...this.userChatHistory].reverse()
            const firstTicket = descTickets.find((message) => message.end_time == null && message.status_id != 3)
            return firstTicket
        },
    },
    methods: {
        // itemTitle(item) {
        //     return `${item.user_id} <span class="bg-secondary">${this.getStatus(item)}</span> ${this.getPriority(item)}`
        // },
        getStatus(item) { return this.statuses.find(({ value }) => value == item.status_id).name},
        getPriority(item) { return this.priorities.find(({ value }) => value == item.priority_id).name },
        saveTicket(action) {
            const ticket = JSON.parse(JSON.stringify(this.openOrPendingTicket))
            // console.log('ticket model', ticket)
            ticket.combinedMessages = null
            ticket.statusId = ticket.status_id
            ticket.priorityId = ticket.priority_id
            ticket.categoryId = ticket.category_id
            ticket.userId = ticket.user_id
            ticket.agentId = ticket.agent_id
            ticket.startTime = moment(ticket.start_time).format('YYYY-MM-DD H:m:s')
            if (action == 'close') {
                ticket.statusId = 3
                ticket.endTime = moment.utc().format('YYYY-MM-DD H:m:s')
            }
            console.log('this ticket.model 2', ticket)
            window.axios.put(`/api/tickets/${ticket.id}`, ticket
            ).then((res) => {
                if (action === 'close') {
                    // this.$emit('update:unresolvedUserIssues')
                    this.getAgentTickets()
                }
                console.log(res.data)
            }).catch((error) => {
                console.log(error)
            })
        },
        getAgentTickets() {
            window.axios.get(`api/tickets?agent=${this.cookie.agent_id}`
            ).then(agentTicketsResponse => {
                this.agentTickets = agentTicketsResponse.data
            }).catch(error => { console.log(error) })
        },
        changeTicket() {
            this.$emit('userChatHistoryEmitted', this.selectedTicket)
        }
    },
    created() {
        console.log('created dashboard', this.userChatHistory, this.ticketData)

    },
    mounted() {
        this.getAgentTickets()
        console.log('mounted dashboard', this.userChatHistory, this.agentTickets)
        window.Echo.channel('public-ticket').listen('.ticket', (event) => {
            console.log('ticket event', event)
            this.$emit('update:unresolvedUserIssues')
        })
    },
    watch: {
        userChatHistory(newVal, oldVal) {
            if((newVal !== oldVal) && (oldVal.length != 0) ) {
                console.log('dashboard new history', newVal)
                this.getAgentTickets()
            }
        },
        selectedTicket(val) {
            console.log('selected Ticket', val)
        }
    }
}
</script>