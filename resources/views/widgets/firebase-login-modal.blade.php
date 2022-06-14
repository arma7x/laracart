        <div class="modal fade" id="firebaseLoginModal" tabindex="-1" aria-labelledby="firebaseLoginModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-dark" id="firebaseLoginModalLabel">{{ __('Firebase Login') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div id="firebaseui-auth-container" class="mt-5"></div>
              </div>
            </div>
          </div>
        </div>
        @push('scripts-firebase-login-modal')
        <script type="text/javascript">
            const uiConfig = {
              signInSuccessUrl: '/',
              signInOptions: [
                // Leave the lines as is for the providers you want to offer your users.
                firebase.auth.GoogleAuthProvider.PROVIDER_ID,
                // firebase.auth.FacebookAuthProvider.PROVIDER_ID,
                // firebase.auth.TwitterAuthProvider.PROVIDER_ID,
                // firebase.auth.GithubAuthProvider.PROVIDER_ID,
                firebase.auth.EmailAuthProvider.PROVIDER_ID,
                // firebase.auth.PhoneAuthProvider.PROVIDER_ID,
                // firebaseui.auth.AnonymousAuthProvider.PROVIDER_ID
              ],
              tosUrl: function() {
                window.location.assign('/tos')
              },
              privacyPolicyUrl: function() {
                window.location.assign('/pnp')
              }
            }
            const ui = new firebaseui.auth.AuthUI(firebase.auth());
            ui.start('#firebaseui-auth-container', uiConfig);
            firebase.auth().onAuthStateChanged((user) => {
                if (user) {
                    console.log(user);
                    const firebaseLoginBtn = document.getElementById('firebaseLoginBtn');
                    firebaseLoginBtn.classList.add('d-none');
                    const firebaseLogoutBtn = document.getElementById('firebaseLogoutBtn');
                    firebaseLogoutBtn.classList.remove('d-none');
                    const loginNavItem = document.getElementById('loginNavItem');
                    loginNavItem.classList.add('d-none');
                    const registerNavItem = document.getElementById('registerNavItem');
                    registerNavItem.classList.add('d-none');
                    firebase.auth().currentUser.getIdToken()
                    .then((token) => {
                        console.log(token);
                    })
                    .catch((err) => {
                        console.error(err);
                    });
                } else {
                    console.log('User is null');
                    const firebaseLoginBtn = document.getElementById('firebaseLoginBtn');
                    firebaseLoginBtn.classList.remove('d-none');
                    const firebaseLogoutBtn = document.getElementById('firebaseLogoutBtn');
                    firebaseLogoutBtn.classList.add('d-none');
                    const loginNavItem = document.getElementById('loginNavItem');
                    loginNavItem.classList.remove('d-none');
                    const registerNavItem = document.getElementById('registerNavItem');
                    registerNavItem.classList.remove('d-none');
                    let timer = setInterval(() => {
                        const open = document.getElementsByClassName('firebaseui-callback-indicator-container').length > 0 || document.getElementsByClassName('firebaseui-info-bar').length > 0;
                        if (open) {
                            const firebaseLoginModal = new bootstrap.Modal(document.getElementById('firebaseLoginModal'));
                            firebaseLoginModal.show();
                            clearInterval(timer);
                            timer = null;
                        }
                    }, 100);
                    setTimeout(() => {
                        if (timer) {
                            clearInterval(timer);
                            timer = null;
                        }
                    }, 5000);
                }
            }, (error) => {
                console.log(error)
            });

            function logoutFirebase() {
                firebase.auth().signOut()
                .finally(() => {
                    window.location.reload();
                });
            }
        </script>
        @endpush
        @stack('scripts-firebase-login-modal')
