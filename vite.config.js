import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  base: '/',                      // 👈 important for subfolder
  server: {
    host: '127.0.0.1',                 // Bind to IPv4 to avoid [::1] CSP issues
    port: 5173,
  },
  plugins: [
    laravel({
      input: ['resources/js/app.js', 'resources/css/app.css'],
      refresh: true,
    }),
    vue(),                             // 👈 enable Vue SFC support
    tailwindcss(),                     // 👈 add Tailwind CSS plugin
  ],
})
