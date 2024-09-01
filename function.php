function cambiar_titulo_y_favicon_dinamicamente_con_inactividad() {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const originalTitle = document.title; // Guarda el título original
            const originalFavicon = "🔵"; // Emoji del favicon original
            const titles = [
                { title: "Título 1", favicon: "😀" },
                { title: "Título 2", favicon: "🚀" },
                { title: "Título 3", favicon: "🔥" }
            ]; // Títulos y emojis en rotación
            let currentIndex = 0;
            let cycleCount = 0;
            let inactive = false;
            let inactivityTimer;
            let titleRotationInterval;

            function changeFavicon(emoji) {
                const link = document.querySelector("link[rel*='icon']") || document.createElement("link");
                link.type = "image/x-icon";
                link.rel = "shortcut icon";
                link.href = "data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22><text y=%221.15em%22 font-size=%2224%22>" + emoji + "</text></svg>";
                document.getElementsByTagName("head")[0].appendChild(link);
            }

            function startInactivityTimer() {
                inactivityTimer = setTimeout(function() {
                    inactive = true;
                    startTitleRotation(); // Inicia el cambio de título y favicon después de 2 minutos de inactividad
                }, 120000); // 120000 ms = 2 minutos
            }

            function resetInactivityTimer() {
                clearTimeout(inactivityTimer);
                clearInterval(titleRotationInterval); // Detiene la animación si el usuario vuelve
                document.title = originalTitle; // Restaura el título original
                changeFavicon(originalFavicon); // Restaura el favicon original
                inactive = false;
                startInactivityTimer();
            }

            function startTitleRotation() {
                if (inactive) {
                    titleRotationInterval = setInterval(function() {
                        if (cycleCount === 3) {
                            document.title = originalTitle; // Vuelve al título original
                            changeFavicon(originalFavicon); // Cambia al emoji original
                            cycleCount = 0; // Reinicia el contador
                            setTimeout(function() {
                                currentIndex = 0; // Reinicia la rotación
                            }, 3000); // Espera 3 segundos con el título y emoji originales
                        } else {
                            document.title = titles[currentIndex].title;
                            changeFavicon(titles[currentIndex].favicon);
                            currentIndex = (currentIndex + 1) % titles.length;
                            cycleCount++;
                        }
                    }, 2000); // 2000 ms = 2 segundos
                }
            }

            // Escucha eventos de actividad del usuario
            document.addEventListener('mousemove', resetInactivityTimer);
            document.addEventListener('keydown', resetInactivityTimer);
            document.addEventListener('scroll', resetInactivityTimer);
            document.addEventListener('click', resetInactivityTimer);

            // Inicia el temporizador de inactividad cuando la página carga
            startInactivityTimer();
        });
    </script>
    <?php
}
add_action('wp_head', 'cambiar_titulo_y_favicon_dinamicamente_con_inactividad');
