import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@js': path.resolve(__dirname, './src/js'),        // React
      '@legacy': path.resolve(__dirname, '../js'),       // PHP / antiguo
      '@': path.resolve(__dirname, './src'),
    }
  },
  server: {
    fs: {
      allow: ['..'] 
    },
    cors: true,
    strictPort: true,
    hmr: {
      host: 'localhost',
    },
  }
})