require("./bootstrap");
import { createApp, inject } from 'vue'
import { createVuetify } from 'vuetify'
import VueCookies from 'vue-cookies'
import Echo from 'laravel-echo'

import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import App from './App.vue'
import router from './router/router'

const vuetify = createVuetify({
  components,
  directives
})

const app = createApp(App)

app.use(vuetify)
app.use(router)
app.use(VueCookies)

const $cookies = inject('$cookies')

app.mount('#app')

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
