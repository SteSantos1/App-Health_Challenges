<script>
    const formLogin = document.getElementById('form-login');
    const campoEmail = document.getElementById('email');
    const campoSenha = document.getElementById('password');
    const botaoMostrar = document.getElementById('mostrar-senha');
    const textoErro = document.getElementById('erro-js');

    botaoMostrar.addEventListener('click', function() {
        if (campoSenha.type === 'password') {
            campoSenha.type = 'text';
            botaoMostrar.textContent = 'Esconder';
            botaoMostrar.setAttribute('aria-label', 'Esconder senha');
        } else {
            campoSenha.type = 'password';
            botaoMostrar.textContent = 'Mostrar';
            botaoMostrar.setAttribute('aria-label', 'Mostrar senha');
        }
    });

    formLogin.addEventListener('submit', function(event) {
        const email = campoEmail.value.trim();
        const senha = campoSenha.value;
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

        let mensagem = '';

        if (email === '' || senha === '') {
            mensagem = 'Preencha o e-mail e a senha.';
        } else if (!regexEmail.test(email)) {
            mensagem = 'Digite um e-mail válido.';
        }

        if (mensagem !== '') {
            event.preventDefault(); 
            textoErro.textContent = mensagem;
            textoErro.classList.remove('escondido');
        } else {
            textoErro.classList.add('escondido'); 
        }
    });
</script>
