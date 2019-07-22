import Vue from 'vue';
import VuePusher from 'vue-pusher';

Vue.use(VuePusher, {
    api_key: '2f349120268babc31390',
    options: { cluster: 'eu' }
});