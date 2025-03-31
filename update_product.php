<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE Product SET 
        Lender=?, InterestRate=?, MortgageTerm=?, MinIncome=?, 
        MaxOutgoings=?, MinCreditScore=?, EmploymentType=?, 
        MonthlyRepayment=?, AmountPaidBack=? WHERE ProductId=?");

    $stmt->execute([
        $_POST['lender'],
        $_POST['rate'],
        $_POST['term'],
        $_POST['min_income'],
        $_POST['max_outgoings'],
        $_POST['credit_score'],
        $_POST['employment_type'],
        $_POST['repayment'],
        $_POST['paidback'],
        $id
    ]);

    $_SESSION['flash'] = "Product #$id updated successfully ✅";
    header("Location: product_list.php");
    exit();
} else {
    $_SESSION['flash'] = "Invalid request.";
    header("Location: product_list.php");
    exit();
}
