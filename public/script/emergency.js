

function playAlertSound() {
    const sound = document.getElementById('alertSound');
    if (!sound) return;

    const playPromise = sound.play();
    if (playPromise !== undefined) {
        playPromise.catch(error => console.warn("Autoplay prevented:", error));
    }
}

function stopAlertSound() {
    const sound = document.getElementById('alertSound');
    if (!sound) return;

    sound.pause();
    sound.currentTime = 0;
}

// --- Emergency Modal Logic ---
document.addEventListener('DOMContentLoaded', function () {
    let isModalShown = false;
    let modalInstance = null;

    // --- Dynamic base URL ---
    const userRole = document.body.getAttribute("data-role"); // ilagay sa <body data-role="admin"> o "staff"
    const baseUrl = userRole === "admin" ? "/admin" : "/staff";

    function cleanupBackdrop() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }

    // Fetch emergencies from backend
    function fetchEmergencies() {
        fetch(`${baseUrl}/emergencies`)
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('emergency-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                const emergencies = tempDiv.querySelectorAll('.emergency-item');
                const modalEl = tempDiv.querySelector('#emergencyModal');

                if (emergencies.length > 0 && modalEl) {
                    if (!isModalShown) {
                        container.innerHTML = html;
                        modalInstance = new bootstrap.Modal(
                            container.querySelector('#emergencyModal')
                        );
                        bindAcknowledgeButtons();
                        modalInstance.show();
                        playAlertSound();
                        isModalShown = true;
                    } else {
                        const modalBody = container.querySelector('#emergencyModal .modal-body');
                        const newBody = tempDiv.querySelector('#emergencyModal .modal-body');
                        if (modalBody && newBody) {
                            modalBody.innerHTML = newBody.innerHTML;
                            bindAcknowledgeButtons();
                        }
                    }
                } else {
                    if (isModalShown && modalInstance) {
                        stopAlertSound();
                        modalInstance.hide();
                        cleanupBackdrop();
                        isModalShown = false;
                    }
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    function bindAcknowledgeButtons() {
        document.querySelectorAll('.acknowledge-btn').forEach(button => {
            button.addEventListener('click', function () {
                let id = this.getAttribute('data-id');

                fetch(`${baseUrl}/emergency/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const item = document.getElementById(`emergency-${id}`);
                        if (item) item.remove();

                        if (
                            document.querySelectorAll('.emergency-item').length === 0 &&
                            modalInstance
                        ) {
                            stopAlertSound();
                            modalInstance.hide();
                            cleanupBackdrop();
                            isModalShown = false;
                        }
                    }
                })
                .catch(err => console.error("Delete error:", err));
            });
        });
    }

    // Initial fetch
    fetchEmergencies();

    // Poll every 5 seconds
    setInterval(fetchEmergencies, 5000);
});


