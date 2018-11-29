import Vue from 'vue'
import Vuex from 'vuex'
import mutations from "./mutations";
import actions from "./actions";

Vue.use(Vuex)

export default new Vuex.Store({
  // 状态数据
  state: {

  },
  // 计算属性
  getters: {

  },
  // 操作方法
  mutations,
  // 异步操作方法
  actions
});
