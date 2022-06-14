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
        <form id="firebase-logout-form" action="{{ route('firebase-logout') }}" method="POST" class="d-none">
            @csrf
        </form>
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
                    firebase.auth().currentUser.getIdToken()
                    .then((token) => {
                        return axios.post("{{ route('firebase-login') }}", {token: token});
                    })
                    .then(() => {
                        document.location = '/';
                    })
                    .catch((err) => {
                        console.error(err);
                    });
                } else {
                    console.log("{{ __('Unauthorized') }}");
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
                    document.getElementById('firebase-logout-form').submit();
                });
            }
        </script>
        @endpush
        @stack('scripts-firebase-login-modal')
