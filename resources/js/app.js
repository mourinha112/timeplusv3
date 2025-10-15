import "./bootstrap";
import Clipboard from "@ryangjchandler/alpine-clipboard";

// Registra componentes Alpine após a inicialização do Alpine pelo Livewire.
document.addEventListener("alpine:init", () => {
    Alpine.plugin(Clipboard);
});

window.Swal = Swal;

import "./../../vendor/power-components/livewire-powergrid/dist/powergrid";
