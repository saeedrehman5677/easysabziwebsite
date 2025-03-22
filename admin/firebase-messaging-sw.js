importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyDm4Bl4eRrqAyuIKEvR4SF6AvK7CPxcnLg",
    authDomain: "sabzimandi-5d816.firebaseapp.com",
    projectId: "sabzimandi-5d816",
    storageBucket: "sabzimandi-5d816.firebasestorage.app",
    messagingSenderId: "798169491116",
    appId: "1:798169491116:web:d0f100fbd17ee9d4dee109",
    measurementId: "G-0N6YDD714M"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    return self.registration.showNotification(payload.data.title, {
        body: payload.data.body ? payload.data.body : '',
        icon: payload.data.icon ? payload.data.icon : ''
    });
});