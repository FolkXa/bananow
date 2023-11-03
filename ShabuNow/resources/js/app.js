import './bootstrap';
import 'flowbite';

import { createApp } from 'vue';
// อย่าลืม import component ที่สร้างไว้ด้วย
// import Example from "./components/Example.vue";
import App from "./components/App.vue";

// ส่วนที่เอาไว้เพิ่ม component ของ vuejs
const app = createApp();
// app.component("example", Example);
app.component("app", App);

app.mount("#app");