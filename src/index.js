import Vue from "vue";
import "./plugins/axios";
import Index from "./views/Index.vue";
import "./plugins/iview.js";
import router from "./plugins/router";
import store from "./store/index.js";

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(Index)
}).$mount("#app");

