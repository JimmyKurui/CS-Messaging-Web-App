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
        :items="unresolvedNotifications" 
        v-model="selectedUnresolvedUser"
        item-value="key"
        item-text="value"
        label="Notifications">
        </v-select> -->
        <div v-if="cookie.agent_id" class="dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i>
                <span class="badge badge-danger">Notifications</span>
            </a>
            <form action="" @submit.prevent="">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">
                    <a v-for="user in unresolvedNotifications" @click.prevent="chooseUser(user.key)" href="#" class="dropdown-item">{{ user.key }} - <span class="badge badge-primary">{{ user.text  }}</span></a>
                </div>
            </form>
        </div>
        <div class="ml-auto bg-info">
            {{ cookie.user_id || cookie.agent_id }}
        </div>
    </nav>
</template>

<script>
export default {
    props: {
        cookie: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            unresolvedNotifications: [],
            selectedUnresolvedUser: 1
        }
    },
    methods: {
        chooseUser(user) {
            window.axios.get(`/api/conversations?user=${user}`)
            .then(res => {
                
                this.$emit('userConversationEmitted', res.data)
            })
        }
    },
    mounted() {
        console.log('navbar mounted', this.cookie)
        window.axios.get('/api/messages?noTicket')
            .then(res => {
                let unresolvedMessages = res.data.reduce((countMap, obj) => {
                    const userId = obj.user_id;
                    countMap[userId] = (countMap[userId] || 0) + 1;
                    return countMap;
                }, []);
                unresolvedMessages = Object.entries(unresolvedMessages).map(([key, value]) => ({ 'key': key, 'text': value }));
                this.unresolvedNotifications = unresolvedMessages
            })
    },
}
</script>
