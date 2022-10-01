<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>
        <form action="/olvide" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu email">
            </div>
           
            <input type="submit" class='boton' value="Enviar Instrucciones">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obten una</a>
            <a href="/">Iciar sesion/a>
        </div>
    </div><!-- Contenedor sm -->
</div>