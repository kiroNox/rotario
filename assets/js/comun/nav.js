$("#logout_btn").on("click", (e)=>{
	Swal.fire({
		title: "¿Estás Seguro?",
		text: "¿Está seguro que desea Salir?",
		showCancelButton: true,
		confirmButtonText: "Salir",
		confirmButtonColor: "#007bff", // amarillo #ffc107 rojo #dc3545 azul #007bff
		cancelButtonText: `Cancelar`,
		icon: "warning",
	}).then((result) => {
		if (result.isConfirmed) {
			
			location.href="?p=out";
	
		}
	});
});

$("#help-btn").on("click", (e)=>{
	console.log("queso");
	if(typeof Intro !== 'undefined'){
		Intro.setDontShowAgain(false);
		Intro.start();
	}
	else{
		introJs().setDontShowAgain(false);
		introJs().start();
	}
});