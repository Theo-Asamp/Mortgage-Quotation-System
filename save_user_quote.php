<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    echo json_encode(['error' => 'Only regular logged-in users can save quotes.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $income = floatval($_POST['income'] ?? 0);
    $bonus = floatval($_POST['bonus'] ?? 0);
    $overtime = floatval($_POST['overtime'] ?? 0);
    $outcome = floatval($_POST['outcome'] ?? 0);
    $property = floatval($_POST['property'] ?? 0);
    $borrow = floatval($_POST['borrow'] ?? 0);
    $years = intval($_POST['years'] ?? 0);
    $months = intval($_POST['months'] ?? 0);

    $mortgageLength = ($years * 12) + $months;
    $interestAnnually = 0.03;
    $monthlyRate = $interestAnnually / 12;

    if ($monthlyRate == 0 || $mortgageLength == 0) {
        $monthlyRepayment = $borrow / ($mortgageLength ?: 1);
    } else {
        $monthlyRepayment = $borrow * $monthlyRate * pow(1 + $monthlyRate, $mortgageLength) / (pow(1 + $monthlyRate, $mortgageLength) - 1);
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO SavedQuotes (
                UserId, Income, Bonus, Overtime, Outgoings,
                PropertyValue, BorrowAmount, MortgageLength,
                InterestAnnually, MonthlyRepayment
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId, $income, $bonus, $overtime, $outcome,
            $property, $borrow, $mortgageLength,
            $interestAnnually, $monthlyRepayment
        ]);
        echo json_encode(['success' => true, 'message' => 'Quote saved successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
