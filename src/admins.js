import Vue from "vue";
import "./plugins/axios";
import Admins from "./views/Admins.vue";
import "./plugins/iview.js";
import router from "./plugins/router";
import store from "./store/index.js";
import ResourcesComponent from "./components/ResourcesComponent.vue";

Vue.config.productionTip = false;
Vue.component("ResourcesComponent", ResourcesComponent);

new Vue({
  router,
  store,
  render: h => h(Admins)
}).$mount("#app");
