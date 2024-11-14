function muestraMensaje(titulo, mensaje = '', icono = '', customProp = false, func) {
	if(typeof titulo == 'undefided' || titulo == null){titulo = '';}
	if(typeof mensaje == 'undefided' || mensaje == null){mensaje = '';}
	if(typeof icono == 'undefided' || icono == null){icono = 'error';}
	else{

		switch (icono) {
			case 's':
			case 'success':
				icono = 'success';
				break;
			case 'e':
			case 'error':
				icono = 'error';
				break;
			case '?':
			case 'q':
			case 'question':
				icono = 'question';
				break;
			case '¡':
			case 'i':
			case 'info':
				icono = 'info';
				break;
			case 'warning':
			case 'w':
			case '!':
				icono = 'warning';
				break;
		}
	}
	if(typeof customProp === 'function'){
		let tempprop = func;
		func = customProp;
		customProp = tempprop; 
	}


	var obj = {};
	obj.title = titulo;
	if(/<ENDL>/.test(mensaje)){
		mensaje = mensaje.replace(/<ENDL>/g, '<br>');
		obj.html = mensaje;
	}
	else{
		obj.text = mensaje;
	}
	obj.icon = icono;
	obj.showConfirmButton = false;
	obj.showCancelButton = true;
	obj.cancelButtonText = 'Cerrar';

	if(typeof func === 'function'){
		obj.showConfirmButton = true;
		obj.confirmButtonText = 'Aceptar';
		obj.focusConfirm = true;
	}
	else{
		obj.focusCancel = true;
	}

	// var modal = document.querySelector('.modal.show');
	// if (modal) {

	// 	obj.target = `#${modal.id}`;
	// }

	if(customProp){
		// willClose
		// didClose
		// willOpen
		// didOpen
		if(customProp.modal){

			let modal = document.querySelector(customProp.modal);
			if(!modal){
				modal = document.querySelector(".modal.show");
			}
			
			if (modal) {

				obj.target = `#${modal.id}`;
			}


			delete customProp.modal;
		}
		for (var p in customProp){
			obj[p] = customProp[p];
		}
	}
	if(!obj.ignore){
		if(typeof func === 'function'){
			Swal.fire(obj).then((result)=>{
				func(result.isConfirmed,result);
			});
		}
		else{
			Swal.fire(obj);
		}
	}
	else{
		if(typeof func === 'function'){
			func(true);
		}
	}
}
let ajaxCounterConsult = 0;
let ajaxCounterConsult_body = 0;
function enviaAjax(datos, func_success,func_beforesend="loader_main") {
	if(typeof func_success !== "function"){
		console.error("falta la funcion success");
	}
	var xhr;
	var promesa = new Promise(function(exito,fail) {
		xhr = $.ajax({
			async: true,
			url: "",
			type: "POST",
			contentType: false,
			data: datos,
			processData: false,
			cache: false,
			beforeSend: function () {
				if(typeof func_beforesend === "function"){
					func_beforesend();
				}
				else if (func_beforesend == "loader_main"){
					ajaxCounterConsult++;
					loader_main(true,ajaxCounterConsult);
				}
				else if(func_beforesend == "loader_body"){
					ajaxCounterConsult_body++;
					loader_body(true,ajaxCounterConsult_body);
				}
			},
			timeout: 30000,
			success: function (respuesta) {
				try {
					if(respuesta==='close_sesion_user'){
						location.reload();
					}
					else if(typeof func_success === "function"){
						func_success(respuesta,exito,fail);
					}
					else throw "No hay una función definida";

				} catch (e) {

					var temp = respuesta.replace(/\s/g, "");
					if(/^<br\/\><b>Fatalerror\<\/b>:Allowedmemorysizeof/.test(temp)){
						muestraMensaje("Error", "Exceso de memoria utilizada", "e");
						fail("Exceso de memoria utilizada");
					}
					else{
						alert("Error en " + e.name + " !!!");
						fail(e.message);
					}
					console.error(e);
					console.log(respuesta);
				}
			},
			error: function (request, status, err) {
				if (status == "timeout") {
					if(fail() != 'timeout'){
						muestraMensaje("Servidor Ocupado", "Intente de nuevo", "error");
					}

				} 
				else if(request.readyState===0){
					if(status != 'abort'){
					muestraMensaje("No Hay Conexión Con El Servidor", "Intente de nuevo", "error");}
				}
				else {
					muestraMensaje("Error", request + status + err, "error");
				}
				if(status != 'abort'){fail(request, status, err);}

				if (func_beforesend == "loader_main"){
					ajaxCounterConsult--;
					loader_main(false,ajaxCounterConsult);
				}
				else if(func_beforesend == "loader_body"){
					ajaxCounterConsult_body--;
					loader_body(false,ajaxCounterConsult_body);
				}
			},
			complete: function (xhr, status) { // ocurre en el abort también
				// modalcarga(false).then(function() {
				// 	if(status === "success"){
				// 		exito(xhr.responseText);
				// 	}
				// });
				if(func_beforesend == 'loader_main' || func_beforesend == 'close_loader_main'){
					ajaxCounterConsult--;
					loader_main(false,ajaxCounterConsult);
				}
				else if(func_beforesend == 'loader_body' || func_beforesend == 'close_loader_body'){
					ajaxCounterConsult_body--;
					loader_body(false,ajaxCounterConsult_body);
				}
			},
		});
	})

	return {p:promesa,xhr:xhr};
	
}

