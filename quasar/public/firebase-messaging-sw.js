/* eslint-disable no-undef */
importScripts('https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.6.1/firebase-messaging.js')

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const app = firebase.initializeApp({
  apiKey: 'AIzaSyCJ4w5orI9tO22GJvCAHzqWZmwEmnsJXzM',
  authDomain: 'apexionerp-7d49a.firebaseapp.com',
  projectId: 'apexionerp-7d49a',
  storageBucket: 'apexionerp-7d49a.appspot.com',
  messagingSenderId: '2998353675',
  appId: '1:2998353675:web:4ba6b2f48d61e3ff8b2c0e',
  measurementId: 'G-ZBM4TXYBXT'
})

const messaging = app.messaging()
messaging.onBackgroundMessage((payload) => {
  // console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = payload.notification.title
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/apexion_logo.svg'
  }

  self.registration.showNotification(notificationTitle,
    notificationOptions)
})
