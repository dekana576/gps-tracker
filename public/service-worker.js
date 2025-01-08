self.addEventListener('notificationclick', function (event) {
    event.notification.close(); // Tutup notifikasi

    event.waitUntil(
        // Ambil semua jendela aktif
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
            // Fokuskan ke salah satu jendela yang aktif
            for (const client of clientList) {
                if ('focus' in client) {
                    return client.focus(); // Fokuskan ke jendela yang sudah ada
                }
            }
            // Tidak ada jendela lain yang aktif, tetap tidak membuka yang baru
            return Promise.resolve(); // Tidak melakukan apa-apa jika tidak ada jendela aktif
        })
    );
});
