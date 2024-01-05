<?php include_once __DIR__.'/header-dashboard.php'; ?>

<div class="contendor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver al Perfil</a>

    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="nombre">Password Actual</label>
            <input 
                type="password"                
                name="password_actual"
                placeholder="Password Actual"
            />
        </div>
        <div class="campo">
            <label for="nombre">Nuevo Password</label>
            <input 
                type="passeword"                
                name="password_nuevo"
                placeholder="Tu Password Nuevo"
            />
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__.'/footer-dashboard.php'; ?>