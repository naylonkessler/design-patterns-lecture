<?php

abstract class CsvReportTemplate
{
    public function generateReport(): void
    {
        $data = $this->fetchData();
        $formattedData = $this->formatData($data);
        $this->displayReport($formattedData);
    }

    abstract protected function fetchData(): array;

    protected function formatData(array $data): string
    {
        $header = implode(',', array_map('ucfirst', array_keys($data[0]))) . PHP_EOL;

        return array_reduce(
            $data,
            fn ($carry, $one) => $carry . '"' . implode('","', $one) . '"' . PHP_EOL,
            $header
        );
    }

    abstract protected function displayReport(string $formattedData): void;
}

class SalesReport extends CsvReportTemplate
{
    protected function fetchData(): array
    {
        return [
            ['product' => 'Desktop Gamer Alienware Aurora', 'value' => 2399.96],
            ['product' => 'Monitor Gamer Samsung Odyssey 49 QLED', 'value' => 1063.50],
            ['product' => 'Joystick Thrustmaster Hotas Warthog', 'value' => 679.98],
            ['product' => 'Projector BenQ TH585P, 3800 Full-HD', 'value' => 999],
        ];
    }

    protected function displayReport(string $formattedData): void
    {
        echo 'Sales Report in CSV' . PHP_EOL;
        echo '--------------------------------------' . PHP_EOL;
        echo $formattedData;
    }
}

class ExpenseReport extends CsvReportTemplate
{
    protected function fetchData(): array
    {
        return [
            ['item' => 'Location Rental', 'value' => -560.35],
            ['item' => 'Payroll', 'value' => -980.00],
            ['item' => 'Products for sale', 'value' => -2020.34],
        ];
    }

    protected function displayReport(string $formattedData): void
    {
        echo 'Expense Report in CSV' . PHP_EOL;
        echo '--------------------------------------' . PHP_EOL;
        echo $formattedData;
    }
}

echo PHP_EOL;
$salesReport = new SalesReport();
$salesReport->generateReport();
echo PHP_EOL;

$expenseReport = new ExpenseReport();
$expenseReport->generateReport();
echo PHP_EOL;
