<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
	<div id="header">
		<a href="#">Nous contacter</a>

		<?php if (!isset($_SESSION['user_id'])) : ?>
			<button type="button" class="btn btn-light mb-2 btn-admin" id="loginBtn">Administration</button>
		<?php endif; ?> 	
		
		<?php if (isset($_SESSION['user_id'])) : ?>
			<a href="invoice-list?logout=true">
				<button type="button" class="btn btn-light mb-2 btn-admin" id="loginBtn">Déconnexion</button>
			</a>	
		<?php endif; ?> 	
		
	</div>
    <div class="container mt-5">
		<img src="/images/header_logo.png" style="width:350px; height:70px" border="0" alt="dupuytren">
			<?php if (isset($_GET["error"]) && $_GET["error"] === 'connexionFail') : ?>
				<br /><br />	
				<div class="alert alert-danger" role="alert">
					Identifiants incorrects. Veuillez vérifier votre identifiant et mot de passe.
				</div>
			<?php endif; ?> 
		<div class="search">
        <h2 class="text-center">Payez en toute sécurité et en 2 minutes votre facture</h2>
		<br />
        <form action="payment-invoice" method="POST">
            <div class="mb-3">
                <p>Veuillez saisir votre numéro de facture* puis valider : Si votre numéro de facture commence par des 0 veuillez les écrire</p>
                <input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" placeholder="Entrer le numéro de la facture" required>
				<p class="notice">* Ce numéro se trouve sur la facture qui vous a été transmise par nos services<p>
            </div>
			
			<div style="float:right">
				<button type="submit" class="btn btn-primary">Valider</button>
			</div>
        </form>
		</div>
    </div>
	
	<!-- Modal structure -->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="loginModalLabel">Accés à la liste des factures importées</h5>
				</div>
				<div class="modal-body">
					<form id="loginForm" action="invoice-list" method="POST">
						<div class="form-group">
							<input type="username" class="form-control" name="login" placeholder="Votre identifiant" required>
						</div>
						<br />
						<div class="form-group">
							<input type="password" class="form-control" name="password" placeholder="Votre mot de passe" required>
						</div>

						<button type="submit" class="btn btn-primary btn-login">Connexion</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
    <div class="row">
		<div class="col-md-8 offset-md-2 text-center">
			<img src="images/cb_3dsv2.png"><img src="images/mc_3dsv2.png"><img src="images/visa_3dsv2.png">
		</div>
    </div>
	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
$(document).ready(function() {
    // Open modal on button click
    $('#loginBtn').click(function() {
        $('#loginModal').modal('show');
    });
});
</script>

<style>
	#header {
		background: #9b999a;
		color: #ffffff;
		height: auto;
		margin-bottom: 20px;
		min-height: 20px;
		padding: 10px 0 10px 0;
		width: 100%;
	}
	#header a:link, #header a:visited {
		color: #ffffff;
		display: inline-blox;
		margin: 10px;
		text-decoration: none;
	}
	.notice {
		font-size: 10px;
		color: #ccc;
	}
	.search {
		border: #ccc 1px bold;
		margin-top: 30px;
		padding: 25px 50px 50px 50px;
		border: 1px solid #ccc;
		background: #f7f7f7;
	}
	.btn-admin {
		float: right;
		height: 30px;
		line-height: 15px;
		font-weight: bold;
		margin-right: 20px;
	}
	.btn-login {
		float: right;
		margin-top: 10px;
	}
</style>
</html>
