<div class="contenedor login">
 <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar sesión</p>
        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu email">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu password">
            </div>
            <input type="submit" class='boton' value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obten una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- Contenedor sm -->
</div>