// 引入路由组件
import Home from '@/components/Home.vue';
import About from "@/components/About.vue";

// 定义路由信息对象数组
const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/about', name: 'about', component: About }
];

// 对外导出路由对象数组
export default routes;
