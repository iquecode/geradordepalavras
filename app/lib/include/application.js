loading = true;

Application = {};
Application.translation = {
    'en' : {
        'loading' : 'Loading'
    },
    'pt' : {
        'loading' : 'Carregando'
    },
    'es' : {
        'loading' : 'Cargando'
    }
};

Adianti.onClearDOM = function(){
	/* $(".select2-hidden-accessible").remove(); */
	$(".colorpicker-hidden").remove();
	$(".select2-display-none").remove();
	$(".tooltip.fade").remove();
	$(".select2-drop-mask").remove();
	/* $(".autocomplete-suggestions").remove(); */
	$(".datetimepicker").remove();
	$(".note-popover").remove();
	$(".dtp").remove();
	$("#window-resizer-tooltip").remove();
};


function showLoading() 
{ 
    if(loading)
    {
        __adianti_block_ui(Application.translation[Adianti.language]['loading']);
    }
}

Adianti.onBeforeLoad = function(url) 
{ 
    loading = true; 
    setTimeout(function(){showLoading()}, 400);
    if (url.indexOf('&static=1') == -1) {
        $("html, body").animate({ scrollTop: 0 }, "fast");
    }
};

Adianti.onAfterLoad = function(url, data)
{ 
    loading = false; 
    __adianti_unblock_ui( true );
    
    // Fill page tab title with breadcrumb
    // window.document.title  = $('#div_breadcrumbs').text();
};

// set select2 language
$.fn.select2.defaults.set('language', $.fn.select2.amd.require("select2/i18n/pt"));



//********* a partir daqui customisado por iquedev */

function maskPhone()
{

const masks = {
    cpf (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})/, '$1-$2')
        .replace(/(-\d{2})\d+?$/, '$1')
    },
  
    cnpj (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{2})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1/$2')
        .replace(/(\d{4})(\d)/, '$1-$2')
        .replace(/(-\d{2})\d+?$/, '$1')
    },
  
    phone (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{2})(\d)/, '($1) $2')
        .replace(/(\d{4})(\d)/, '$1-$2')
        .replace(/(\d{4})-(\d)(\d{4})/, '$1$2-$3')
        .replace(/(-\d{4})\d+?$/, '$1')
    },
  
    cep (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{5})(\d)/, '$1-$2')
        .replace(/(-\d{3})\d+?$/, '$1')
    },
  
    pis (value) {
      return value
        .replace(/\D+/g, '')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{5})(\d)/, '$1.$2')
        .replace(/(\d{5}\.)(\d{2})(\d)/, '$1$2-$3')
        .replace(/(-\d)\d+?$/, '$1')
    },
  
    money (value) {
      const cleanValue = +value.replace(/\D+/g, '')
      const options = { style: 'currency', currency: 'BRL' }
      return new Intl.NumberFormat('pt-br', options).format(cleanValue / 100)
    },
    
    name (value) {
      return value
        .replace(/[^a-zA-Z ]/g, "");
    }
    
  }
  
  document.querySelectorAll('input').forEach($input => {
    const field = $input.dataset.mask_ique
  
    $input.addEventListener('input', e => {
      e.target.value = masks[field](e.target.value)
    }, false)

  })
    
}



function autoCep()
{

    // $date_input = document.querySelectorAll('[name="date[]"]');
    // $type_input = document.querySelectorAll('[name="type[]"]');
    // $date_input[i].value = date;
    // $type_input[i].value = type;

  const cep = document.querySelector('[auto_cep="cep"]')

  const showData = (result)=>{
      for(const campo in result){
          if(document.querySelector(`[auto_cep="${campo}"]`)){
              document.querySelector(`[auto_cep="${campo}"]`).value = result[campo]
          }
      }
  }

  cep.addEventListener("blur",(e)=>{
      let search = cep.value
                      .replace("-","")
                      .replace(".","")
      const options = {
          method: 'GET',
          mode: 'cors',
          cache: 'default'
      }

      fetch(`https://viacep.com.br/ws/${search}/json/`, options)
      .then(response =>{ response.json()
          .then( data => showData(data))
      })
      .catch(e => console.log('Erro (autocep): '+ e,message))
  })
}


function includeNotificationMenu()
{
    const navMenu = document.querySelector('.iquedev-wrapper-info-header');
    const onclick = 'onclick="iquedevOpenModal(' + "'iquedev-modal-notification')" + '"';
                                                     

    //let content = '<div class="nav-item dropdown"><a id="iquedev-link-notification" class="dropdown-item"><span style="padding-left:4px">Avisos</span></a></div>';
    let content = '<div class="nav-item dropdown"><a id="iquedev-link-notification" class="dropdown-item"><span style="padding-left:4px" ' + onclick + '>Avisos</span></a></div>';
    navMenu.insertAdjacentHTML("afterbegin", content);
}




function iquedevOpenModal(mn){
  const modal = document.getElementById(mn);
  if (typeof modal == 'undefined' || modal === null)
      return;
 
  modal.style.display = 'Block';
  $("#html").addClass("on-hide-scroll"); 
}

function iquedevCloseModal(mn){
  const modal = document.getElementById(mn);
  if (typeof modal == 'undefined' || modal === null)
      return;
  modal.style.display = 'none';
  $("#html").removeClass("on-hide-scroll");
}