import VueRouter from 'vue-router'

Vue.use(VueRouter)

//Router Path
const routes = [
	{
        path: '/highcharts',
        name: 'highcharts',
        component: require('./components/HighchartsComponent')
    }
];

//Router Instance
const router = new VueRouter({
    mode: 'history',
    routes: routes,
});

//Router Guard
router.beforeEach((to, from, next) => {
	next()
})

export default router