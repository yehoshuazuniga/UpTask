<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/crear" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Tu email" value="<?php echo $usuario->email ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu password">

            </div>
            <div class="campo">
                <label for="password2">Repite tu password</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu password">
            </div>
            <input type="submit" class='boton' value="Crear Cuenta">
        </form>
        <div class="acciones">
            <a href="/">Iniciar session</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- Contenedor sm -->
</div>