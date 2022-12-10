
import HomePage from './layouts/HomePage.vue'
import AllPage from './layouts/AllPage.vue'
import CustomerPage from './layouts/CustomerPage.vue'
import Home from './layouts/Home.vue'
import Login from './layouts/Login.vue'

export const routes = [

    {
        path: '/',
        name: 'home',
        component: Home,
        // children: [
        //     {
        //         path: '',
        //         name: 'home-page',
        //         component: AllPage,
        //     },
        //     {
        //         path: 'customer/:customer_name',
        //         name: 'customer-page',
        //         component: CustomerPage,
        //         props:true
               
        //     }
        // ]
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },

];