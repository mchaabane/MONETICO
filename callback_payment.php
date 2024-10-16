
<?php
header('Content-Type: text/plain');
require 'config/db.php';

$invoiceNumber = $_POST['reference'] ?? '';
$codeRetour = $_POST['code-retour'] ?? '';

if ($invoiceNumber) {

    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE invoice_number = :invoice_number");
    $stmt->execute(['invoice_number' => $invoiceNumber]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($invoice && $codeRetour === "payetest") {
		// Préparer la requête SQL d'UPDATE
		$sql = "UPDATE invoices SET invoice_status = :invoice_status, invoice_payment_date = :invoice_payment_date WHERE id = :id";

		// Préparer la requête
		$stmt = $pdo->prepare($sql);
		$invoiceId = $invoice["id"];
		$dateNow = date('Y-m-d H:i:s');
		$payedStatus = 1;

		// Associer les paramètres
		$stmt->bindParam(':invoice_status', $payedStatus);
		$stmt->bindParam(':id', $invoiceId);
		$stmt->bindParam(':invoice_payment_date', $dateNow);
		

		// Exécuter la requête
		if ($stmt->execute()) {
			// Version 2 correspond au protocole utilisé
			// cdr=0 signifie que l'accusé de réception a été correctement reçu sans erreur.
			echo "version=2\ncdr=0";

			// Fin de script pour éviter tout autre affichage
			exit;
		}
	}
}

// Version 2 correspond au protocole utilisé
// cdr=1 signifie que l'accusé de réception a été reçu avec erreur.
echo "version=2\ncdr=1";

// Fin de script pour éviter tout autre affichage
exit;
?>