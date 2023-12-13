(function() {
    //Boton para mostrar el Modal de Agregar tarea
    obtenertareas();

    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    async function obtenertareas() {

        try {
            const id = obtenerProyecto();
            const url =  `api/tareas?id=${id}`
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            
            const { tareas }  = resultado
        
            mostrarTareas(tareas);
        }catch (error) {
            console.log(error);
        }   
    }

    function mostrarTareas(tareas) {
        
        
        if(tareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'

        }
        
        tareas.forEach(tarea =>{
            const contenedorTareas = document.createElement('LI');
            contenedorTareas.dataset.tareaId = tarea.id;
            contenedorTareas.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            //Botones
            const btnEstadoTarea =  document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTareas.appendChild(nombreTarea);
            contenedorTareas.appendChild(opcionesDiv);


            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTareas);
            
            
        });
    }

    function mostrarFormulario() {
        const modal=document.createElement('DIV');
        modal.classList.add('modal');
            modal.innerHTML = `
                 <form class="formulario nueva-tarea">
                <legend>Aña<de una nueva tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="Añadir Tarea al Proyecto Actual"
                        id="tarea"
                    />
                </div>
                <div class="opciones">
                    <input
                        type="submit"
                        class="submit-nueva-tarea"
                        value="Añadir Tarea"
                    />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
                 
                </form>
            `;

            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('animar');
             },0);
                        
            
            modal.addEventListener('click',function(e) {
                e.preventDefault();
                if(e.target.classList.contains('cerrar-modal')){
                    
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');

                    setTimeout(() => {
                        modal.remove();
                    }, 500);
                }

                if(e.target.classList.contains('submit-nueva-tarea')) {
                    submitFormularioNuevaTarea();
                }

                
            })

            document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea() {
        const tarea = document.querySelector('#tarea').value.trim();

        if(tarea === ''){
            
            //Mostrar una alerta de error
            mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        }
        
        
        agregarTarea(tarea);
    }


    //Muestra un mensaje en la interface
    function mostrarAlerta(mensaje, tipo, referencia) {
     
        //Previene la creacion de multiples aleras
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        
        //Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Eliminar la alerta despues de 5 segundos
        setTimeout( () => {
            alerta.remove();
        },5000);
    }

    //Consultar el servidor para añadir la tarea
    async function agregarTarea(tarea)  {
        //Construir la peticion
        
        const datos = new FormData(); 
        datos.append('nombre', tarea);
        datos.append('proyectoid', obtenerProyecto());

        
        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            
            const resultado = await respuesta.json();
            console.log(resultado);

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);
            }


        } catch (e) {
            console.log(e);
        }

    }

    function obtenerProyecto() {
        //URLSearchParams trae datos de la Url actual en la se esta atualmente en el navegador
        const proyectoParams = new URLSearchParams(window.location.search);
        //Object.fromEntries(); Funcion helper que convirte un arreglo de pares en un objeto llave-valor
        const proyecto = Object.fromEntries(proyectoParams.entries());
        //Por lo tanto, la funcion fromEntries convirte el dato extraido de windows.location.serch con la funcion URLSerchParams 
        return proyecto.id;
    }

})();
