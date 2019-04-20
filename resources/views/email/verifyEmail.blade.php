<body>
	<h4>Confirmar correo</h4>
	<h3>Hola {{ $name }}, gracias por registrarte en <strong>TubeKids</strong> !</h3>
    <p>Por favor confirma tu correo electrónico.</p>
    <p>Para ello simplemente debes hacer click en el siguiente enlace:</p>
	<a href="{{ route('confirm.email', $token) }}">
		Click para confirmar tu email
	</a>
</body>