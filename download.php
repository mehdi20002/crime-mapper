<?php
require 'tools/config.php';
require('fpdf/fpdf.php');  // Assurez-vous que le chemin vers fpdf est correct

if (isset($_GET['id'])) {
    $citizenId = $_GET['id'];



    // Récupérer les informations du citoyen
    $sql = "SELECT nom, prenom, cin, created_at, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $citizenId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Créer le PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Informations du citoyen');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        foreach ($row as $key => $value) {
            $pdf->Cell(40, 10, ucfirst($key) . ': ' . $value);
            $pdf->Ln(8);
        }

        $pdf->Output('D', 'citizen_' . $citizenId . '.pdf');
    } else {
        echo "Aucune information trouvée pour ce citoyen.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID du citoyen manquant.";
}
?>
