(function () {

    obtenerTareas();
    let tareas = [];

    //boton para mostrar el modal de agrregar la tarea
    const nuevaTarea = document.querySelector('#agregar-tarea');
    nuevaTarea.addEventListener('click', function () {
        mostrarFormulario();
    });

    async function obtenerTareas() {

        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas()
        } catch (error) {
            console.log(error)
        }

    }

    function mostrarTareas() {
        limpiarTareas();
        if (tareas.length == 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas)
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = 'Tarea: ' + tarea.nombre;
            nombreTarea.onclick = function () {
                mostrarFormulario(true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //botonesb

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({ ...tarea });
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.onclick = function () {
                confirmarEliminarTarea({ ...tarea });
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.append(contenedorTarea)

            //console.log(contenedorTarea)
        });
    }

    function mostrarFormulario(editar = false, tarea = {}) {


        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Añade Nueva tarea'}</legend>
                    <div class="campo">
                    <label>Tarea</label>
                    <input 
                        type="text"
                        name="tarea"
                        id="tarea"
                        placeholder='${editar ? "Editar Tarea al proyecto actual" : "Añadir tarea al proyecto actual"}'
                        value = "${tarea.nombre ? tarea.nombre : ''}"
                    />
                    </div>
                    <div class="opciones">
                        <input type="submit" 
                            class="submit-nueva-tarea"
                            value="${editar ? 'Guardar cambios' : 'Añadir'}"
                        /> 
                        <button type="button" class="cerrar-modal">Cancelar</button>
                    </div>
                
            </form>
            `

        setTimeout(() => {
            const formulario = document.querySelector('.formulario')
            formulario.classList.add('animar');
        }, 0)

        modal.addEventListener('click', function (e) {
            e.preventDefault();

            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario')
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove()
                }, 500);

            }

            if (e.target.classList.contains('submit-nueva-tarea')) {
                //console.log('diste click en submit');
                const nombretarea = document.querySelector('#tarea').value.trim();

                if (nombretarea === '') {
                    /* mostrar alerta de error */
                    mostrarAlerta("El nombre de la tarea es obligatorio", 'error', document.querySelector('.formulario legend'));
                    return;
                }
                if (editar) {
                    tarea.nombre = nombretarea;
                    actualizarTarea(tarea)
                } else {
                    agregarTarea(nombretarea)
                }
            }

            //console.log(e.target)
        });


        /*     const btnCerrarModal = document.querySelector('cerrar-modal')
            btnCerrarModal.addEventListener('click', function(e){
                console.log("hola")
            }) */

        document.querySelector('.dashboard').appendChild(modal);
    }

    async function agregarTarea(tarea) {
        //construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {

            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            //  console.table(resultado);
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1500)

                //agregar el obj de tareas al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: 0,
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];
                mostrarTareas()

                //    console.log(tareaObj)
            }

        } catch (error) {
            console.log(error);
        }


    }

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const { estado, id, nombre, proyectoId } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            if (resultado.respuesta.tipo === 'exito') {
             //   mostrarAlerta(resultado.respuesta.mensaje, resultado.respuesta.tipo, document.querySelector('.contenedor-nueva-tarea'))
                Swal.fire(resultado.respuesta.mensaje, resultado.respuesta.mensaje, 'success');

                const modal = document.querySelector('.modal');
                if(modal) modal.remove();

                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }
        } catch (error) {

        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Eliminar Tarea',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea) {
        const { estado, id, nombre } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            console.log(resultado)
            if (resultado.resultado) {
                /*   mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea') ); */
                Swal.fire('Eliminado', resultado.mensaje, 'success');
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas()
            }
        } catch (error) {

        }
    }

    function mostrarAlerta(mensaje, tipo, referencia) {

        //previene a creacion de alertas multimples
        const alertaPrevia = document.querySelector('.alertas');
        if (alertaPrevia) {
            alertaPrevia.remove()
        }
        //muestra un mensaje en la interfas
        const alerta = document.createElement('DIV');
        alerta.classList.add('alertas', tipo);
        // console.log(alerta)
        alerta.textContent = mensaje;
        //console.log(referencia.parentElement)
        //inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling)

        //eliminar la alerta despues de 5 seg

        setTimeout(() => {
            alerta.remove()
        }, 3500)

    }

    function obtenerProyecto() {

        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries())

        return proyecto.id;
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild)
        }
    }

})();