function loader_main(control = true,counter = 0){	
	if(document.querySelector("main.main-content")){
		var main = document.querySelector("main.main-content");

		if(control){
			if(!main.querySelector("div.loader-main")){
				main.appendChild(crearElem("div","class,loader-main"));

			}
		}
		else{
			if(main.querySelector("div.loader-main") && counter <= 0){
				main.removeChild(main.querySelector("div.loader-main"));
				ajaxCounterConsult = 0;
			}
		}

	}
}

function loader_body(control = true,counter = 0){	

	if(control){
		if(!document.body.querySelector("div.loader-body")){
			document.body.appendChild(crearElem("div","class,loader-body"));

		}
	}
	else{
		if(document.body.querySelector("div.loader-body") && counter <= 0){
			document.body.removeChild(document.body.querySelector("div.loader-body"));
			ajaxCounterConsult_body = 0;
		}
	}

}




function eventoKeypress(etiqueta,exp){
	if(typeof etiqueta === "string"){
		etiqueta = document.getElementById(etiqueta);
	}
	etiqueta.onkeypress=function(e){
		validarKeyPress(e,exp);
	}
}
function eventoKeyup(etiqueta, exp, mensaje, etiquetamensaje, func, func2){
	if(typeof etiqueta === "string"){
		etiqueta = document.getElementById(etiqueta);
	}
	etiqueta.onkeyup=function(e){
		if(e.key == "Enter"){
			return false;
		}



		if(typeof func ==="function"){// antes de validar
			func(this);
		}
		if(this.allow_empty == true && this.value == ''){
			var resp = validarKeyUp(true, $(this), mensaje, etiquetamensaje);
			this.classList.remove("is-valid","is-invalid");
		}
		else {
			var resp = validarKeyUp(exp,$(this),mensaje,etiquetamensaje);
		}

		if(typeof func2 === "function"){//después de validar
			func2(this, resp);
		}

	}
	etiqueta.validarme = function(){
		if(this.allow_empty == true && this.value == ''){
			return validarKeyUp(true, $(this), mensaje, etiquetamensaje);
		}
		return validarKeyUp(exp, $(this), mensaje, etiquetamensaje);
	}
}


function sepMilesMonto (value, cond=false, sigSepar = '.',sigDecim = ','){
	if(typeof value !== 'string'){
		return value;
	}
	var negativo = false;
	if(value!=''){
		var x = 0;
		if(/^[-]/.test(value)){
			negativo = true;
		}

		value = value.replace(/^[0]*\D*[0]*|\D/g,'');
		for(var i = 3;i>value.length;value = '0'+value){
			x++;
			if(x>1000){break;}
		}
		if(!cond){
			var expmonto= new RegExp("(\\d)(?:(?=\\d{3}\\"+sigDecim+"\\d+)|(?=(?:\\d{3})+\\"+sigDecim+"))","g");
			value = value.replace(/(\d)(\d\d)$/,"$1"+sigDecim+"$2");
		//expmonto = /(\d)(?:(?=\d{3}$)|(?=(?:\d{3})+$))/g;// si no tiene decimales
		value = value.replace(expmonto,"$1"+sigSepar);
		}
		else{
			value = value.replace(/(\d)(\d\d)$/,"$1.$2");
		}
		if(negativo){
			value = '-'+value;
		}
		return value;
	}
	else{
		return '';
	}
}

