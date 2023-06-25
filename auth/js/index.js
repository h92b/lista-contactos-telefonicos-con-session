/**
 * javascript para todo lo que este dentro de la carpeta auth
*/


/**
 * Seleccion el Elemento html <a> con el id toggleForm
 * la @const toggleForm al ser diferente a @null inicializa
 * la @function functionToggleForm y una serie de @variables
 * necesarias para el correcto funcionamiento de la funcion
 */
const toggleForm = document.querySelector('#toggleForm');

/**
 * class para los titulos del formulario las propiedades son privadas
 */
class textForm {
    #action = ['login', 'signup'];
    #title = ['registro de usuarios', 'iniciar cuenta'];

    constructor () {
        this.#action = ['login', 'signup']
        this.#title = ['registro de usuarios', 'iniciar cuenta']
    }

    get action () {
        return this.#action
    }

    get title () {
        return this.#title
    }
};

/**
 * la @function functionToggleForm se encarga de cambiar los valores
 * para cambiar el comportamiendo del formulario incluyendo el titulo
 * del formulario.
 * @param {event} ev 
 */
const functionToggleForm = (ev) => {
    ev.preventDefault()

    if (ev.target.textContent === 'Registrar') {
        ev.target.textContent = 'Iniciar'
    } else {
        ev.target.textContent = 'Registrar'
    }

    let title = sing.previousElementSibling.textContent;
    let formAction = actions.value;

    /**
     * Esto funcion solo por que hay 2 valores en el array de class @classForm
     * en el caso de haber 3 indices me devolveria 2 indices. Ya que @filter lo estoy 
     * usando con el operador de desigualdad al ser un indice diferente al otro
     * retorna el diferente, por lo tanto, seria el opuesto al que tiene el formulario
     * por eso funciona, pero, en el caso de haber mas de 2 indices seria algo como
     * @filter ( a != [a,b,c,d] ).toString() retornaria 'b,c,d'
     */
    actions.value = classForm.action.filter(e=>e!=formAction).toString();
    sing.previousElementSibling.textContent = classForm.title.filter(e=>e!=title).toString();

};

/**
 * inicializa la @function functionToggleForm en el caso que @const toggleForm
 * sea diferente a @null mas 3 @variables una de ellas es la class @textForm
 * aparte arroja un mensaje por consola de informacion que el boton esta
 * disponible para fines educativos.
*/
if((null != toggleForm && (sing = document.querySelector('#sing'), actions = document.querySelector('#actions'), classForm = new textForm, toggleForm.addEventListener('click', functionToggleForm, false)), true)) {
    console.info(`el boton para el cambio del formulario esta disponible.`);
}

/* inicio de la validacion del formulario */

/**
 * @param {RegExp} regEx 
 * @param {string} value 
 * @returns boolean
 */
const regExp = (regEx, value) => (regEx.test(value)) ? true : false ;

/**
 * esta @function clearMsgInputs es la encargada de insertar 
 * los mensajes en mode de error o exitoso en el formulario 
 * login y signup tambien limpia el contenido obmitiendo el 
 * @param value 
 * @param {HTMLElement} msg 
 * @param {string} value 
 * @param {string} valid 
 */
const clearMsgInputs = (msg, value = '', valid = 'error') => {
    msg.parentElement.nextElementSibling.textContent = value;
    msg.parentElement.nextElementSibling.className = valid
}

/**
 * retorna @false en algunos casos en el caso de estar todo bien
 * redirige a otra @URL
 * @param {event} ev 
 * @returns false
 */
window.onsubmit = function (ev) {
    ev.preventDefault()

    if (ev.type !== 'submit') {
        console.info(`Function no disponible.`);
        return false;
    }

    let el = ev.target,
    
    element = Object.values(el).map( e => 
        (function(a,b){
            if(b !== '') {
                (a !== 'submit' && a !== 'hidden')&&(clearMsgInputs(e))
                return true
            }
            (a !== 'submit' && a !== 'hidden')&&(clearMsgInputs(e, 'No puede haber campos vacios.'))
            return false;
        }(e.type, e.value)) ? (function(a){
            return (
                (e.getAttribute('name') === 'email' && (
                    function(){
                        return regExp(/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i, a) ? (clearMsgInputs(e, 'El Email es valido.', 'success'), e.getAttribute('name')+'='+e.value) : (clearMsgInputs(e, 'No cumple con el estandar.'), false);
                    }()
                )) ||
                (e.getAttribute('name') === 'password' && (
                    function(){
                        return regExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&'\"\;\:\(\)\.\*\+\-\=])[A-Za-z\d@$!%*?#&'\"\;\:\(\)\.\*\+\-\=]{8,}$/, a) ? (clearMsgInputs(e, 'El Password es valido.', 'success'), e.getAttribute('name')+'='+e.value) : (clearMsgInputs(e, 'No cumple con el estandar.'), false);
                    }()
                )) ||
                (e.getAttribute('name') === 'csrf' && (
                    function(){
                        return regExp(/^([a-f 0-9]{32,32})+$/, a) ? e.getAttribute('name')+'='+e.value : false;
                    }()
                )) ||
                (e.getAttribute('name') === 'actions' && (
                    function(){
                        return regExp(/^([a-zA-Z])+$/, a) ? e.getAttribute('name')+'='+e.value : false;
                    }()
                ))
            )
        }(e.value)) : false
    );
    
    if( element.filter(e=>e!=false).length == 4) {
        clearMsgInputs(document.getElementById('email'))
        clearMsgInputs(document.getElementById('password'))
        let string = element.toString().replace(/,/g, '&');
        let url = new URL(el.action);
        let search = new URLSearchParams(string);
        window.location = url +'?'+ search;
    }

};