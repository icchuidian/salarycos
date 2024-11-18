<?php
// Function to calculate weekdays in a given month and year
function getWeekdaysInMonth($month, $year) {
    $firstDay = new DateTime("$year-$month-01");
    $lastDay = new DateTime("$year-$month-01");
    $lastDay->modify('last day of this month');

    $weekdayCount = 0;

    while ($firstDay <= $lastDay) {
        if ($firstDay->format('N') < 6) { // 1=Monday, 5=Friday
            $weekdayCount++;
        }
        $firstDay->modify('+1 day');
    }

    return $weekdayCount;
}

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Get previous month and year
$previousMonth = date('m', strtotime('-1 month'));
$previousYear = date('Y', strtotime('-1 month'));

// Get next month and year
$nextMonth = date('m', strtotime('+1 month'));
$nextYear = date('Y', strtotime('+1 month'));

// Calculate weekdays for current, previous, and next months
$weekdays_in_month = getWeekdaysInMonth($currentMonth, $currentYear);
$weekdays_in_previous_month = getWeekdaysInMonth($previousMonth, $previousYear);
$weekdays_in_next_month = getWeekdaysInMonth($nextMonth, $nextYear);

// Monthly salary
$monthly_salary = 27000;

// Calculate daily wage
$daily_wage_current_month = $monthly_salary / $weekdays_in_month;
$daily_wage_previous_month = $monthly_salary / $weekdays_in_previous_month;
$daily_wage_next_month = $monthly_salary / $weekdays_in_next_month;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Salary Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        label {
            font-weight: bold;
        }
        input {
            margin: 5px 0;
            padding: 8px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            margin: 10px 0;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
    <script>
        function calculateSalary() {
            var daysWorkedPreviousMonth = document.getElementById("days_worked_previous_month").value;
            var daysWorkedCurrentMonth = document.getElementById("days_worked_current_month").value;
            var daysWorkedNextMonth = document.getElementById("days_worked_next_month").value;
            var customTaxPercentage = document.getElementById("custom_tax_percentage").value;

            daysWorkedPreviousMonth = daysWorkedPreviousMonth ? parseInt(daysWorkedPreviousMonth) : 0;
            daysWorkedCurrentMonth = daysWorkedCurrentMonth ? parseInt(daysWorkedCurrentMonth) : 0;
            daysWorkedNextMonth = daysWorkedNextMonth ? parseInt(daysWorkedNextMonth) : 0;
            customTaxPercentage = customTaxPercentage ? parseFloat(customTaxPercentage) : 0;

            var weekdaysInPreviousMonth = <?php echo $weekdays_in_previous_month; ?>;
            var weekdaysInMonth = <?php echo $weekdays_in_month; ?>;
            var weekdaysInNextMonth = <?php echo $weekdays_in_next_month; ?>;
            var dailyWagePreviousMonth = <?php echo $daily_wage_previous_month; ?>;
            var dailyWageCurrentMonth = <?php echo $daily_wage_current_month; ?>;
            var dailyWageNextMonth = <?php echo $daily_wage_next_month; ?>;

            if (daysWorkedPreviousMonth < 0 || daysWorkedCurrentMonth < 0 || daysWorkedNextMonth < 0 || customTaxPercentage < 0) {
                document.getElementById("salary_result").innerHTML = "Please enter valid numbers.";
                return;
            }

            var salaryPreviousMonth = dailyWagePreviousMonth * daysWorkedPreviousMonth;
            var salaryCurrentMonth = dailyWageCurrentMonth * daysWorkedCurrentMonth;
            var salaryNextMonth = dailyWageNextMonth * daysWorkedNextMonth;

            var totalSalary = salaryPreviousMonth + salaryCurrentMonth + salaryNextMonth;

            var taxAmount = (customTaxPercentage / 100) * totalSalary;
            var netSalary = totalSalary - taxAmount;

            var result = "";

            if (daysWorkedPreviousMonth > 0) {
                result += "Salary for " + daysWorkedPreviousMonth + " days worked in the previous month: PHP " + salaryPreviousMonth.toFixed(2) + "<br>";
            }

            result += "Salary for " + daysWorkedCurrentMonth + " days worked in the current month: PHP " + salaryCurrentMonth.toFixed(2) + "<br>";

            if (daysWorkedNextMonth > 0) {
                result += "Salary for " + daysWorkedNextMonth + " days worked in the next month: PHP " + salaryNextMonth.toFixed(2) + "<br>";
            }

            result += "Total Salary: PHP " + totalSalary.toFixed(2) + "<br>";
            result += "Tax Deducted (" + customTaxPercentage + "%): PHP " + taxAmount.toFixed(2) + "<br>";
            result += "Net Salary: PHP " + netSalary.toFixed(2);

            document.getElementById("salary_result").innerHTML = result;
        }
    </script>
</head>
<body>
    <h2>Employee Salary Calculator</h2>
    <p>Previous Month (Weekdays): <?php echo $weekdays_in_previous_month; ?></p>
    <p>Current Month (Weekdays): <?php echo $weekdays_in_month; ?></p>
    <p>Next Month (Weekdays): <?php echo $weekdays_in_next_month; ?></p>

    <label for="days_worked_previous_month">Enter number of days worked in previous month:</label>
    <input type="number" id="days_worked_previous_month" min="0" max="<?php echo $weekdays_in_previous_month; ?>">
        <br>
    <label for="days_worked_current_month">Enter number of days worked in current month:</label>
    <input type="number" id="days_worked_current_month" min="0" max="<?php echo $weekdays_in_month; ?>">
        <br>
    <label for="days_worked_next_month">Enter number of days worked in next month:</label>
    <input type="number" id="days_worked_next_month" min="0" max="<?php echo $weekdays_in_next_month; ?>">
        <br>
    <label for="custom_tax_percentage">Enter custom tax percentage:</label>
    <input type="number" id="custom_tax_percentage" min="0" step="0.01" value="2.00">
        <br>
    <button onclick="calculateSalary()">Calculate Salary</button>

    <div id="salary_result" class="result"></div>
</body>
</html>
