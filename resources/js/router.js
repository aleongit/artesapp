//import Vue from 'vue';
//import VueRouter from 'vue-router';
//Vue.use(VueRouter); -> a vue 2

import {
    createRouter,
    createWebHistory,
    createWebHashHistory,
} from "vue-router";

//servei auth
import * as auth from "./services/auth_service";

//const Welcome = () => import('./views/Welcome.vue');
import Home from "./views/grups/Home.vue";

const rutes = [
    // home agrupa vistes fill, i que seran /home/namefill
    {
        path: "/home",
        name: "home",
        component: Home,
        children: [

            {
                path: "", // --> /
                name: "dashboard",
                component: () => import('./views/Dashboard.vue'),
            },
            
            //agrupació admin
            {
                path: "admin",
                name: "admin",
                component: () => import('./views/grups/Admin.vue'),
                children: [

                    {
                        path: "categories", // --> /home/categories
                        name: "categories",
                        component: () => import("./views/Categories.vue"),
                    },
        
                    {
                        path: "productes", // --> /home/productes
                        name: "productes",
                        component: () => import("./views/Productes.vue"),
                    },

                ],
                beforeEnter(to, from, next) {
                    console.log('getUserRole: '+ auth.getUserRole());
                    if (auth.getUserRole() == 'admin') {
                        next();
                    } else {
                        next('/401');
                    }
                }
            },

            //agrupació user
            {
                path: "user",
                name: "user",
                component: () => import('./views/grups/User.vue'),
                children: [
                    {
                        path: "profile",
                        name: "profile",
                        component: () => import('./views/user/Profile.vue'),
                    },
                    {
                        path: "change-password",
                        name: "change-password",
                        component: () => import('./views/user/ChangePass.vue'),
                    },
                ]
            },
            
            {
                path: "clients", // --> /home/clients
                name: "clients",
                component: () => import("./views/Clients.vue"),
            },

            {
                path: "comandes", // --> /home/comandes
                name: "comandes",
                component: () => import("./views/Comandes.vue"),
            },


        ],
        //Navigation Guards = guardiants de navegació
        //abans d'entrar a home comprovar si usuari logat i redirigir
        beforeEnter(to, from, next) {
            if (!auth.isLoggedIn()) {
                next('/login');
            } else {
                next();
            }
        }
    },

    {
        path: "/",
        redirect: "/home",
    },

    {
        path: "/register",
        name: "register",
        component: () => import("./views/auth/Register.vue"),
    },
    {
        path: "/login",
        name: "login",
        component: () => import("./views/auth/Login.vue"),
        //si user logat, si va a login -> redirigir a home
        beforeEnter(to, from, next) {
            if (!auth.isLoggedIn()) {
                next();
            } else {
                next('/home');
            }
        }
    },
    {
        path: "/reset-password",
        name: "reset-password",
        //component: () => import("./views/auth/ResetPassword.vue"),
    },

    {
        path: '/401',
        name: "401",
        component: () => import("./views/errors/401.vue"),
    },

    {   
        //* compte canvi!!! Removed * (star or catch all) routes
        //https://router.vuejs.org/guide/migration/#removed-star-or-catch-all-routes
        //{ path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound },
        
        path: '/:pathMatch(.*)*',
        name: "404",
        component: () => import("./views/errors/404.vue"),
        
    }
];

const router = createRouter({
    //history: createWebHashHistory(), //->rutes amb # (http://127.0.0.1:8000/#/home)
    history: createWebHistory(),
    routes: rutes,
    linkActiveClass: "active",
});

export default router;
