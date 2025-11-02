import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'prompt',
            includeAssets: ['favicon.ico', 'icons/*.png'],

            manifest: {
                name: 'Macro Log - Meal Tracker',
                short_name: 'Macro Log',
                description: 'Track your meals and nutrition goals with AI-powered insights',
                theme_color: '#4F46E5',
                background_color: '#FFFFFF',
                display: 'standalone',
                scope: '/',
                start_url: '/dashboard',
                orientation: 'portrait-primary',
                categories: ['health', 'nutrition', 'lifestyle'],

                icons: [
                    {
                        src: '/icons/icon-72x72.png',
                        sizes: '72x72',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-96x96.png',
                        sizes: '96x96',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-128x128.png',
                        sizes: '128x128',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-144x144.png',
                        sizes: '144x144',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-152x152.png',
                        sizes: '152x152',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: '/icons/icon-384x384.png',
                        sizes: '384x384',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any'
                    },
                    {
                        src: '/icons/maskable-icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ],

                shortcuts: [
                    {
                        name: 'Log Meal',
                        short_name: 'Log Meal',
                        description: 'Quickly log a new meal',
                        url: '/dashboard?action=log',
                        icons: [{ src: '/icons/icon-192x192.png', sizes: '192x192' }]
                    },
                    {
                        name: 'View History',
                        short_name: 'History',
                        description: 'View meal history',
                        url: '/history',
                        icons: [{ src: '/icons/icon-192x192.png', sizes: '192x192' }]
                    }
                ]
            },

            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff,woff2}'],

                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/.*\/api\/.*/i,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            expiration: {
                                maxEntries: 50,
                                maxAgeSeconds: 60 * 60
                            },
                            networkTimeoutSeconds: 10
                        }
                    },
                    {
                        urlPattern: /^\/(dashboard|history|goals|profile).*/i,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages-cache',
                            expiration: {
                                maxEntries: 20,
                                maxAgeSeconds: 60 * 30
                            },
                            networkTimeoutSeconds: 5
                        }
                    },
                    {
                        urlPattern: /\.(?:png|jpg|jpeg|svg|gif|webp|woff|woff2)$/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'assets-cache',
                            expiration: {
                                maxEntries: 100,
                                maxAgeSeconds: 60 * 60 * 24 * 30
                            }
                        }
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.bunny\.net\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-cache',
                            expiration: {
                                maxEntries: 30,
                                maxAgeSeconds: 60 * 60 * 24 * 365
                            },
                            cacheableResponse: {
                                statuses: [0, 200]
                            }
                        }
                    }
                ],

                skipWaiting: true,
                clientsClaim: true
            },

            devOptions: {
                enabled: false,
                type: 'module'
            }
        })
    ],
});
