$(document).ready(function () {
  
  function realizarSolicitudAjax(url,metodo,datos,exitoMensaje,errorMensaje,callback  ) {

    $.ajax({url: url, method: metodo, data: datos, processData: false, contentType: false,
      success: function (respuesta) {
        try {
          if (callback) {
            callback(respuesta);
          } else {
            muestraMensaje("Éxito", exitoMensaje, "s");
            //cargar_areas();
           // $("#exampleModalCenter").modal("hide");
            console.log(respuesta);
          }
        } catch (error) {
          console.log("Error", "Respuesta inválida del servidor.", "error");
        }
        return respuesta; // Aquí se imprime la respuesta en la consola
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición AJAX:", textStatus, errorThrown);
        alert(
          "Error Hubo un problema con la solicitud. Inténtelo de nuevo más tarde."
        );
      },
    });
  }
 
  // Función AJAX reutilizable

  // Evento para el botón de envío
  $("#botonEnvio").on("click", function () {
    let datos = new FormData($("#formularioAreas")[0]);
    //REGISTRAR
    if(document.getElementById('botonEnvio').value == 'Registrar'){
      datos.append("accion","create"); 

      const descripcion = datos.get("descripcion");
      const codigo = datos.get("codigo");

    let error = false;
    // Validar los campos
    if (descripcion == "") {
      document.getElementById("error_descripcion").innerHTML = "Por favor, complete todos los campos.";
      error = "true";
    } else {
      document.getElementById("error_descripcion").innerHTML = "";
    }
    if (codigo == "") {
      document.getElementById("error_codigo").innerHTML = "Por favor, complete todos los campos.";
      error = "true";
    } else {
      document.getElementById("error_codigo").innerHTML = "";
    }
    if(error == "true"){ console.log("hola"); return;}

    console.log(JSON.stringify(datos),"hola 2");

    // Llamar a la función AJAX
    realizarSolicitudAjax(
      "",
      "POST",
      datos,
      "Área registrada correctamente",
      "Hubo un problema al registrar el área."
    );
    cargar_areas();
    cerrarModal();

    return
    }
    //MODIFICAR
    else if (document.getElementById('botonEnvio').value == 'Modificar'){
      datos.append("id",document.getElementById('id').value);
      datos.append("accion","update");
      const descripcion = datos.get("descripcion");
      const codigo = datos.get("codigo");

    let error = false;
    // Validar los campos
    if (descripcion == "") {
      document.getElementById("error_descripcion").innerHTML = "Por favor, complete todos los campos.";
      error = "true";
    } else {
      document.getElementById("error_descripcion").innerHTML = "";
    }
    if (codigo == "") {
      document.getElementById("error_codigo").innerHTML = "Por favor, complete todos los campos.";
      error = "true";
    } else {
      document.getElementById("error_codigo").innerHTML = "";
    }
    if(error == "true"){ console.log("hola"); return;}

    console.log(JSON.stringify(datos),"hola 2");

    // Llamar a la función AJAX
    realizarSolicitudAjax(
      "",
      "POST",
      datos,
      "Área registrada correctamente",
      "Hubo un problema al registrar el área."
    );
    cargar_areas();
    cerrarModal();
    return;
    }

 

    
  });

  // Modales 
  function cerrarModal() {
    $('#exampleModalCenter').modal('hide'); // Cierra el modal
  }

  function abrirModalModificar(datos) {


    document.getElementById('formularioAreas').value = datos.id_area;
    document.getElementById('descripcion').value = datos.descripcion;
    document.getElementById('codigo').value = datos.codigo;
    document.getElementById('id').value = datos.id_area;

    document.getElementById('botonEnvio').value = 'Modificar';
    document.getElementById('botonEnvio').classList.replace("btn-primary", "btn-warning");
    document.getElementById('botonEnvio').classList.add("text-dark");
    document.querySelector("#exampleModalCenter h5.modal-title").innerHTML = 'Modificar';

    $("#exampleModalCenter").modal("show");
}

  // Funcion para los botones eliminar y modificar de la lista
  
  rowsEvent("tbody_areas",(target,cell)=>{
    if(!cell.parentNode.dataset.id){return false;}

     if(cell.classList.contains("cell-action")){
       while(target.tagName!='BUTTON'&&target.tagName!=cell.tagName){
         count++;
         if(count>100){
           console.error('se paso el while');
           return false;
           break
         }
         target=target.parentNode;
       }

       if(target.tagName == "BUTTON"){
         if(target.dataset.action == "eliminar"){ // ELIMINAR

           muestraMensaje("¿Seguro", "Esta seguro de querer eliminar el area", "?",(resp)=>{

             if(resp){
              target.disabled = true;
              target.blur();
              let datos = new FormData();
              datos.append("accion", "destroy");
              datos.append("id", cell.parentNode.dataset.id);
          
              // Crear un objeto para almacenar los datos
              // let dataObject = {};
              // datos.forEach((value, key) => {
              //     dataObject[key] = value;
              // });
                realizarSolicitudAjax(
                "",
                "POST",
                datos,
                "Área listada correctamente",
                "Hubo un problema al listando el área.",
                (respuesta)=>{                         
                 let res = JSON.parse(respuesta);

                 if(res.estado == 200){
                    cargar_areas();
                 }
                 else {                 
                 cargar_areas();
                 }
               }
              );
              cargar_areas();

             }
           });

         }
         else if (target.dataset.action == 'modificar'){ // MODIFICAR
          var datos = new FormData();
          datos.append("accion","show");
          datos.append("id",cell.parentNode.dataset.id);
          
          enviaAjax(datos,function(respuesta, exito, fail){
            var lee = JSON.parse(respuesta);
            if(lee.estado == 200){
                abrirModalModificar(lee.datos);
                return;
            }
            else if (lee.resultado == 'is-invalid'){
                muestraMensaje(lee.titulo, lee.mensaje,"error");
            }
            else if(lee.resultado == "error"){
                muestraMensaje(lee.titulo, lee.mensaje,"error");
                console.error(lee.mensaje);
            }
            else if(lee.resultado == "console"){
                console.log(lee.mensaje);
            }
            else{
                muestraMensaje(lee.titulo, lee.mensaje,"error");
            }
          });
         }
       }
     }
  },false);

  // Función para listar areas

  function cargar_areas() {
    let datos = new FormData();
    datos.append("accion", "list");
    realizarSolicitudAjax(
      "",
      "POST",
      datos,
      "Área listada correctamente",
      "Hubo un problema al listando el área.",
      (respuesta) => {
        let lee = JSON.parse(respuesta);
        if ($.fn.DataTable.isDataTable("#tabla_areas")) {
          $("#tabla_areas").DataTable().destroy();
        }
        $("#tbody_areas").html("");

        if (!$.fn.DataTable.isDataTable("#tabla_areas")) {
          $("#tabla_areas").DataTable({
              language: {
                  lengthMenu: "Mostrar _MENU_ por página",
                  info: "Mostrando página _PAGE_ de _PAGES_",
                  infoEmpty: "No hay registros disponibles",
                  infoFiltered: "(filtrado de _MAX_ registros totales)",
                  search: "Buscar:",
                  paginate: {
                      first: "Primera",
                      last: "Última",
                      next: "Siguiente",
                      previous: "Anterior",
                  },
              },
              columnDefs: [
                  {targets: 1, title: "ID" },
                  { targets: 2, title: "Descripción" },
                  { targets: 3, title: "Código" },
              ],
              columns: [
                  { data: "id_area" },
                  { data: "descripcion" },
                  { data: "codigo" },
                  { data: "extra" },
              ],
              data: lee.datos,

              createdRow: function (row, data) {
                  row.dataset.id = data.id_area;

                  var acciones = row.querySelector("td:nth-child(4)");
                  
                  acciones.style.width = "15%";
                  acciones.style.textAlign = "center";
                  acciones.classList.add('text-nowrap', 'cell-action');

                  var btn1 = crearElem("button", "class,btn btn-warning,data-action,modificar", "<span class='bi bi-pencil-square' title='Modificar'></span>");
                  var btn2 = crearElem("button", "class,btn btn-danger ml-1,data-action,eliminar", "<span class='bi bi-trash' title='Eliminar'></span>");
                  
                  acciones.appendChild(btn1);
                  acciones.appendChild(btn2);
              },
              autoWidth: false,
          });
      
          if (lee.mensaje.length > 0) {
              $("#tabla_areas")[0].parentNode.classList.add("table-responsive");
          }
      } else if (lee.resultado == "is-invalid") {
          console.log("Error: ", lee.titulo, lee.mensaje);
      } else if (lee.resultado == "error") {
          console.log("Error: ", lee.titulo, lee.mensaje);
          console.error("Error detallado: ", lee.mensaje);
      } else if (lee.resultado == "console") {
          console.log("Mensaje de consola: ", lee.mensaje);
      } else {
          console.log("Error desconocido: ", lee.titulo, lee.mensaje);
      }


        
      });
  }

  $('#exampleModalCenter').on('hidden.bs.modal', function (e) {
    document.getElementById('descripcion').value = "";
    document.getElementById('codigo').value = "";
  })

  cargar_areas();
});
