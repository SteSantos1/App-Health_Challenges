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

    async function avancarPasso() {
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

        
        const status = document.getElementById('email-status');
        const dados = new FormData();
        dados.append('email', inputEmail.value.trim());
        
        status.textContent = 'Verificando e-mail...';

        try {
            const resposta = await fetch('../php/verificar_email.php', {
            method: 'POST',
            body: dados,
            headers: { 'Accept': 'application/json' }
        });
        
        if (!resposta.ok) {
            throw new Error('Falha ao verificar o e-mail');
        }
        
        const resultado = await resposta.json();
        status.textContent = resultado.mensagem;
        
        if (!resultado.disponivel) {
            return;
        }
        } catch (erro) {
            status.textContent = 'Não foi possível verificar o e-mail. Tente novamente.';
            return;
        }
        
        if (tudoCerto) {
            document.getElementById('passo1').style.display = 'none';
            document.getElementById('passo2').style.display = 'block';
            document.getElementById('barra-progresso').style.width = '100%';
            document.querySelector('#passo2 h1').focus();
        }
}


    function voltarPasso() {
        document.getElementById('passo1').style.display = 'block';
        document.getElementById('passo2').style.display = 'none';
        document.getElementById('barra-progresso').style.width = '50%';
        document.querySelector('#passo1 h1').focus();
    }
</script>