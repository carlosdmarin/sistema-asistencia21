<!-- MODAL DE INICIAR SESIÓN -->
<dialog id="login_modal" class="modal">
    <div class="modal-box p-0 max-w-md bg-white">

        <!-- Botón cerrar FUERA del form -->
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-500 hover:text-gray-700 z-10" onclick="login_modal.close()">✕</button>

       <form method="POST" action="<?php echo BASE_URL; ?>/auth/login">
            <input type="hidden" name="accion" value="login">

            <div class="form">
                <h3 class="text-2xl font-bold text-center text-gray-800 mb-4">
                    Iniciar Sesión
                </h3>

                <div class="flex-column">
                    <label>Usuario o Email</label>
                </div>
                <div class="inputForm">
                    <svg height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"/>
                    </svg>
                    <input type="text" class="input" name="usuario" placeholder="Ingresa tu usuario" required />
                </div>

                <div class="flex-column">
                    <label>Contraseña</label>
                </div>
                <div class="inputForm">
                    <svg height="20" viewBox="-64 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"/>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"/>
                    </svg>
                    <div class="relative w-full">
                        <input type="password" id="login_password" class="input w-full pr-10" name="clave" placeholder="Ingresa tu contraseña" required />
                        <i id="toggle_login_password" class="fas fa-eye absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer hover:text-gray-700 z-10"></i>
                    </div>
                </div>

                <div class="flex-row">
                    <span class="span">¿Olvidaste contraseña?</span>
                </div>

                <button type="submit" class="button-submit-ingresar">Ingresar</button>
            </div>
        </form>

    </div>
</dialog>
