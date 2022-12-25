(function(){
//boton para mostrar el modal de agrregar la tarea
    const  nuevaTarea = document.querySelector('#agregar-tarea');
    nuevaTarea.addEventListener('click', mostrarFormulario);

    function mostrarFormulario(){
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML=`
            <form class="formulario nueva-tarea">
                <legend>Añade Nueva tarea</legend>
                    <div class="campo">
                    <label>Tarea</label>
                    <input 
                        type="text"
                        name="tarea"
                        placeholder="Añadir tarea al proyecto actual"
                    />
                    </div>
                    <div class="opciones">
                        <input type="submit" 
                            class="submit-nueva-tarea"
                            value="Añadir"
                        /> 
                        <button type="button" class="cerrar-modal">Cancelar</button>
                    </div>
                
            </form>
            `

            setTimeout(()=>{
                const formulario = document.querySelector('.formulario')
                formulario.classList.add('animar'); 
            },0)

            modal.addEventListener('click', function(e){
                e.preventDefault();

                if(e.target.classList.contains('cerrar-modal')){
                    const formulario = document.querySelector('.formulario')
                    formulario.classList.add('cerrar');             
                    setTimeout(() => {
                        modal.remove()   
                    }, 500);
                   
                }

                console.log(e.target)
            });

        /*     const btnCerrarModal = document.querySelector('cerrar-modal')
            btnCerrarModal.addEventListener('click', function(e){
                console.log("hola")
            }) */

            document.querySelector('body').appendChild(modal);
    }

})();


