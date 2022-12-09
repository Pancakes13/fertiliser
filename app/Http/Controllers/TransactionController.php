<?php

namespace App\Http\Controllers;

use App\MyClasses\Stock;
use App\Models\Transaction;
use Exception;

class TransactionController extends Controller
{
    /**
     * Initializes Stock queue.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $stock = new Stock();
            $transactions = $this->getAllTransactions($stock);
            $stock->transactions = unserialize(serialize($transactions));
            $stock->initializeStock();
            return view('inventory', [
                'transactions' => $transactions,
                'stockItems' => $stock->getStockOnHand()
            ]);
        } catch (Exception $error) {
            return $error;
            return view('inventory', [
                'transactions' => $transactions,
                'stockItems' => $stock->getStockOnHand(),
                'error' => $error->getMessage()
            ]);
        }
    }

    /**
     * Get all transaction data from source.
     *
     * @return Array[App\Models\Transaction;]
     */
    public function getAllTransactions()
    {
        return Transaction::findAllTransactions();
    }

    /**
     * Get all transaction data from source.
     *
     * @return Array[App\Models\Transaction;]
     */
    public function getStockApplied($quantity)
    {
        try {
            if (!is_numeric($quantity) || $quantity <= 0) {
                throw new Exception();
            }
            $stock = new Stock();
            $transactions = $this->getAllTransactions($stock);
            $stock->transactions = unserialize(serialize($transactions));
            $stock->initializeStock();
            
            $transaction = new Transaction([date('d/m/Y'), 'Application', $quantity, '']);
            $applied = $stock->removeStock($transaction);
            $transactions = array_merge($transactions, $applied);
            return view('inventory', [
                'transactions' => $transactions,
                'stockItems' => $stock->getStockOnHand(),
                'applied' => $applied,
                'total_valuation' => Stock::getAppliedValuation($applied)
            ]);
        } catch (Exception $error) {
            return view('inventory', [
                'transactions' => $transactions,
                'stockItems' => $stock->getStockOnHand(),
                'error' => $error->getMessage()
            ]);
        }

    }
}
