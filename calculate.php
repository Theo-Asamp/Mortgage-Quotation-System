<?php
require 'db.php';
$income = $_POST['income'];
$outgoings = $_POST['outgoings'];
$creditScore = $_POST['credit_score'];
$employment = $_POST['employment_type'];
$sql = "SELECT * FROM Product WHERE 
    MinIncome <= ? AND 
    MaxOutgoings >= ? AND 
    MinCreditScore <= ? AND 
    (EmploymentType = ? OR EmploymentType = 'any')";
$stmt = $conn->prepare($sql);
$stmt->execute([$income, $outgoings, $creditScore, $employment]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($matches as $match) {
    echo '<div>';
    echo '<strong>Lender:</strong> ' . htmlspecialchars($match['Lender']) . '<br>';
    echo '<strong>Rate:</strong> ' . $match['InterestRate'] . '%<br>';
    echo '<strong>Term:</strong> ' . $match['MortgageTerm'] . ' years<br>';
    echo '<strong>Repayment:</strong> £' . $match['MonthlyRepayment'];
    echo '</div><hr>';
}
?>