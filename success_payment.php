<?php
	$invoiceNumber = $_GET['invoiceNumber'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div id="header">
		<a href="#">Nous contacter</a>
	</div>
    <div class="container mt-5">
		<img src="/images/header_logo.png" style="width:350px; height:70px" border="0" alt="dupuytren">
		
		<div class="search">
			<h2 class="text-center">Paiement effectué</h2>
			<div class="mb-3" style="color: green; margin-top:40px">
				<p>Votre facture (<?php echo $invoiceNumber; ?>) a bien été payée ! <a href="/">Payer une autre facture</a></p>
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
		/* width: 50%; */
		border: 1px solid green;
		background: #f7f7f7;
		min-height: 280px;
	}
</style>
</html>
