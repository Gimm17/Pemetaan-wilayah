import "./bootstrap";

// Alpine.js is loaded by Livewire v3 automatically
// import Alpine from "alpinejs";
// window.Alpine = Alpine;
// Alpine.start();

// Leaflet (biar map-page bisa pakai window.L)
import "leaflet/dist/leaflet.css";
import L from "leaflet";
window.L = L;

// Fix icon path kalau marker default tidak muncul di Vite
import markerIcon2x from "leaflet/dist/images/marker-icon-2x.png";
import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

window.Swal = Swal;

// Toast from session (Laravel flash)
window.showToastFromSession = (payload) => {
    if (!payload) return;
    Swal.fire({
        toast: true,
        position: "top-end",
        timer: 3500,
        showConfirmButton: false,
        icon: payload.type || "success",
        title: payload.title || "OK",
    });
};
