import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import vuetify from './plugins/vuetify'; // pastikan vuetify sudah diatur di plugins

import Main from "./Layouts/Main.vue";

// Setup aplikasi dengan Inertia.js
createInertiaApp({
    title: (title) => `Scuderia Ferrari ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        
        // Layout default untuk setiap page
        page.default.layout = page.default.layout || Main;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)  // Ziggy untuk routing di Vue
            .use(vuetify)   // Integrasi Vuetify
            .component("Head", Head) // Komponen Head untuk SEO dan metadata
            .component("Link", Link) // Komponen Link untuk navigasi
            .mount(el); // Mount aplikasi ke elemen HTML
    },
    progress: {
        color: "#fff",    // Warna progress bar
        showSpinner: true, // Menampilkan spinner saat transisi
    },
});
