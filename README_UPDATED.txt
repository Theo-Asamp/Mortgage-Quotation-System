UPDATED FILES FOR MORTGAGE QUOTATION SYSTEM

1. guest_quote.php
   - Allows unregistered users to estimate borrowing.
   - Place it in the root folder and link it from index.html.

2. compare.php
   - Displays up to 3 selected mortgage quotes side-by-side.
   - Make sure to link from the quote list page with ?ids=1,2,3 style.

3. calculate.php
   - Updated logic to filter products based on income, outgoings,
     credit score, and employment type.
   - Replace your current calculate.php with this one.

4. Database Update
   - The Product table schema has been updated to include:
     Lender, InterestRate, MortgageTerm, MinIncome, MaxOutgoings,
     MinCreditScore, EmploymentType