function sepMiles (value, cond=false, sigSepar = '.',sigDecim = ','){
	if(typeof value !== 'string'){
		return value;
	}
	var negativo = false;
	if(value!=''){
		var x = 0;
		if(/^[-]/.test(value)){
			negativo = true;
		}
		if(!cond){
			var expmonto = /(\d)(?:(?=\d{3}$)|(?=(?:\d{3})+$))/g;
			value = value.replace(expmonto,"$1"+sigSepar);
		}
		else{
			var expmonto= new RegExp(`\\${sigSepar}`,"g");
			value = value.replace(expmonto,"");
		}
		if(negativo){
			value = '-'+value;
		}
		return value;
	}
	else{
		return '';
	}
}

function eventoMonto(etiqueta,func_afterkeyup = function(e){e.value = sepMilesMonto(e.value); },mensaje = "Ingrese un monto valido"){
	let montoExp = /^\d{1,3}(?:[\.]\d{3})*[,]\d{2}$/;
	var n = 16;// decimal (12,2) 1.000.000.000,00

	
	if(typeof etiqueta !== "string"){console.error("la etiqueta debe ser un string con el id del formulario de monto",etiqueta); return false; }
	eventoKeyup(etiqueta, montoExp, mensaje, undefined, func_afterkeyup);
	eventoKeypress(etiqueta, /^[0-9]$/);

	//Si se está repitiendo, ignorar
	document.getElementById(etiqueta).addEventListener('keydown', function(keyboardEvent) {if (keyboardEvent.repeat) keyboardEvent.preventDefault(); });
	document.getElementById(etiqueta).onchange = document.getElementById(etiqueta).oninput = function(){this.value = sepMilesMonto(this.value); validarKeyUp(montoExp, $(this), mensaje); }
	document.getElementById(etiqueta).maxLength = n;
	document.getElementById(etiqueta).validarme = function(){
		if(this.allow_empty == true && this.value == ''){
			return validarKeyUp(true, $(this), mensaje);
		}
		var value_temp = this.value.replace(/\./g, '');
		if(/[0-9]{1,18}[,\.][0-9]{2}/.test(value_temp)){
			return validarKeyUp(true, $(this), mensaje);
		}
		else{
			return validarKeyUp(false, $(this), mensaje);
		}
	}
	document.getElementById(etiqueta).classList.add("text-right");
	document.getElementById(etiqueta).autocomplete = 'off';


}

function eventoFecha(etiqueta,mensaje = "La fecha es invalida"){
	if(typeof etiqueta !== "string"){console.error("la etiqueta debe ser un string con el id del formulario de monto",etiqueta); return false; }
	eventoKeyup(etiqueta, /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/, mensaje);
	eventoKeypress(etiqueta, /^[0-9]$/);
	document.getElementById(etiqueta).onchange = function(){eventoKeyup(this, /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}$/, mensaje);}
	document.getElementById(etiqueta).validarme = function(){
		return V.fecha(this.value);
	}
}


function removeSpace(cadena)// remueve espacios al final, al principio y los dobles espacios
{
	if(typeof cadena==='string')
	{
		if(/(?:^\s)|(?:[\s][\s])|(?:[\s]+$)/.test(cadena))
		{
			cadena = cadena.replace(/\n/mg,"---WHITE_ENDL_SPACE---");
			cadena=cadena.replace(/(?:^\s+)|(?:[\s]+$)/mg,"");
			while(/[\s][\s]/.test(cadena)) cadena=cadena.replace(/(?:[\s][\s])+/," ");
			cadena = cadena.replace(/---WHITE_ENDL_SPACE---/g,"\n");
		}
		return cadena;
	}
	else{
		console.error('El argumento debe ser un string');
		return undefined;
	}
};

