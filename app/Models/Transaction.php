<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Transaction extends Model
{
    use HasFactory;

    public function __construct($data = null)
    {
        if ($data) {
            $this->date = $data[0];
            $this->type = $data[1];
            $this->quantity = (int)$data[2];
            if (isset($data[3])) {
                $this->price = (float)$data[3];
            }
        }
    }
    
    /**
     * Checks if row is valid transaction object.
     * Required Fields:
     * [0] => Date, [1] => Type, [2] => Quantity
     *
     * @return Boolean
     */
    public static function isValid($transaction) {
        return (
            isset($transaction[0]) &&
            isset($transaction[1]) && in_array($transaction[1], ['Purchase', 'Application']) &&
            isset($transaction[2]) && is_numeric($transaction[2]));
    }

    /**
     * Returns all transaction data from data source (csv file)
     *
     * @return Array[App\Models\Transaction]
     */
    public static function findAllTransactions() {
        $transactions = [];
        $file = fopen($_SERVER['DOCUMENT_ROOT']."/../data/Fertiliser inventory movements - Sheet1 (1) (1) (2) (1) (2) (1) (1).csv","r");
        while(! feof($file)) {
            $row = fgetcsv($file);
            if (Transaction::isValid($row)) {
                $transaction = new Transaction($row);
                $transactions[] = $transaction;
            }
        }
        fclose($file);

        return $transactions;
    }
}
