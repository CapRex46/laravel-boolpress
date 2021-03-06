import { home } from "fontawesome";
import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./pages/Home.vue";
import Contacts from "./pages/Contacts.vue";




Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    routes: [
      {
        path: "/",
        component: Home,
        name: "home.index",
        meta: { title: "Homepage", linkText: "Home" },
      },
      {
        path: "/contatti",
        component: Contacts,
        name: "contacts.index",
        meta: { title: "Contatti", linkText: "Scrivici!" },
      },
      {
        path: "/posts/:post",
        component: PostShow,
        name: "posts.show",
        meta: { title: "Dettagli post" },
      },
      {
        path: "*",
        path: "/not-found",
        alias: "*",
        component: Error,
        name: "error"
      }
    ],
  });

  router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
  
    next();
  });
  

export default router