import firebase from 'firebase/app'
import 'firebase/messaging'
import Vue from 'vue'
const firebaseConfig = {
  apiKey: 'AIzaSyCJ4w5orI9tO22GJvCAHzqWZmwEmnsJXzM',
  authDomain: 'apexionerp-7d49a.firebaseapp.com',
  projectId: 'apexionerp-7d49a',
  storageBucket: 'apexionerp-7d49a.appspot.com',
  messagingSenderId: '2998353675',
  appId: '1:2998353675:web:4ba6b2f48d61e3ff8b2c0e',
  measurementId: 'G-ZBM4TXYBXT'
}
// Initialize Firebase
firebase.initializeApp(firebaseConfig)
const messaging = firebase.messaging()
Vue.prototype.$fcm = messaging
export { messaging }
