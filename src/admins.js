import Vue from "vue";
import "./plugins/axios";
import Admins from "./views/Admins.vue";
import "./plugins/iview.js";
import router from "./router";
import store from "./store";

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(Admins)
}).$mount("#app");
