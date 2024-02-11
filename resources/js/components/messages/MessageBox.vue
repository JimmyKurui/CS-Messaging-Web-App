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
                    const newMessage = {
                        id: res.data.id,
                        timestamp: res.data.timestamp,
                        body: res.data.body,
                        user_id: res.data.user_id ?? res.data.agent_id,
                        ticket_id: res.data.ticket_id,
                        isAgent: !!parseInt(res.data.agent_id),
                    }
                    this.$emit('update:userChatHistory', newMessage)
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
            return this.messages.flatMap(item => item.combinedMessages)
            
        },
    },
    created() {
        // console.log('create messageBox.', this.cookie, this.messages)

    },
    mounted() {
        window.Echo.channel('public').listen('.chat', (event) => {
            if (this.broadcast === false) {
                console.log('event emitted', event)
                const newMessage = {
                    user_id: parseInt(event.code),
                    timestamp: moment.utc().format('YYYY-MM-DD H:m:s'),
                    body: event.message,
                    isAgent: !!parseInt(event.isAgent),
                    ticket_id: parseInt(event.ticketId),
                }
                this.$emit('update:userChatHistory', newMessage)
            }
        })
        console.log('mounted MessageBox')
    }
}
</script>