function validarKeyUp(er, etiqueta, mensaje, etiquetamensaje) {
	if(typeof etiqueta === 'string'){etiqueta = $("#"+etiqueta);}
	else if (etiqueta.tagName){
		etiqueta = $(etiqueta);
	}
	if(etiqueta.data("span")){
		etiquetamensaje = $("#"+etiqueta.data("span"));
	}
	else if(typeof etiquetamensaje === 'undefined'){
		console.error("falta la etiqueta mensaje",etiqueta);
	}
	if(er === true||er === false){
		a = er;
	}
	else{
		a = er.test(etiqueta.val());
	}
	if (a) {
		if (etiqueta.hasClass("is-invalid")) {
			etiqueta.toggleClass("is-invalid");
		}
		if (!etiqueta.hasClass("is-valid")) {
			etiqueta.toggleClass("is-valid");
		}
		etiquetamensaje.text("");
		return true;
	} else {
		if (etiqueta.hasClass("is-valid")) {
			etiqueta.toggleClass("is-valid");
		}
		if (!etiqueta.hasClass("is-invalid")) {
			etiqueta.toggleClass("is-invalid");
		}
		etiquetamensaje.text(mensaje);
		return false;
	}
}

function validarKeyPress(e, er) {
	var codigo = e.keyCode;
	var tecla = String.fromCharCode(codigo);
	if (er.test(tecla) === false) {
		e.preventDefault();
		return false;
	}
	else return true;
}

class Validaciones{

	constructor(){
		this.expCedula = /(?:(?:^[ve][-][0-9]{7,8}$)|(?:^[jg][-][0-9]{8,10}$))/i;
		this.expCedula_opt = /(?:(?:^[0-9]{7,8}$)|(?:^[ve][-\s]?[0-9]{7,8}$)|(?:^[jg][-\s]?[0-9]{8,10}$))/i;
		this.expHora = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
		this.expTelefono = /^[0-9]{4}[-\s]?[0-9]{7}$/;
		this.expEmail = /^[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/;
		this.expMonto = /^\d{1,3}(?:[\s]\d{3})*[,]\d{2}$/;
		this.expPass = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,20}$/
		
		///^[a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]$/
		///^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]/
	}

