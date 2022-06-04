            <div class="modal fade" id="tokenQRCodeModal" tabindex="-1" aria-labelledby="tokenQRCodeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-body d-flex justify-content-center align-items-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tokenQRCodeModalLabel">{{ __('Scan the QR-Code token') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div id="tokenQRCodeImg" class="modal-body"></div>
                    </div>
                </div>
            </div>
            @push('scripts-qr-modal')
            <script>
                function generateTokenQrCode() {
                    let tokenName = prompt("{{ __('Please enter token name') }}", "{{ config('app.name', 'Laravel') }}");
                    if (tokenName != null) {
                        axios.post("{{ route('generate-token') }}", { name: tokenName })
                        .then(response => {
                            const qrModal = document.getElementById('tokenQRCodeModal');
                            const qrContainer = document.getElementById('tokenQRCodeImg');
                            const qrcode = new QRCode(qrContainer, {
                                text: response.data.token,
                                width: 250,
                                height: 250,
                            });
                            new bootstrap.Modal(qrModal).toggle();
                            qrModal.addEventListener('hidden.bs.modal', (evt) => {
                                qrcode.clear();
                                qrContainer.textContent = '';
                            });
                            qrModal.addEventListener('shown.bs.modal', () => {
                                qrContainer.children[1].classList.add('img-fluid');
                                qrContainer.children[1].style.width = '100%';
                                qrContainer.children[1].style.height = '100%';
                            })
                            console.log(response.data.token);
                        })
                        .catch((err) => {
                            alert(err.response.data.message);
                        });
                    }
                }
            </script>
            @endpush
            @stack('scripts-qr-modal')
