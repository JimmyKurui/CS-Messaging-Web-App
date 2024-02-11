<template>
    <v-app>
        <nav-bar :cookie="cookie" @userConversationEmitted="loadUserChatHistory"/>
        <router-view @userChatHistoryEmitted="loadUserChatHistory" 
        :userChatHistory="userChatHistory" 
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
                user_id : null
            }
        }
    },
    methods: {
        loadUserChatHistory(data) {
            console.log('load chat cookies', this.cookie, this.$cookies.keys())
            this.userChatHistory = data
            this.cookie.user_id = this.$cookies.get('user_id');
            this.cookie.agent_id = this.$cookies.get('agent_id');
            if(!Object.values(this.cookie).includes(null)) {
                console.log('Cookie assignment problem')
                this.$router.push('/')
            }
            console.log('load chat history', this.cookie,  this.$cookies.get('user_id'))
        }
    },
    mounted() {
    //   this.noDataText = this.$vuetify.t(this.noDataText);
      this.cookie.user_id = this.$cookies.get('user_id')
      this.cookie.agent_id = this.$cookies.get('agent_id')
      console.log('mounted app', this.cookie, this.userChatHistory)
    }
}
</script>
