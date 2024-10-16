<?php
	session_start();
	
	// echo password_hash('', PASSWORD_BCRYPT);;

	// Database connection details
	require '../config/db.php';

	// Check if form is submitted
	if (isset($_GET["logout"]) && $_GET["logout"] == true) {
		session_unset();  // Clear session variables
		session_destroy(); // Destroy session
		header("Location: /");  // Redirect to login page if not authenticated
		exit;
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$login = $_POST['login'];
		$password = $_POST['password'];
		
		// Fetch user from the database
		$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
		$stmt->execute(['login' => $login]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($user) {
			// Verify password
			if (password_verify($password, $user['password'])) {

				$_SESSION['user_id'] = $user['id'];
				$_SESSION['login'] = $user['login'];
			} else {
				header("Location: /?error=connexionFail");  // Redirect to login page if not authenticated
				exit;
			}
		} else { 
			header("Location: /?error=connexionFail");  // Redirect to login page if not authenticated
			exit;
		}
	}
	
	if (!isset($_SESSION['user_id'])) {
		header("Location: /");  // Redirect to login page if not authenticated
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TEST</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

</head>
<body>
	<div id="header">
		<a href="#">Nous contacter</a>
		<?php if (isset($_SESSION['user_id'])) : ?>
			<a href="invoice-list?logout=true">
				<button type="button" class="btn btn-light mb-2 btn-admin" id="loginBtn">Déconnexion</button>
			</a>	
		<?php endif; ?> 
	</div>	

    <div class="list">
		 <a href="/"><img src="../images/header_logo.png" style="width:350px; height:70px" border="0" alt="dupuytren"></a>
		<div class="search">
			<h2 class="text-center">Liste des factures importées</h2>
			<hr>
			<!-- Product List Table -->
		<div class="table-responsive">
			<table  id="invoiceTable" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Numéro Facture</th>
						<th>Client</th>
						<th>Montant</th>
						<th>E-mail</th>
						<th>Adresse</th>
						<th>Ville</th>
						<th>Code Postal</th>
						<th>Date Facture</th>
						<th>Statut</th>
						<th>Date Paiement</th>
					</tr>
				</thead>
				<tbody>
					<?php	
						try {
							$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							// Fetch all records from the products table
							$sql = "SELECT * FROM invoices ORDER BY id";
							$stmt = $pdo->prepare($sql);
							$stmt->execute();
							
							// Fetch all results and display them in the table
							$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

							if ($products) {
								foreach ($products as $product) {
									echo "<tr>";
										echo "<td>" . htmlspecialchars($product['invoice_number']) . "</td>";
										echo "<td>" . htmlspecialchars($product['client_name']) . "</td>";
										echo "<td>" . htmlspecialchars($product['invoice_amount']) . "</td>";
										echo "<td>" . htmlspecialchars($product['client_email']) . "</td>";
										echo "<td>" . htmlspecialchars($product['client_address']) . "</td>";
										echo "<td>" . htmlspecialchars($product['client_city']) . "</td>";
										echo "<td>" . $product['client_postal_code'] . "</td>";
										echo "<td>" . $product['invoice_date'] . "</td>";
										echo "<td>" . ($product['invoice_status'] === 1 ? "<span style='color:green; font-weight:bold'>Payée</span>" : "<span style='color:red; font-weight:bold'>Non Payée</span>") . "</td>";
										echo "<td>" . htmlspecialchars($product['invoice_payment_date']) . "</td>";
									echo "</tr>";
								}
							} else {
								echo "<tr><td colspan='3' class='text-center'>No products found</td></tr>";
							}

						} catch (PDOException $e) {
							// Handle any errors
							echo "<tr><td colspan='3' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>	
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<!-- Initialize DataTables -->
<script>
$(document).ready(function() {
    $('#invoiceTable').DataTable({
        "order": [[0, "asc"]] // Order by the first column (ID) ascending
    });
});
</script>

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
		font-size: 10px;
		margin-top: 30px;
	}
	.table-responsive{
		overflow-x: visible; !important
	}
	.list {
		padding-right: 10px;
		padding-left: 10px;
	}
		.btn-admin {
		float: right;
		height: 30px;
		line-height: 15px;
		font-weight: bold;
		margin-right: 20px;
	}
</style>
</html>
