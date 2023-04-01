import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

const fs = require('fs');
const path = require('path');
const util = require('util');
function createFilePlugin() {
  return {
    name: 'create-file-plugin',
    configureServer(server) {
      if (server.config.mode === 'development') {
        server.httpServer.on('listening', () => {
          const address = server.httpServer.address();
          fs.writeFileSync('./public/hot', `http://${address.address}:${address.port}`);
        });
      }
    },
  };
}

function removeEverythingPlugin() {
  return {
    'name': 'remove-everything-before-build',
    buildStart() {
      if (process.env.NODE_ENV !== 'development') {
        fs.readdir('./public', (err, files) => {

          if (err) return;

          files.forEach((file) => {
            const filePath = path.join('./public', file);
            if (file === 'index.php') return;

            fs.statSync(filePath).isDirectory() ?
              fs.rmdirSync(filePath, {recursive: true}) :
              fs.unlinkSync(filePath);
          });
        })
      }
    }
  }
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    removeEverythingPlugin(),
    createFilePlugin(),
  ],
  publicDir: 'static',
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url))
    }
  },
  build: {
    emptyOutDir: false,
    outDir: 'public',
    rollupOptions: {
      input: 'index.html'
    },
  },
})
