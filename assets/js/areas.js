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
  function cerrarModal() {
    $('#exampleModalCenter').modal('hide'); // Cierra el modal
  }
  // Función AJAX reutilizable

  // Evento para el botón de envío
  $("#botonEnvio").on("click", function () {
    let datos = new FormData($("#formularioAreas")[0]);
    const descripcion = datos.get("descripcion");
    const codigo = datos.get("codigo");
    datos.append("accion", "create");
    // Validar los campos
    if (descripcion === "" || codigo === "") {
      alert("Por favor, complete todos los campos.");
      return;
    }
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
  });

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
          datos.append("accion","get_hijo");
          datos.append("id",cell.parentNode.dataset.id);
          enviaAjax(datos,function(respuesta, exito, fail){
          
            var lee = JSON.parse(respuesta);
            if(lee.resultado == "get_hijo"){

              document.getElementById('form_id_hijo').value = lee.mensaje.id_hijo;
              document.getElementById('madre_cedula').value = lee.mensaje.cedulaMadre;
              document.getElementById('padre_cedula').value = lee.mensaje.cedulaPadre;
              document.getElementById('nombre').value = lee.mensaje.nombre;
              document.getElementById('fecha_nacimiento').value = lee.mensaje.fecha_nacimiento;
              document.getElementById('genero').value = lee.mensaje.genero;
              document.getElementById('discapacitado').checked = (lee.mensaje.discapacidad == '1')?true:false;
              document.getElementById('observacion').value = lee.mensaje.observacion;
              document.getElementById('nombre-padre').innerHTML = lee.mensaje.nombrePadre;
              document.getElementById('nombre-madre').innerHTML = lee.mensaje.nombreMadre;

              document.getElementById('submit_btn').value = 'Modificar';
              document.getElementById('submit_btn').classList.replace("btn-primary", "btn-warning");
              document.getElementById('submit_btn').classList.add("text-dark");

              document.querySelector("#modal_registar_hijos h5.modal-title").innerHTML = 'Modificar Hijo';


              $("#modal_registar_hijos").modal("show");
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
        console.log(lee);
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
