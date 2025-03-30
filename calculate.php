<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST values from form
    $income = floatval($_POST['income']);
    $bonus = floatval($_POST['bonus']);
    $overtime = floatval($_POST['overtime']);
    $outcome = floatval($_POST['outcome']);
    $propertyValue = floatval($_POST['property']);
    $borrowAmount = floatval($_POST['borrow']);
    $years = intval($_POST['years']);
    $months = intval($_POST['months']);

    // Validate inputs
    if ($income <= 0 || $borrowAmount <= 0 || $propertyValue <= 0 || ($years === 0 && $months === 0)) {
        echo json_encode(['error' => 'Please fill out all required fields correctly.']);
        exit;
    }

    // Total income calculation
    $totalIncome = $income + $bonus + $overtime;

    // Loan duration in months
    $loanDuration = ($years * 12) + $months;

    // Basic mortgage formula (3% fixed interest)
    $interestRate = 0.03 / 12;
    $monthlyPayment = ($borrowAmount * $interestRate) / (1 - pow(1 + $interestRate, -$loanDuration));

    // Return result
    $result = [
        'total_income' => $totalIncome,
        'loan_duration' => "{$years} years and {$months} months",
        'monthly_payment' => number_format($monthlyPayment, 2),
    ];

    echo json_encode($result);
    exit;
}
?>
