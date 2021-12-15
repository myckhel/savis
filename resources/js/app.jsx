import "../css/app.css";
import "./bootstrap";

import { render } from "react-dom";
import { createInertiaApp } from "@inertiajs/inertia-react";
import { InertiaProgress } from "@inertiajs/progress";

InertiaProgress.init();

createInertiaApp({
    resolve: async (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx");
        const page = Object.keys(pages).find((page) =>
            page.endsWith(`${name}.jsx`)
        );

        return (await pages[page]()).default;
    },
    setup({ el, App, props }) {
        render(<App {...props} />, el);
    },
});
