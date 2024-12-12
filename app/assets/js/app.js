import { createApp } from 'vue';
import App from '../components/App.vue';


window.onload = () => {
  const appElement = document.getElementById('app');
  createApp(App).mount('#app');
};
