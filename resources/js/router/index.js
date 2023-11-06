import {createRouter , createWebHistory} from "vue-router";
import invoiceIndex from '../components/invoices/index.vue';
import notFound from '../components/notFound.vue';
import axios from 'axios';
import NewInvoice from '../components/invoices/NewInvoice.vue';
import show from '../components/invoices/show.vue';
import edit from '../components/invoices/edit.vue';

const routes =[
    {
        path: '/',
        component : invoiceIndex,

    },
    {
        path:'/:pathMatch(.*)*',
        component : notFound
    },

    {
        path : '/invoice/new',
        component: NewInvoice
    },
    {
        path : '/invoice/show/:id',
        component: show,
        props :true
    },
    {
        path : '/invoice/edit/:id',
        component: edit,
        props :true
    }

]


const router = createRouter({
    history:createWebHistory(),
    routes
})

export default router