	cedula(ci,tipo=true)	{//tipo = true obligatorio v,e,j false no
		if(tipo == true){
			var exp = this.expCedula_l;
		}
		else var exp = this.expCedula;

		return exp.test(ci);
	}
	fecha(dateV, mensaje=false){
		if(typeof dateV==='string')
		{
			if(!/^[\d][\d][\d][\d][\/-][\d]?[\d][\/-][\d]?[\d]$/.test(dateV)) {return false;}
			dateV=dateV.split(/\D/);
		}
		else {return false;}
		var d=dateV[2];//dia
		var m=dateV[1];//mes
		var a=dateV[0];//year
		var ok = true;
		if( (a < 1900) || (m < 1) || (m > 12) || (d < 1) || (d > 31) )
			if(mensaje == true){
				if(a<1900){ok = 'El año no puede ser inferior al 1900'}
				else{
					ok = "El mes o el día es invalido";
				}
			}
			else{
				ok = false;
			}
		else
		{
			if((a%4 != 0) && (m == 2) && (d > 28)){
				if(mensaje == true){
					ok = "Fecha no valida para año bisiesto";
				}
				else{
					ok = false;
				}
			}
			else
			{
			if( (((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)) )
				if(mensaje == true){
					ok = "El día no es valido para el mes";
				}
				else{
					ok = false;
				}
			}
		}
		return ok;
		//no puede ser menor a 1900 el año
	}
	hora(string){

		return this.expHora.test(string);
	}
	telefono(string){

		return this.expTelefono.test(string);
	}
	email(string){

		return this.expEmail.test(string);
	}
	monto(string,sep=true, sepr='s', decim = ','){
		if(sep==true){
			var exp = new RegExp("^[0-9]{1,3}(?:[\\"+sepr+"][0-9]{3}){0,6}[\\"+decim+"][0-9]{2}$");
		}
		else{
			var exp = /^[0-9]{1,18}(?:[\.][0-9]{2})?$/;
		}
		return exp.test(string);
	}
	texto(string,n=100,vacio=false){
		if(vacio==true){
			vacio = 0;
		}
		else vacio = 1;
		var exp = new RegExp("^[a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{"+vacio+","+n+"}$","m");
		return exp.test(string);
	}
	expTexto(n=100,vacio=false){
		if(vacio==true){
			vacio = 0;
		}
		else vacio = 1;
		var exp = new RegExp("^[a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{"+vacio+","+n+"}$","m");
		return exp;
	}
	alfanumerico(string,n=100,vacio=false){
		if(vacio==true){
			vacio = 0;
		}
		else vacio = 1;
		var exp = new RegExp("^[0-9.,\/#!$%\^&\*;:{}=\-_`~()”“\"…a-zA-Z\\säÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ]{"+vacio+","+n+"}$","m");
		return exp.test(string);

	}
	numero(string,n='+'){
		var exp = new RegExp(`^[0-9]${n}$`);
		return exp.test(string);
	}

	is_in(string){// verifica si un valor es igual a los siguientes argumentos (acepta arreglos)
		for(var i = 1;i<arguments.length; i++){
			if(Array.isArray(arguments[i])){
				var elem = arguments[i];
				for (var a = 0; a < elem.length; a++){
					if(string == elem[a]){
						return true;
					}
				}
			}
			else if(string == arguments[i]){
				return true;
			}
		}
		return false;
	}
}

function crearElem(type,attr='',content='',separador = ',')
{
	var elem=document.createElement(type);
	if(elem)
	{
		if(attr!='')
		{
			attr=attr.split(separador);
			if(attr.length%2==0)
			{
				for(var i=0;i<attr.length;i++)
				{
					attr[i] = attr[i].replace(/(?:^\s*)|(?:\s*$)/g, "");

					attr[(i+1)] = attr[(i+1)].replace(/(?:^\s*)|(?:\s*$)/g, "");
					if(attr[i+1] == "_"){attr[i+1] = '';}
					elem.setAttribute(attr[i],attr[(i+1)]);
					i++;
				}
				if(content.tagName){elem.appendChild(content);}
				else if(content!=''){elem.innerHTML=content;}
				return elem;
			}
			else
			{
				console.error('Los attr debent tener un valor separado por "'+separador+'" ej. id'+separador+'value')
				return undefined;
			}
		}
		else if(content !=''){
			if(content.tagName){elem.appendChild(content);}
			else{elem.innerHTML=content;}
		}
		return elem;
	}
	else return undefined;
}

const V = new Validaciones();


function iniciar_show_password(){// aplica el evento a todos los elementos que tienen la clase .show-password-btn
	var btn = document.querySelectorAll(".show-password-btn");
	var ojo_abierto = "bi-eye-fill";
	var ojo_cerrado = "bi-eye-slash-fill";
	var icon_ini = "bi";
	for (var x of btn){

		
		
		if(x.dataset.inputpass){
			if(document.getElementById(x.dataset.inputpass)){
				x.classList.add(icon_ini, ojo_cerrado);
				document.getElementById(x.dataset.inputpass).type = "password";
				x.onclick=function(){
					if(document.getElementById(x.dataset.inputpass).type == 'text' && !document.getElementById(x.dataset.inputpass).disabled){
						document.getElementById(x.dataset.inputpass).type = "password";
						this.classList.remove(ojo_abierto);
						this.classList.add(ojo_cerrado);
					}
					else if(document.getElementById(x.dataset.inputpass).type == 'password' && !document.getElementById(x.dataset.inputpass).disabled){
						document.getElementById(x.dataset.inputpass).type = "text";
						this.classList.remove(ojo_cerrado);
						this.classList.add(ojo_abierto);
					}
				}
			}
			else{
				console.error(`El elemento '${x.dataset.inputpass}' no existe`);
			}
		}
		else{
			console.error("El elemento no tiene el atributo 'data-inputpass'",x);
		}
	}
}

function cedulaKeypress(tag){
	if(typeof tag === 'string'){
		tag = document.getElementById(tag);
	}
	tag.onkeypress=tag.oninput= tag.onchange=function(e){
		tecla = String.fromCharCode(e.keyCode);
		var cont_tecla_letra;

		if((!(cont_tecla_letra = /^[vejg]$/i.test(tecla))) && !/^[vejg][-]/i.test(this.value)){
			var pref = this.value.replace(/[^vejg]/ig,"");
			pref = (pref!='')?pref.toUpperCase()+'-':"V-";
			this.value = pref + this.value.replace(/[^0-9]/g,"");
			if(this.value.length >= this.value.maxLength){e.preventDefault();return 0;}
			validarKeyPress(e,/^[0-9]$/);
		}
		else if(cont_tecla_letra){
			this.value = this.value.replace(/[^0-9]/g,"");
			this.value = tecla.toUpperCase()+"-"+this.value;
			e.preventDefault();
		}
		else{
			validarKeyPress(e,/^[0-9]$/);
		}

	}
	tag.maxLength = 12;
}

function eventoPass(tag){// para que la contraseña tenga entre 6 y 20 caracteres una minuscula, una mayuscula y un numero
if(typeof tag ==='string'){
		tag = document.getElementById(tag);
	}
	if(tag){
		tag.onkeyup=function(e){
			
			if(this.dataset.span){
				etiquetamensaje = $("#"+this.dataset.span);
			}
			else if(typeof etiquetamensaje === 'undefined'){
				console.error("falta la etiqueta mensaje",this);
			}

			if (!(this.allow_empty === true && this.value == '')) {
				if(!V.expPass.test(this.value)){

					this.classList.remove("is-valid");
					this.classList.add("is-invalid");
					var mensaje = "";
					
					if(!/^.{6,20}$/.test(this.value)) mensaje = "entre 6 y 20 caracteres";
					if(!/^(?=.*?[A-Z]).{1,}$/.test(this.value)) mensaje += (mensaje == '')?"una letra mayúscula": ", una letra mayúscula";
					if(!/^(?=.*?[a-z]).{1,}$/.test(this.value)) mensaje += (mensaje == '')?"una letra minúscula": ", una letra minúscula";
					if(!/^(?=.*?[0-9]).{1,}$/.test(this.value)) mensaje += (mensaje == '')?"un numero": " y un numero";

					etiquetamensaje.text("La contraseña debe tener al menos "+mensaje);
				}
				else{
					this.classList.add("is-valid");
					this.classList.remove("is-invalid");
					etiquetamensaje.text("");
				}
			}
			else{
				this.classList.remove("is-valid");
				this.classList.remove("is-invalid");
				etiquetamensaje.text("");
			}

		}

		tag.validarme = function(){
			this.onkeyup();
			if(this.allow_empty === true && this.value == ''){
				return true;
			}
			else{
				return V.expPass.test(this.value);
			}
		}
	}

}

function eventoTelefono(tag){
	if(typeof tag ==='string'){
		tag = document.getElementById(tag);
	}
	if(tag){
		tag.onkeyup=function(e){
			this.value = this.value.replace(/^([0-9]{4}|\+[0-9]{5})\D+?([0-9]+)/, "$1-$2");
		}
	}
}


function removeSpace(cadena)
{
	if(typeof cadena==='string')
	{
		if(/(?:^\s)|(?:[\s][\s])|(?:[\s]+$)/.test(cadena))
		{
			cadena = cadena.replace(/\n/mg,"---WHITE_ENDL_SPACE---");
			cadena=cadena.replace(/(?:^\s+)|(?:[\s]+$)/mg,"");
			while(/[\s][\s]/.test(cadena)) cadena=cadena.replace(/(?:[\s][\s])+/," ");
			cadena = cadena.replace(/---WHITE_ENDL_SPACE---/g,"\n");
		}
		return cadena;
	}
	else{
		console.error('El argumento debe ser un string');
		return undefined;
	}
};
function rowsEvent(tbody,func,control=true){//solo permite un evento del rowsEvent a la vez
	if(typeof tbody==='string')
	{
		tbody=document.getElementById(tbody);
	}

	var handler_rowsEvent = function(e){
			var elem=e.target;
			count=0;
			if(control){// trata de retorna la fila
				while(elem.tagName!='TR'&&elem.tagName!='TBODY'&& elem!=this){
					count++;
					if(count>100)
					{
						console.error('se paso el while');
						return false;
						break
					}
					elem=elem.parentNode;
				}
				if(elem.tagName=='TBODY' || elem == this){
					return false;
				}
				if(!elem.getElementsByTagName('td')[0].classList.contains("dataTables_empty")){
					func(elem,e.target);
				}
			}
			else{//retorna el elemento donde se dio click ej un td de un talbe tr y la celda
				var cell = elem;
				while(cell.tagName!='TD'&&cell.tagName!='TH'&& cell!=this){
					count++;
					if(count>100)
					{
						console.error('se paso el while');
						return false;
						break
					}
					cell=cell.parentNode;
				}
				func(elem,cell);
			}
		}

	var removeEvent = function (){
		tbody.removeEventListener('click', handler_rowsEvent, true);
	};


	if(typeof func==='function')
	{
		if(typeof tbody.removeRowsEvent === "function"){
			rowsEvent(tbody.id,false);
		}

		tbody.addEventListener('click', handler_rowsEvent, true);
		tbody.removeRowsEvent = removeEvent;
	}
	else if(func === false){// si es false se elimina el evento
		if(typeof tbody.removeRowsEvent === "function"){
			tbody.removeRowsEvent();
			tbody.removeRowsEvent = undefined;
		}
	}
	else{
		console.error('el segundo argumento debe ser una función que se ejecutara al hacer click en el table');
	}
}

function rowsEventActions(tbody,func){
	rowsEvent(tbody,(target,cell)=>{
	 	if(!cell.parentNode.dataset.id){
	 		return false;
	 	}
	 	if(cell.classList.contains("cell-action")){
	 		while(target.tagName!='BUTTON'&&target.tagName!=cell.tagName){
	 			count++;
	 			if(count>100)
	 			{
	 				console.error('se paso el while');
	 				return false;
	 				break
	 			}
	 			target=target.parentNode;
	 		}
	 		if(target.tagName == "BUTTON"){
	 			if(target.disabled == false){
	 				func(target.dataset.action, cell.parentNode.dataset.id ,target);
	 			}
	 		}

	 	}
	},false);
}


FormData.prototype.consoleAll = function() {
	var obj = {};
	for( let [key,value] of this){
		obj[key] = value;
		//console.log(`${key} :: '${value}'`);
	}
	console.table(obj);
};
FormData.prototype.groupby = function(name) {
	var temp = [];
	if(this.has(name)){
		temp = this.getAll(name);
	}
	this.set(name,JSON.stringify(temp));
};
FormData.prototype.setter = function(name,verdad = 1,falso = 0) {
	// si ya existia el dato 'name' el valor es 'verdad' si no es 'falso'
	this.set(name,(this.has(name))?verdad:falso);
};


FormData.prototype.clean = function(name) {
	if(this.has(name)){
		this.set(name,removeSpace(this.get(name)));
	}
};

FormData.prototype.removeSpace = function() {
	// este metodo tiene el problema de que usa el metodo set del formData
	// y si hay dos "name" iguales los elimina y deja uno solo 
	// incluso creo que fallara si hay dos names iguales y se llama este metodo
	// o se queda con el ultimo que encuentre no se XD
  for( let [key,value] of this){
		this.set(key, removeSpace(value));
	}
};

// add a save value an element for a HTMLFormElement of all elements whith name and element in a object
HTMLFormElement.prototype.save = function() {
	this.savedata = new FormData(this);
}
// now load the object to the form
HTMLFormElement.prototype.load = function(obj={not:[]}) {
	if(this.savedata !== undefined && this.savedata instanceof FormData){
		this.reset();
		this.savedata.forEach((value, key) => {
			let control = true;
			if(Array.isArray(obj.not)){
				if(obj.not.length > 0){
					obj.not.forEach((ele)=>{
						console.log("hola");
						if(ele===key){
							control = false;
							return false;
						}
					});
				}

				if(control) {
					this[key].value = value;
				}
			}
			else{
				console.error("debe proporcionar un array en not");
			}
		});
	}
}







function add_event_to_label_checkbox(){
	for(var x of document.querySelectorAll("label.check-button")){

		if(x.onkeypress==null){
			x.setAttribute("tabindex","0");
			
			x.onkeypress=function(e){
				if(e.key == 'Enter'){
					if(document.getElementById(this.getAttribute("for"))){
						document.getElementById(this.getAttribute("for")).click();
					}
				}
			}
		}
	}
}
function fetchNotifications() {
    $.ajax({
        url: 'controlador/notificacion.php', 
        method: 'GET',
        data: { accion: 'getNotifications' },
        dataType: 'json',
        success: function(data) {
			//console.log(data);
            const alertsDropdown = $('#alertsDropdown');
            const alertList = $('#contenido_notificaciones');
            alertList.empty(); // Clear previous alerts

            let alertCount = 0;
            data.forEach(notification => {
                alertCount++;

                const alertItem = $(`
                    <a class="dropdown-item d-flex align-items-center" href="?p=notificaciones" data-id="${notification.id}">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">${new Date(notification.fecha).toLocaleDateString()}</div>
                            <span class="font-weight-bold">${notification.mensaje}</span>
                        </div>
                    </a>
                `);

                alertItem.on('click', function() {
                    markAsRead($(this).data('id'));
                });

                alertList.append(alertItem);
            });

            const badgeCounter = alertsDropdown.find('.badge-counter');
            badgeCounter.text(alertCount > 3 ? '3+' : alertCount);
            if (alertCount === 0) {
                badgeCounter.hide();
            } else {
                badgeCounter.show();
            }
        },
        error: function(error) {
            console.error('Error fetching notifications:', error);
        }
    });
}

function markAsRead(id) {
    $.ajax({
        url: 'controlador/notificacion.php',
        method: 'POST',
        data: { accion: 'markAsRead', id: id },
        dataType: 'json',
        success: function(response) {
            if (response.resultado === 'exito') {
                fetchNotifications(); // Refresh notifications
            } else {
                console.error('Error marking notification as read:', response.mensaje);
            }
        },
        error: function(error) {
            console.error('Error marking notification as read:', error);
        }
    });
}
// Llamar a la función fetchNotifications cada 5 minutos



document.addEventListener("DOMContentLoaded", function(){

	fetchNotifications(); // Fetch notifications on page load
    setInterval(fetchNotifications, 60000); // Fetch notifications every 60 seconds

	if(true){
		if(document.querySelector("#page-top > #wrapper:first-child")){
			if(getCookie("modo_oscuro")){
				document.body.classList.add("dark-mode");
			}




			var darkmode_btn = crearElem("a","class,dropdown-item cursor-pointer,href,#,onclick,return false");
			darkmode_btn.innerHTML="<span>Modo Oscuro</span> <span class='bi dark-mode-btn-dropdown-icon'></span>"
			darkmode_btn.onclick=function(e){
				e.preventDefault();
				if(document.body.classList.contains("dark-mode")){
					document.body.classList.remove("dark-mode");
					document.cookie = "modo_oscuro=; expires=Thu, 01 Jan 1970 00:00:00 UTC"; // se elimina la cookie
				}
				else{
					document.body.classList.add("dark-mode");
					document.cookie = "modo_oscuro=dark-mode; expires=" + new Date(Date.now() + 365*24*60*60*1000).toUTCString();// en un año XD
				}

			}

			//document.body.appendChild(crearElem("div","class,darkmode_btn-container",darkmode_btn));

			drop_menu = document.querySelector("#userDropdown+div.dropdown-menu");


			$(drop_menu).prepend(darkmode_btn);
			// $(drop_menu).prepend('<div class="dropdown-divider"></div>');
			//drop_menu.appendChild(crearElem("div","class,dropdown-divider"));
			//drop_menu.appendChild(darkmode_btn);
		}

	}
	// TODO quitar esto;
});


function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

