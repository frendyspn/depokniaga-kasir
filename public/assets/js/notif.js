// importScripts("https://www.gstatic.com/firebasejs/7.23.0/firebase.js");

const firebaseConfig = {
    apiKey: "AIzaSyBRM-xAqytalTRmEYj7jTmLgf7vxEQNmcU",
    authDomain: "satukurirwebpush.firebaseapp.com",
    projectId: "satukurirwebpush",
    storageBucket: "satukurirwebpush.appspot.com",
    messagingSenderId: "642680161917",
    appId: "1:642680161917:web:9b0a9e5f3e074ecbce9314",
    measurementId: "G-ZZHXP0DPB4"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

function initFirebaseMessagingRegistration() {
        messaging
        .requestPermission()
        .then(function () {
            return messaging.getToken()
        })
        .then(function(token) {
            var latitude = document.getElementById("latitude").value
            console.log(latitude);
            console.log('ini dia')

            if (latitude === '') {
                initFirebaseMessagingRegistration()
            }

            window.location.href = '/save-token/'+token+'/'+latitude;

            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': {{csrf_token()}}
            //     }
            // });

            // $.ajax({
            //     url: '{{ url("save-token") }}',
            //     type: 'POST',
            //     data: {
            //         device_token: token,
            //     },
            //     header:{
            //         'X-CSRF-TOKEN': {{csrf_token()}}
            //     },
            //     dataType: 'JSON',
            //     success: function (response) {
            //         alert('Token saved successfully.');
            //     },
            //     error: function (err) {
            //         console.log('User Chat Token Error'+ err);
            //     },
            // });
            // console.log('itu dia')
        }).catch(function (err) {
            console.log('User Chat Token Error'+ err);
        });
 }

 messaging.onMessage(function({data:{body,title}}){
    // new Notification(title, {body});
    if (!("Notification" in window)) {
        alert("This browser does not support system notifications.");
    } else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification('apaaaa',{body});
        notification.onclick = function(event) {
            event.preventDefault();
            window.open(payload.notification.click_action , '_blank');
            notification.close();
        }
    }
});