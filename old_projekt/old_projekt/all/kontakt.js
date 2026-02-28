const lat = 53.56220025142036;
        const lng = 8.585880426987268;

        const map = L.map('map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);

        marker.bindPopup("<b>Unser Geschäft</b><br>Hafenstraße 179<br>27568 Bremerhaven<br><a href='tel:+4917630311301'>0176 30311301</a>").openPopup();

        marker.on('click', function() {
            map.setView([lat, lng], 17);
        });
