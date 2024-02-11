<template>
    <div class="chat">
        <div class="top">
            <img class="w-25"
                src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f"
                alt="">
            <div>
                <p id="ownerId">{{ cookie.agent_id ? "User" : "Agent" }}</p>
                <small>Online</small>
            </div>
        </div>

        <div v-for="message in ascOrderedMessages" class="messages">
            <div v-if="cookie.agent_id">
                <Sender v-if="message.isAgent" :message="message.body" :code="message.user_id" />
                <Receiver v-else :message="message.body" :code="message.user_id" />
            </div>
            <div v-else>
                <Receiver v-if="message.isAgent" :message="message.body" :code="message.user_id" />
                <Sender v-else :message="message.body" :code="message.user_id" />
            </div>
        </div>

        <div class="bottom">
            <form id="messageForm" @submit.prevent="submitMessage" ref="form">
                <input type="text" id="messageInput" v-model="newMessage" placeholder="Enter message" required>
                <button type="submit"></button>
            </form>
            <!-- <v-form v-model="valid" ref="form" lazy-validation id="messageForm">
                <v-text-field label="Enter message" v-model="name" required id="messageInput"></v-text-field>

                <v-btn @click="submit" :disabled="!valid"></v-btn>
            </v-form> -->
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import Receiver from './Receiver.vue';
import Sender from './Sender.vue'

export default {
    components: { Receiver, Sender },
    props: {
        messages: {
            type: Array,
            default: [],
        },
        cookie: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            newMessage: '',
            broadcast: false
        }
    },
    methods: {
        submitMessage() {
            console.log('clicked form', this.ascOrderedMessages)
                console.log('clicked form next tick', this.ascOrderedMessages)
                let code = this.cookie.user_id
                let route = 'messages'
                this.broadcast = true
                if (this.cookie.agent_id) {
                    code = this.cookie.agent_id
                    route = 'ticket-messages'
                }
                window.axios.post(`api/${route}`, {
                    message: this.newMessage,
                    code,
                    broadcast: this.broadcast,
                    ticket_id: this.ascOrderedMessages[this.ascOrderedMessages.length - 1].ticket_id,
                    isAgent: this.cookie.agent_id ? 1 : 0,
                }).then(res => {
                    this.messages.push({
                        user_id: parseInt(this.cookie.agent_id ?? this.cookie.user_id),
                        timestamp: moment.utc().format('YYYY-MM-DD H:m:s'),
                        body: this.newMessage,
                        isAgent: this.cookie.agent_id ? true : false,
                        ticket_id: this.ascOrderedMessages[this.ascOrderedMessages.length - 1].ticket_id,
                    })
                    this.broadcast = false
                    this.newMessage = ''
                    console.log(this.ascOrderedMessages)
                }).catch(error => {
                    console.error('Error broadcasting message:', error);
                });
        },
    },
    computed: {
        ascOrderedMessages() {
            const messages = [...this.messages].sort((a, b) => {
                // Convert timestamp strings to Date objects for comparison
                const timestampA = new Date(a.timestamp);
                const timestampB = new Date(b.timestamp);

                if (timestampA < timestampB) {
                    return -1;
                } else if (timestampA > timestampB) {
                    return 1;
                } else {
                    return 0;
                }
            })
            return messages
        }
    },
    created() {
        // console.log('create messageBox.', this.cookie, this.messages)

    },
    mounted() {
        window.Echo.channel('public').listen('.chat', (event) => {
            if (this.broadcast === false) {
                console.log('event emitted', event)
                this.messages.push({
                    user_id: event.code,
                    timestamp: moment.utc().format('YYYY-MM-DD H:m:s'),
                    body: event.message,
                    isAgent: event.isAgent,
                    ticket_id: event.ticketId,
                })
                console.log(this.ascOrderedMessages)
            }
        })
        // console.log('After event ctrl', (broadcast? 'true': 'false'))
    }
}
</script>
