<template>
    <v-app>
        <nav-bar :cookie="cookie" @userConversationEmitted="loadUserChatHistory" @autoTicketCreated="loadTicket" />
        <router-view 
        @userChatHistoryEmitted="loadUserChatHistory" 
        @update:userChatHistory="updateUserChatHistory"
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
            unresolvedChatHistory: [],
            userChatHistory: [],
            cookie: {
                agent_id: null,
                user_id: null
            },
            ticketData: {},
        }
    },
    methods: {
        loadUserChatHistory(data) {
            this.userChatHistory = data
            this.cookie.user_id = this.$cookies.get('user_id');
            this.cookie.agent_id = this.$cookies.get('agent_id');
            if (!Object.values(this.cookie).includes(null)) {
                console.log('Cookie assignment problem')
                this.$router.push('/')
            }
            console.log('load chat history', this.cookie, this.userChatHistory)
        },
        loadTicket(data) {
            console.log('load ticket')
            this.ticketData = data
        },
        updateUserChatHistory(data) {
            const updatedUserChatHistory = JSON.parse(JSON.stringify(this.userChatHistory))
            const ticketForUpdateIndex = this.userChatHistory.findIndex(ticket => ticket.id === data.ticket_id);
            updatedUserChatHistory[ticketForUpdateIndex].combinedMessages.push(data)
            console.log('oldHistory', this.userChatHistory[ticketForUpdateIndex].combinedMessages.length)
            this.userChatHistory = updatedUserChatHistory
            console.log('updatedHistory', this.userChatHistory[ticketForUpdateIndex].combinedMessages.length)
        }
    },
    mounted() {
        //   this.noDataText = this.$vuetify.t(this.noDataText);
        this.cookie.user_id = this.$cookies.get('user_id')
        this.cookie.agent_id = this.$cookies.get('agent_id')
        console.log('mounted app', this.ticketData, this.userChatHistory)
    }
}
</script>
