import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import {resolve} from 'path'

const twigRefreshPlugin = {
  name: 'twig-refresh',
  configureServer ({watcher,ws}) {
    watcher.add(resolve('templates/**/*.twig'))
    watcher.on('change',function (path) {
      if(path.endsWith('.twig')) {
        ws.send({
          type: 'full-reload'
        })
      }
    })
  }
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react(),twigRefreshPlugin],
  // Specifie le dossier racine
  root:'./assets',
  // Pour specifier la base de l'url
  base: '/assets/',
  server: {
    watch: {
      disableGlobbing: false
    }
  },
  build: {
    manifest:true,
    assetsDir:'',
    outDir: '../public/assets/',
    rollupOptions: {
      output: {
        manualChunks: undefined
      },
      // Pour specifier a vite de trouver le point d'entrer
      input: {
        'main.js':'./assets/main.js',
        'tailwind.css':'./assets/css/tailwind.css'
      }
    }
  }
})