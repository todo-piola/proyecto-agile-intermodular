import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@js': path.resolve(__dirname, '../js')
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