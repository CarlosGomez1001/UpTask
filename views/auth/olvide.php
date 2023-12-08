

<div class="contenedor olvide">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm"> 
        <p class="descripcion-pagina">Recuperar Password</p>
        <?php include_once __DIR__.'/../templates/alertas.php'; ?>

        <form class="formulario" method="POST" action="/olvide" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="text"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                />
            </div>

            <input type="submit" class="boton" value="Enviar Instrucciones">
            
        </form>

        <div class="acciones">
            <a href="/">Ya tienes cuenta? Inicar Sessión</a>
            <a href="/crear">Aun no tienes una cuenta? Obtener una</a>
            
        </div>
    </div> <!-- Contenedor sm </div>  -->

