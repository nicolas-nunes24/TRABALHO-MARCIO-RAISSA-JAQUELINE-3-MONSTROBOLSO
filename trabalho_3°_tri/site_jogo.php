<!DOCTYPE html>
<html lang="en">
<head>
    <!--<style link="jogo.css"></style>-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forçamon</title>
</head>
<body>
 
<fieldset>
    <legend><h2>FORÇAMON</h2></legend>
    <button type="button" onclick="jogar()">Jogar</button>

    <h1>Monte seu time</h1>
    <button type="button" onclick="TrocaTela()">Time</button>

    <h1>Lojinha</h1>
    <p>ITENS DA LOJA AQUI</p>
</fieldset>

<div id="time">

</div>

<script>
 function jogar(){
    window.location.href = "jogo.php";
}

function TrocaTela(){
    window.location.href = "MonteTime.html";

}
</script>
</body>
</html>