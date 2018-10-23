import Vue from 'vue';
import Vuex from 'vuex';
import highcharts from './modules/highcharts';

const store = new Vuex.Store({
    modules: {
        highcharts
    }
});

export default store;