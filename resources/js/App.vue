<template>
    <v-app>
        <NavBar 
        :unresolvedUserIssues="unresolvedUserIssues" 
        :cookie="cookie"
        @userConversationEmitted="loadUserChatHistory" 
        @autoTicketCreated="loadTicket"
        @update:unresolvedUserIssues = "getUnassignedUserIssues" />

        <router-view 
        @userChatHistoryEmitted="loadUserChatHistory" 
        @update:userChatHistory="updateUserChatHistory"
        @update:unresolvedUserIssues = "getUnassignedUserIssues"
        :userChatHistory="userChatHistory" 
        :ticketData="ticketData" 
        :cookie="cookie"></router-view>
    </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue';

export default {
    components: { NavBar },
    data() {
        return {
            unresolvedUserIssues: [],
            userChatHistory: [],
            cookie: {
                agent_id: null,
                user_id: null
            },
        }
    },
    methods: {
        loadUserChatHistory(data) {
            if (typeof data != 'object') {
                window.axios.get(`api/support?id=${data}`
                ).then(userConversations => {
                    this.userChatHistory = userConversations.data
                }).catch(err => {console.log(err)})
            } else {
                this.userChatHistory = data
                this.cookie.user_id = this.$cookies.get('user_id');
                this.cookie.agent_id = this.$cookies.get('agent_id');
                if (!Object.values(this.cookie).includes(null)) {
                    console.log('Cookie assignment problem')
                    this.$router.push('/')
                }
            } 
        },
        async loadTicket(data) {
            try {
                const response = await axios.get(`/api/tickets/${data.ticket_id}`);
                const newTicket = await response.data;
                console.log('newTicket', newTicket)
                return newTicket;
            } catch (error) {
                console.log(error);
                throw error;
            }
        },
        updateUserChatHistory(newChatMessage) {
            const updatedUserChatHistory = JSON.parse(JSON.stringify(this.userChatHistory))
            const ticketForUpdateIndex = this.userChatHistory.findIndex(ticket => ticket.id === newChatMessage.ticket_id);
            if (ticketForUpdateIndex == -1) {
                // const newTicketChat = this.loadTicket(newChatMessage)
                // newTicketChat.combinedMessages = []
                // newTicketChat.combinedMessages.push(newChatMessage)
                // console.log('newTicketUpdate', newTicketChat)
                // updatedUserChatHistory.push(newTicketChat)
                // this.getUnassignedUserIssues()
                window.axios.get(`api/support?id=${newChatMessage.user_id}`
                ).then(userConversations => {
                    this.userChatHistory = userConversations.data
                    console.log('updatedHistory', this.userChatHistory)
                })
            } else {
                updatedUserChatHistory[ticketForUpdateIndex].combinedMessages.push(newChatMessage)
                this.userChatHistory = updatedUserChatHistory
            }
            // console.log('oldHistory', this.userChatHistory)
        
        },
        getUnassignedUserIssues() {
            window.axios.get('/api/messages?noTicket')
                .then(ticketChatResponse => {
                    let issueNotifications = [];
                    [...ticketChatResponse.data].forEach(obj => {
                        let issueCount = obj.messages.reduce((countMap, obj) => {
                            const userId = obj.user_id;
                            countMap[userId] = (countMap[userId] || 0) + 1;
                            return countMap;
                        }, []);
                        issueCount = Object.entries(issueCount).map(([key, value]) => ({ 'key': key, 'text': value, priority: obj.priority_id, ticketId: obj.id }))
                        issueNotifications.push(issueCount)
                    })
                    this.unresolvedUserIssues = issueNotifications.flat()
                    console.log(this.unresolvedUserIssues)
                }).catch(error => { console.log(error) })
        }
    },
    mounted() {
        //   this.noDataText = this.$vuetify.t(this.noDataText);
        this.cookie.user_id = this.$cookies.get('user_id')
        this.cookie.agent_id = this.$cookies.get('agent_id')
        this.getUnassignedUserIssues()
    }
}
</script>
