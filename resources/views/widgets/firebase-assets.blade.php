    <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script>
    @guest
    <!-- Firebase Auth -->
    <link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.css" />
    <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.js"></script>
    @endif
    <script type="text/javascript">
        const firebaseConfig = {
          apiKey: "AIzaSyDYH1WBQXUgrQL3BaOBOiiFKdxLwR7cg10",
          authDomain: "parry-b18e0.firebaseapp.com",
          projectId: "parry-b18e0",
          storageBucket: "parry-b18e0.appspot.com",
          messagingSenderId: "701446495044",
          appId: "1:701446495044:web:13791c0c19156b19879511",
          measurementId: "G-3K9FD7KMSR"
        };

        // Initialize Firebase
        const firebaseApp = firebase.initializeApp(firebaseConfig);
    </script>
