<!DOCTYPE html>
<html>
<head>
    <title>Confirmação de Compra de Ingresso</title>
</head>
<body>
<p>Caro(a) {{ $client->first_name }},</p>

<p>Queremos expressar nossa gratidão pela sua compra e por escolher nosso evento de stand-up para o seu entretenimento!
    Estamos ansiosos para compartilhar uma noite de risadas e diversão com você.</p>

<h3>Detalhes do Evento:</h3>
<ul>
    <li>Data: 28 de Dezembro de 2023</li>
    <li>Horário: {{ $time }}</li>
    <li>Local: Night Cafe Komaki</li>
    <li>Mapa: <a href="https://maps.app.goo.gl/b2FPXCcN7xBTw91x5">https://maps.app.goo.gl/b2FPXCcN7xBTw91x5</a></li>
</ul>

<p>Seu Ingresso:</p>

@foreach($qrCodePaths as $qrCodePath)

    <img src="{{ asset($qrCodePath) }}" alt="QR Code">

@endforeach


<p>Lembre-se de trazer este ingresso, impresso ou digital, no dia do evento para garantir sua entrada. Chegue com
    antecedência para aproveitar ao máximo a experiência.</p>

<p>Caso tenha alguma dúvida ou necessite de assistência adicional, não hesite em nos contatar pelo telefone +81
    80-5811-8528.</p>

<p>Aguardamos ansiosamente por sua presença e prometemos que será uma noite inesquecível!</p>

<p>Atenciosamente,</p>

<p>Lt Corporation</p>

<p><em>Este e-mail foi enviado automaticamente. Por favor, não responda.</em></p>
</body>
</html>
