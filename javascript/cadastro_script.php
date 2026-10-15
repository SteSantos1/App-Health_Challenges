<script>
    const formulario = document.querySelector('form');
    const inputName = document.getElementById('name');

    formulario.addEventListener('submit', function(event) {
        if (inputName.value.trim() === '') {
            event.preventDefault();
            alert('Por favor, preencha o seu nome completo antes de continuar.');
        }
    });

    const inputPassword = document.getElementById('password');
    const inputConfirm = document.getElementById('confirm-password');

    inputConfirm.addEventListener('input', function() {
        if (inputPassword.value !== inputConfirm.value) {
            inputConfirm.setCustomValidity('As senhas não coincidem.');
        } else {
            inputConfirm.setCustomValidity('');
        }
    });

    const inputEmail = document.getElementById('email');

    inputEmail.addEventListener('input', function() {
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regexEmail.test(inputEmail.value)) {
            inputEmail.setCustomValidity('Por favor, introduza um formato de e-mail válido.');
        } else {
            inputEmail.setCustomValidity('');
        }
    });

    const inputAltura = document.getElementById('height');
    const inputPeso = document.getElementById('weight');

    inputAltura.addEventListener('input', function() {
    let valor = inputAltura.value.replace(/[^\d.,]/g, '');

    const regexDuploSeparador = /([.,].*)[.,]/;
    if (regexDuploSeparador.test(valor)) {
        valor = valor.replace(regexDuploSeparador, '$1');
    }
    inputAltura.value = valor;

    
    if (!valor.includes('.') && valor.length >= 3 && !valor.includes(',')){
        inputAltura.setCustomValidity('Digite sua altura em metros exemplo: 1.75');
    } else {
        inputAltura.setCustomValidity('');
    }
});


    inputPeso.addEventListener('input', function() {
        let valor = inputPeso.value.replace(/[^\d.,]/g, '');

        const regexDuploSeparador = /([.,].*)[.,]/;
        if (regexDuploSeparador.test(valor)) {
            valor = valor.replace(regexDuploSeparador, '$1');
        }

        inputPeso.value = valor;
    });

    function avancarPasso() {
        const inputsPasso1 = document.querySelectorAll('#passo1 input');
        let tudoCerto = true;

        for (let i = 0; i < inputsPasso1.length; i++) {
            let input = inputsPasso1[i];

            if (!input.checkValidity()) {
                input.reportValidity();
                tudoCerto = false;
                break;
            }
        }

        if (tudoCerto) {
            document.getElementById('passo1').style.display = 'none';
            document.getElementById('passo2').style.display = 'block';
            document.getElementById('barra-progresso').style.width = '100%';
        }
    }

    function voltarPasso() {
        document.getElementById('passo1').style.display = 'block';
        document.getElementById('passo2').style.display = 'none';
        document.getElementById('barra-progresso').style.width = '50%';
    }
</script>