/**
 * javascript para todo lo que este dentro de la carpeta dashboard
*/

const ampliar = document.querySelectorAll('.ampliar');
const nameEdit = document.querySelector('#nameEdit');
const phoneEdit = document.querySelector('#phoneEdit');
const id = document.querySelector('#id');
const modal = document.querySelector('.modal');
const btnEdit = document.querySelector('#edit');
const close = document.querySelector('#close');

const windowAmpliar = (ev) => {
    modal.classList.add('active')
    let href = ev.target.getAttribute('href').replace(/(#actions=\[)/i, '');
    let href_array = href.replace(/\]$/, '').split(';');
    if (href_array.length === 4) {
        nameEdit.value = href_array[1]
        phoneEdit.value = href_array[2]
        id.value = href_array[3]
    }
    btnEdit.setAttribute('type', 'submit')
    close.addEventListener('click', ()=> {
        btnEdit.setAttribute('type', 'button')
        modal.classList.remove('active')
        document.querySelector('#formEdit').reset();
    })
};

[... ampliar.values()].map(e=>e.addEventListener('click', windowAmpliar, false)) ;