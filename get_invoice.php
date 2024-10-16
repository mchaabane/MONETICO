<?php

require 'config/db.php';
require 'config/monetico.php';

$invoiceNumber = $_POST['invoiceNumber'] ?? '';

if ($invoiceNumber) {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE invoice_number = :invoice_number");
    $stmt->execute(['invoice_number' => $invoiceNumber]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if (!empty($invoice) && $invoice["invoice_status"] == 0){
		
		$TPE = MONETICO_TPE;
		// Client Infos
		$clientName = $invoice["client_name"]; // Example Client Name
		$mail = $invoice["client_email"];
		// Prepare form data
		$montant= number_format($invoice['invoice_amount'], 2) . "EUR"; // Amount without decimals (e.g., 15050 for 150.50 EUR)
		$lgue = MONETICO_LANG; // Language code (FR = French)
		$version = MONETICO_VERSION; // Protocol version

		$commandeInfos = [
			"billing" => [
				"addressLine1" => $invoice["client_name"],
				"city" => $invoice["client_city"],
				"postalCode" => $invoice["client_postal_code"],
				"email" => $invoice["client_email"],
				"country" => MONETICO_LANG
				]
		];
		$contexte_commande = base64_encode(utf8_encode(json_encode($commandeInfos)));	
		$date = date('d/m/Y:H:i:s');
		$reference = $invoiceNumber;
		$societe = MONETICO_SOCIETE;
		$texte_libre = MONETICO_TEXT;
		$url_retour_err = MONETICO_URL_RETOUR_ERR . "?invoiceNumber=" . $invoiceNumber;
		$url_retour_ok = MONETICO_URL_RETOUR_OK . "?invoiceNumber=" . $invoiceNumber;


		// Generate the MAC (Message Authentication Code)
		$texteMAC = "TPE=" . $TPE . "*contexte_commande=" .$contexte_commande .	"*date=" .$date . "*lgue=" .$lgue . "*mail=" .$mail . "*montant=" .$montant . "*reference=" .$reference. "*societe=" .$societe . "*texte-libre=" .$texte_libre . "*url_retour_err=" .$url_retour_err . "*url_retour_ok=" .$url_retour_ok  . "*version=" .$version;

		$MAC = hash_hmac('sha1', $texteMAC, hex2bin(MONETICO_KEY));
	}
}
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
			<h2 class="text-center">Détails de la Facture</h2>

			<?php if (!empty($invoice)): ?>
				<?php if ($invoice["invoice_status"] == 1): ?>
					<div class="alert alert-danger" role="alert">
						Votre facture a déjà été payée le <?php echo $invoice["invoice_payment_date"]; ?>
					</div>
				<?php endif; ?> 
				
				<div class="invoiceStyle">
					<p><strong>Numéro de Facture</strong> : <?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
					<p><strong>Client</strong> : <?php echo htmlspecialchars($invoice['client_name']); ?></p>
					<p><strong>Montant à Payer</strong> : <?php echo htmlspecialchars(number_format($invoice['invoice_amount'], 2)); ?> €</p>
					<p><strong>Contact E-mail</strong> : <?php echo htmlspecialchars($invoice['client_email']); ?></p>
				</div>
				
				<?php if ($invoice["invoice_status"] == 0): ?>
					<form method="POST" action="<?php echo MONETICO_PAYMENT_URL; ?>">
						<input type="hidden" name="TPE" value="<?php echo $TPE; ?>">
						<input type="hidden" name="date" value="<?php echo $date; ?>">
						<input type="hidden" name="contexte_commande" value="<?php echo $contexte_commande; ?>">
						<input type="hidden" name="lgue" value="<?php echo $lgue; ?>">
						<input type="hidden" name="mail" value="<?php echo $mail; ?>">
						<input type="hidden" name="montant" value="<?php echo $montant; ?>">					
						<input type="hidden" name="reference" value="<?php echo $reference; ?>">
						<input type="hidden" name="societe" value="<?php echo $societe; ?>">
						<input type="hidden" name="texte-libre" value="<?php echo $texte_libre; ?>">
						<input type="hidden" name="version" value="<?php echo $version; ?>">
						<input type="hidden" name="url_retour_ok" value="<?php echo $url_retour_ok; ?>">
						<input type="hidden" name="url_retour_err" value="<?php echo $url_retour_err; ?>">
						<input type="hidden" name="MAC" value="<?php echo $MAC; ?>">
						
						<div style="text-align:right; margin-top:15px">
							<a href="/" class="btn btn-secondary">Annuler</a>
							<button type="submit" class="btn btn-primary">Payer votre facture</button>
						</div>
					</form>
				<?php else: ?>
					<div style="text-align:right;">
						<a href="/" class="btn btn-secondary mt-3">Retour à la recherche</a>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<div class="alert alert-danger" role="alert">
					Facture non trouvée. Merci de <a href = "/">recommencer</a>.
				</div>
				<div style="text-align:right">
					<a href="/" class="btn btn-secondary mt-3">Retour à la recherche</a>
				</div>
			<?php endif; ?>  
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
		padding: 25px 50px 5px 50px;
		border: 1px solid #ccc;
		background: #f7f7f7;
	}
	.invoiceStyle {
		background: #fff;
		border: #ccc 1px solid;
		padding: 10px;
		padding-left: 30px;
		margin-top: 20px;
		margin-bottom: 20px;
	}
</style>
</html>
