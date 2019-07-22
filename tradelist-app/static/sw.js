importScripts('/_nuxt/workbox.4c4f5ca6.js')

workbox.precaching.precacheAndRoute([
  {
    "url": "/_nuxt/1d293e30cbcdf795d788.js",
    "revision": "eb49dcdcf5ab00d1aa603a75462e16e0"
  },
  {
    "url": "/_nuxt/1e26593a169f2bb65a6e.js",
    "revision": "5dcd24d91a3b62ae3065a0e0984e5c65"
  },
  {
    "url": "/_nuxt/2053c18cca9c82d6e309.js",
    "revision": "07d402a599c3b4de377453dfd9366ec1"
  },
  {
    "url": "/_nuxt/218342c32c60a2620bc0.js",
    "revision": "43b7999291d10a610eecde8960d9ad6e"
  },
  {
    "url": "/_nuxt/2459148532455fcfa51f.js",
    "revision": "3182088aa7a2ff434bae9d8ecadcbdc8"
  },
  {
    "url": "/_nuxt/265c89841911ee8c71f6.js",
    "revision": "cf36c23ea144d10d62126bf27368bdb3"
  },
  {
    "url": "/_nuxt/2c298882fd0f99c94c75.js",
    "revision": "5af1f0986d7ae213a4eafadcbc424926"
  },
  {
    "url": "/_nuxt/3eeb5633fbaa1408a048.js",
    "revision": "5cf73bfc17c3aaeb7d3791a7319bb314"
  },
  {
    "url": "/_nuxt/4376576bcef321710d56.js",
    "revision": "ffb6a60e2ca5ae8bfad3294fcbdbe37c"
  },
  {
    "url": "/_nuxt/45999fdca78751f229b3.js",
    "revision": "a78866cdf4162733f48ba858ca6361d5"
  },
  {
    "url": "/_nuxt/500732fa50b1c9377888.js",
    "revision": "9f6286dfc2df93dc13253d8c08ceea9d"
  },
  {
    "url": "/_nuxt/56d674b15fb715264a57.js",
    "revision": "c0f8b2dd6894fd9e9c93a4d76f4b32a8"
  },
  {
    "url": "/_nuxt/60a3fe30b020e62e3219.js",
    "revision": "2de79837eebcb140f8c60480d07b85bc"
  },
  {
    "url": "/_nuxt/6559adf33c24760d655a.js",
    "revision": "7079b179a07bf57a2cc97203a5e0217e"
  },
  {
    "url": "/_nuxt/656f87bbc8149b4f48ac.js",
    "revision": "856fc0edf54d2419f3f2e3d9cf6974ac"
  },
  {
    "url": "/_nuxt/6e46f15aab1ee2e9ef2b.js",
    "revision": "742f649ac036bc598c88c67b882da547"
  },
  {
    "url": "/_nuxt/7b9cf93873a422c666c6.js",
    "revision": "d5c928ba8d73e1ee2c4cd1f28716db6e"
  },
  {
    "url": "/_nuxt/9997f599f063aa48d40d.js",
    "revision": "aaa4d08c86d0316f1c1d98c2db7b2e4c"
  },
  {
    "url": "/_nuxt/9e74bc7fe62d4695992b.js",
    "revision": "e0ecbd4041b4dde0a7d95c915cd469b5"
  },
  {
    "url": "/_nuxt/a9708047cc712ebc605f.js",
    "revision": "b4269d5bd87aa7c6dbb7e6eff3f7d19e"
  },
  {
    "url": "/_nuxt/b9d95eb0701dac65408c.js",
    "revision": "da337c2190e9d000f9d5df69cff5a2c0"
  },
  {
    "url": "/_nuxt/bca76a47d629fee84e86.js",
    "revision": "0c24be9a5042d170dc24bea3f97e6a9a"
  },
  {
    "url": "/_nuxt/be98e60a2e47d8b9d929.js",
    "revision": "fa24779eff22346f06c2356ae8607337"
  },
  {
    "url": "/_nuxt/cb4dc5da77cd9c960385.js",
    "revision": "2c03c2eaa27022d2c7c8b3ef020f44bf"
  },
  {
    "url": "/_nuxt/cdfdbc469f8bad8a9a1c.js",
    "revision": "82b978706523e46d2c324290cb59fdaf"
  },
  {
    "url": "/_nuxt/fae9ff9132c9415ce91f.js",
    "revision": "32cf06630425a003ca8fe09892f292f8"
  }
], {
  "cacheId": "tradelist-app",
  "directoryIndex": "/",
  "cleanUrls": false
})

workbox.clientsClaim()
workbox.skipWaiting()

workbox.routing.registerRoute(new RegExp('/_nuxt/.*'), workbox.strategies.cacheFirst({}), 'GET')

workbox.routing.registerRoute(new RegExp('/.*'), workbox.strategies.networkFirst({}), 'GET')
