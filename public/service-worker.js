self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    if (event.action === "stop") {
        event.waitUntil(clients.openWindow('/')); // Ganti URL ini sesuai kebutuhan Anda
    } else {
        event.waitUntil(clients.openWindow('/')); // Halaman utama
    }
});
