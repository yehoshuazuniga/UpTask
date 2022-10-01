<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <?php include_once __DIR__ . "/../templates/alertas.php" ?>
        <?php if ($mostrar) { ?>
            <form method="POST" class="formulario">
                <div class="campo">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Tu password">
                </div>

                <!--    <div class="campo">
                <label for="password2">Password</label>
                <input type="password" name="password2" id="password2" placeholder="Tu password">
            </div> -->
                <input type="submit" class='boton' value="Reestablecer password">
            </form>
        <?php } ?>
        <div class="acciones">
<!--             <a href="/">Reestablecer password</a> -->
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
    </div><!-- Contenedor sm -->
</div>