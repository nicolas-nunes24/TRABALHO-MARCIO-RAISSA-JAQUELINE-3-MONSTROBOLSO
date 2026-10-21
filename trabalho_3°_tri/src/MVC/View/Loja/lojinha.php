<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <div class="itemLoja">

        <img src="pokebola2.png" alt="brrbrrpatana">
        <button type="button" onclick="comprarItem(1000)">Buy</button>
   
    </div>


    <script>
        let saldoMoeda = <?php echo (int)$moeda; ?>;
    
        function comprarItem(valorItem){
            if(saldoMoeda < valorItem){
               return alert("<span>Você não tem saldo!</span>");
                
            }
            saldoMoeda -= valorItem;
            return alert ("<span>Sucesso! Item adicionado a sua mochila!</span>");
        }
    </script>
    
</body>
</html>