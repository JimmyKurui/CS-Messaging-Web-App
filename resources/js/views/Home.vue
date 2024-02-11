<template>
    <div>
        <section class="hero">
            <h2>Welcome to Customer Support !</h2>
            <p class="mb-2">Please choose below your service for this test application. No authentication is needed for this
            </p>
        </section>
        <section class="call-to-action">
            <ul class="nav nav-tabs centered-buttons" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user"
                        aria-selected="true">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="agent-tab" data-toggle="tab" href="#agent" role="tab" aria-controls="agent"
                        aria-selected="false">Agent</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                    <p>Prompt for user to select a numerical ID:</p>
                        <v-select :items="users" v-model="selectedUserId" label="id"
                        @update:modelValue="chooseMember('user')"
                        ></v-select>
                </div>
                <div class="tab-pane fade" id="agent" role="tabpanel" aria-labelledby="agent-tab">
                    <p>Prompt for agent to select a numerical ID:</p>
                        <v-select :items="agents" v-model="selectedAgentId" label="id"
                        @update:modelValue="chooseMember('agent')"
                        ></v-select>
                </div>
            </div>
        </section>
    </div>
</template>

<script>

export default {
    data() {
        return {
            agents: [1, 2, 4],
            users: [1, 2, 5, 7, 8],
            dashboardRoute: '',
            selectedAgentId: 1,
            selectedUserId: 1
        }
    },
    methods: {
        chooseMember(type) {
            const route = (type == 'agent') ? 'dashboard' : 'support'
            const data = (type == 'agent') ? this.selectedAgentId : this.selectedUserId

            window.axios.get(`api/${route}?id=${data}`
            ).then(userConversations => {
                this.setCookie(`${(type == 'agent') ? 'agent_id' : 'user_id'}`, data)
                this.$router.push({ name: route });
                this.$emit('userChatHistoryEmitted', userConversations.data);
            }).catch(error => {
                console.error('Error fetching agent data:', error);
            });
        },
        setCookie(name, data) {
            const otherCookie = (name == 'agent_id') ? 'user_id' : 'agent_id' 
            if (this.$cookies.isKey(otherCookie)) {
                console.log('cookie removed', otherCookie)
                this.$cookies.remove(otherCookie);
            } 
            console.log('cookie set', name)
            this.$cookies.set(name, data)
        }
    },
    mounted() {
        window.axios.get(`/api/home`
        ).then(res => {
            this.agents = res.data.agents,
            this.users = res.data.users
        }).catch(error => {
            console.log(error)
        })
        console.log('mounted home', $cookies.keys(), this.cookie)
    }
}
</script>