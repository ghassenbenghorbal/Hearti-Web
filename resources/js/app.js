require("./bootstrap");
require("./plugins/vue-toastification");
import Vue from "vue";
import {
  App as InertiaApp,
  plugin as InertiaPlugin
} from "@inertiajs/inertia-vue";
import { Link } from "@inertiajs/inertia-vue";
import vuetify from "./plugins/vuetify";
import { InertiaProgress } from "@inertiajs/progress";

import VueApexCharts from "vue-apexcharts";
Vue.use(VueApexCharts);

Vue.component("apexchart", VueApexCharts);

import socketio from "socket.io-client";
import VueSocketIO from "vue-socket.io";

export const SocketInstance = socketio("http://localhost:3000",{
  transports: ['websocket'],
  autoConnect: false,
  auth: {
    token: 'abc'
  }
});

Vue.use(
  new VueSocketIO({
    debug: true,
    connection: SocketInstance
  })
);

Vue.use(InertiaPlugin);
Vue.component("Link", Link);
Vue.mixin({ methods: { route: window.route } });
const app = document.getElementById("app");

new Vue({
  vuetify,
  render: h =>
    h(InertiaApp, {
      props: {
        title: title => `${title} - My App`,
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: name => require(`./pages/${name}`).default
      }
    })
}).$mount(app);

InertiaProgress.init({ color: "#ef5350" });
