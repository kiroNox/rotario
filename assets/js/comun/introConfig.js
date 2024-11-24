const Intro = introJs();
Intro.setOption("dontShowAgain",true);
Intro.setOption("prevLabel","Atrás");
Intro.setOption("doneLabel","Fin");
Intro.setOption("nextLabel","Siguiente");

// para copiar

// <script src="vendor/intro.js-7.2.0/package/minified/intro.min.js"></script>
// <script src="assets/js/comun/introConfig.js"></script>

// Intro.setOption("disableInteraction",true);
// Intro.setOption("buttonClass","hide-prevButtom introjs-button");
// Intro.onexit(()=>{$("#modal_registrar_nivel_educativo").modal("hide");})
// console.log(Intro,"intro");
// Intro.onbeforechange(async (elem)=>{
// 	if(elem){
// 		if(elem.dataset.step==3){
// 			$("#modal_registrar_nivel_educativo").modal("show");
// 				await new Promise(resolve => setTimeout(resolve, 400));

// 		}
// 		else if(elem.dataset.step==4){
// 			$("#modal_registrar_nivel_educativo").modal("hide");
// 				await new Promise(resolve => setTimeout(resolve, 400));
// 		}
// 	}
// })
// Intro.start();