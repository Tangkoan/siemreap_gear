import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});



// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";

// export default defineConfig({
//     server: {
//         host: "192.168.1.6", // ✅ អនុញ្ញាតអោយអាច run តាម IP
//         port: 5173, // ✅ អាចកំណត់ត្រឹមតែ 5173 ឬផ្សេងទៀត
//     },
//     plugins: [
//         laravel({
//             input: ["resources/css/app.css", "resources/js/app.js"],
//             refresh: true,
//         }),
//     ],
// });




// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ["resources/css/app.css", "resources/js/app.js"],
//             refresh: true,
//         }),
//     ],
//     // ✅ បន្ថែម
//     server: {
//         host: "0.0.0.0",
//         hmr: {
//             host: "localhost",
//         },
//     },
// });