<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <router-link class="navbar-brand p-4" to="/">Customer Support</router-link>
        <!-- <a  href="#"></a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-md-5">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
            </ul>
        </div>
        <!-- <v-select v-if="cookie.agent_id" 
        :items="unresolvedUserIssues" 
        v-model="selectedUnresolvedUser"
        item-value="key"
        item-text="value"
        label="Notifications">
        </v-select> -->
        <div v-if="cookie.agent_id" class="dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i>
                <span class="badge badge-danger">Issue Notifications</span>
            </a>
            <form action="" @submit.prevent="">
                <div class="dropdown-menu dropdown-menu-right" 
                style="height: 50vh; overflow-y: auto"
                aria-labelledby="dropdownMenu2"
                >
                    <a @click.prevent href="#" disabled><span>Order: By Oldest</span></a>
                    <a v-for="user in innerNotifications" @click.prevent="chooseUser(user)" href="#" class="dropdown-item">
                        {{ user.key }} - 
                        <span class="text-black-50">{{ user.text }}</span> 
                        <span v-if=" user.priority == 'High'"  class="badge badge-danger">{{ user.priority }}</span>
                        <span v-if=" user.priority == 'Medium'" class="badge badge-warning">{{ user.priority }}</span>
                    </a>
                </div>
            </form>
        </div>
        <div class="mx-3 pa-2 bg-info">
            ID: {{ cookie.user_id || cookie.agent_id }}
        </div>
    </nav>
</template>

<script>
export default {
    props: {
        cookie: {
            type: Object,
            default: {}
        },
        unresolvedUserIssues: {
            type: Array,
            default:[], 
        },
    },
    data() {
        return {
            selectedUnresolvedUser: 0,
            priorityEnum: {
                1: 'Low;',
                2: 'Medium',
                3: 'High',
            },
        }
    },
    computed: {
        innerNotifications() {
            return this.unresolvedUserIssues.map(obj => {
                return {
                    ...obj,
                    priority: this.priorityEnum[obj.priority]
                };
            });
        }
    },
    methods: {
        chooseUser(user) {
            console.log('user',user)
            window.axios.get(`/api/support?id=${user.key}`)
            .then(conversations => {
                // const combinedMessageText = conversations.data.reduce((messageText, note) => {
                //     if(!note.isAgent) { 
                //         messageText += note.body
                //     }
                //     return messageText
                // }, '')

                // const messageIds = conversations.data.map((note) => {if(!note.isAgent) return note.id; })
                // console.log('combinedMessage', combinedMessageText, messageIds)

                // window.axios.post(`/api/tickets?autoTicket`, {
                //     userId: user,
                //     agentId: this.cookie.agent_id,
                //     message: combinedMessageText,
                //     messageIds
                // }).then(autoTicket => {
                //     console.log('auto ticket', autoTicket.data)
                //     this.$emit('autoTicketCreated', autoTicket.data)
                // }).catch(error => console.log(error))
                window.axios.patch(`/api/tickets/${user.ticketId}`, {
                    agentId: this.cookie.agent_id,
                    statusId: 2
                }).then(res => {
                    this.$emit('update:unresolvedUserIssues')
                    this.$emit('userConversationEmitted', conversations.data)
                })

            }).catch(error => console.log(error))
        },
        unticketedUserMessages(userConversation) {
            // userConversation
        }
    },
    mounted() {
        console.log('navb', this.innerNotifications)
    },
}
</script>
