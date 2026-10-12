<script>
    function avancarPasso() {
        document.getElementById('passo1').style.display = 'none';
        document.getElementById('passo2').style.display = 'block';
        document.getElementById('barra-progresso').style.display = '100%';
    }

    function voltarPasso() {
        document.getElementById('passo1').style.display = 'block';
        document.getElementsById('passo2').style.display = 'none';
        document.getElementById('barra-progresso').style.display = '50%';
    }
</script>