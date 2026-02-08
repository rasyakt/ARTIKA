<!-- Reusable Scanner Modal Component -->
<div class="modal fade" id="artikaScannerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; background: #000;">
            <div class="modal-header border-0 position-absolute w-100 p-3"
                style="z-index: 1060; background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, transparent 100%);">
                <h5 class="modal-title text-white fw-bold"><i
                        class="fa-solid fa-expand me-2"></i>{{ __('pos.scan_barcode') ?? 'Scan Barcode' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0 position-relative"
                style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <div id="artika-reader" style="width: 100%; height: 100%; min-height: 400px; background: #000;"></div>


                <!-- Loading State -->
                <div id="artika-scanner-loading" class="position-absolute text-white text-center">
                    <div class="spinner-border text-white mb-3" role="status"></div>
                    <div>{{ __('pos.starting_camera') ?? 'Starting camera...' }}</div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-dark justify-content-center p-3">
                <button type="button" id="artika-switch-camera" class="btn btn-outline-light rounded-pill px-4">
                    <i class="fa-solid fa-camera-rotate me-2"></i> {{ __('pos.switch_camera') ?? 'Switch Camera' }}
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Load html5-qrcode from CDN -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript" defer></script>

<script>
    let artikaScanner = null;
    let artikaScannerActive = false;
    let artikaScannerCallback = null;
    let artikaCurrentCamera = 'environment';

    function initArtikaScanner() {
        if (!window.Html5Qrcode) {
            console.error('Html5Qrcode not loaded yet. Retrying...');
            setTimeout(initArtikaScanner, 500);
            return;
        }

        const modal = document.getElementById('artikaScannerModal');
        modal.addEventListener('hidden.bs.modal', stopArtikaScanner);

        document.getElementById('artika-switch-camera').addEventListener('click', function () {
            artikaCurrentCamera = artikaCurrentCamera === 'environment' ? 'user' : 'environment';
            stopArtikaScanner().then(() => startArtikaScannerSession());
        });
    }

    function startArtikaScanner(callback) {
        artikaScannerCallback = callback;
        const modal = new bootstrap.Modal(document.getElementById('artikaScannerModal'));
        modal.show();

        // Brief delay to ensure modal is rendered
        setTimeout(startArtikaScannerSession, 500);
    }

    function startArtikaScannerSession() {
        if (artikaScannerActive) return;

        document.getElementById('artika-scanner-loading').style.display = 'block';

        if (!artikaScanner) {
            artikaScanner = new Html5Qrcode("artika-reader");
        }

        const config = {
            fps: 15, // Slightly higher FPS for faster scanning
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true,
            videoConstraints: {
                focusMode: 'continuous',
                facingMode: artikaCurrentCamera,
                advanced: [{ focusMode: "continuous" }]
            },
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        };

        artikaScanner.start(
            config.videoConstraints,
            config,
            (decodedText) => {

                // Play success beep if possible? (Optional)

                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('artikaScannerModal')).hide();
                    if (artikaScannerCallback) artikaScannerCallback(decodedText);
                }, 300);
            },
            (errorMessage) => {
                // Ignore error messages to keep console clean
            }
        ).then(() => {
            artikaScannerActive = true;
            document.getElementById('artika-scanner-loading').style.display = 'none';
        }).catch(err => {
            console.error('Scanner start error:', err);
            document.getElementById('artika-scanner-loading').innerHTML =
                `<div class="text-danger p-4">${err || 'Camera access denied or device not supported'}</div>`;
        });
    }

    async function stopArtikaScanner() {
        if (artikaScanner && artikaScannerActive) {
            try {
                await artikaScanner.stop();
                artikaScannerActive = false;
            } catch (err) {
                console.warn('Scanner stop error:', err);
            }
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', initArtikaScanner);
</script>