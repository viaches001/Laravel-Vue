import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production'

// Connectivity Checker
window.addEventListener('offline', (e) => {
    store.commit('setOnlineStatus', false)
});
window.addEventListener('online', (e) => {
    store.commit('setOnlineStatus', true)
});

const store = new Vuex.Store({
    strict: debug,
    
    mutations: {
        setOnlineStatus (state, payload) {
            state.online = payload;
        }
    },
    
    state: {
        today: (new Date()).getFullYear().toString() + '-' + ((new Date()).getMonth() + 1).toString().padStart(2, '0') + '-' + ((new Date()).getDate()).toString().padStart(2, '0'),
        online: navigator.onLine
    }
});

export default store;