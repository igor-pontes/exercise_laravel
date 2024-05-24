import { createRouter, createWebHistory } from "vue-router";
import HomeView from '../components/HomeView.vue'
import DashboardView from '../components/DashboardView.vue'
import LoginView from '../components/LoginView.vue'
import RegisterView from '../components/RegisterView.vue'

function guardHome(to, from, next) {
    var isAuthenticated = false;
    const user = JSON.parse(localStorage.getItem("user"));
    if(user) {
        isAuthenticated = true;
        if(user.rank == 1) {
            next('/dashboard');
            return;
        }
    }
    if(isAuthenticated)
        next();
    else
        next('/login');
}

function guardDashboard(to, from, next) {
    var isAuthenticated = false;
    const user = JSON.parse(localStorage.getItem("user"));
    if(user && user.rank == 1)
        isAuthenticated = true;
    if(isAuthenticated)
        next();
    else
        next('/');
}

function guardLogged(to, from, next) {
    var isAuthenticated = false;

    if(localStorage.getItem('user'))
        isAuthenticated = true;

    if(!isAuthenticated)
        next();
    else
        next('/dashboard');
}

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
        path: "/",
        name: "home",
        beforeEnter: guardHome,
        component: HomeView,
        meta: { title: "Home" }
    },
    {
        path: "/dashboard",
        name: "dashboard",
        beforeEnter: guardDashboard,
        component: DashboardView,
        props: { default: true },
        meta: { title: "Dashboard", default: true }
    },
    {
        path: "/dashboard/newtask",
        name: "newtask",
        beforeEnter: guardDashboard,
        component: DashboardView,
        meta: { title: "Dashboard - New Task", newtask: true }
    },
    {
        path: "/login",
        name: "login",
        beforeEnter: guardLogged,
        component: LoginView,
        meta: { title: "Login" }
    },
    {
        path: "/register",
        name: "register",
        beforeEnter: guardLogged,
        component: RegisterView,
        meta: { title: "Register" }
    },
  ],
});

export default router;
