import './bootstrap';


if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
        console.log('Service Worker terdaftar:', registration.scope);
    }).catch(function (error) {
        console.error('Service Worker gagal terdaftar:', error);
    });
}

if ("Notification" in window && navigator.serviceWorker) {
    Notification.requestPermission().then(permission => {
        if (permission !== "granted") {
            alert("Aktifkan izin notifikasi untuk fitur terbaik.");
        }
    });
}


function showNotification(title, body) {
    if ("Notification" in window && Notification.permission === "granted") {
        navigator.serviceWorker.getRegistration().then(function (registration) {
            registration.showNotification(title, {
                body: body,
                icon: "/path/to/icon.png", // Ganti dengan URL ikon Anda
                badge: "/path/to/badge.png", // Ganti dengan URL badge Anda
                vibrate: [200, 100, 200],
                requireInteraction: true,
                actions: [
                    {
                        action: "stop",
                        title: "Berhenti Tracking"
                    }
                ]
            });
        });
    }
}
