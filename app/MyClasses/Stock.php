<?php

namespace App\MyClasses;
use Exception;
use App\Models\Transaction;

/**
 * Class for the queue of inventory stocks
 *
 * @return Boolean
 */
class Stock 
{
    // Front - Represents front unused stocks on hand, the first stock items to be used on application (Removal of stocks)
    public $front;
    
    // End - Last item of unused stock items
    public $end;

    // Transactions - An array of all transactions performed on the stocks on hand
    public $transactions = array();

    // Stock - An array of unused stocks on hand
    public $stock = array();

    function __construct() {
        $this->front = -1;
        $this->end = -1;
    }

    /**
     * Checks if the stock is empty
     *
     * @return Boolean
     */
    public function isEmpty() {
        return $this->front > $this->end || ($this->end == $this->front && $this->stock[$this->front]->quantity == 0);
    }

    /**
     * Checks if the stock is empty
     *
     * @return App\MyClasses\Stock;
     */
    public function getStockOnHand() {
        $stockOnHand = [];

        for ($x = $this->front; $x <= $this->end; $x++) {
            $stockOnHand[] = unserialize(serialize($this->stock[$x]));
        }

        return $stockOnHand;
    }

    /**
     * Add element to stock
     * Element is added to the end of the queue
     *
     * @return App\Models\Transaction;
     * Returns transaction added to stock
     */
    public function addStock($transaction) {
        if ($this->front === -1) {
            ++$this->front;
        }
        $this->stock[++$this->end] = $transaction;
        
        return $transaction;
    }

    /**
     * Remove element from the stock queue
     * Element is removed at the start of the queue
     *
     * @return Boolean
     * Returns bool on success or failure
     */
    public function removeStock($transaction) {
        $removed_list = [];
        if($this->isEmpty()){
            throw new Exception('Quantity applied exceeds quantity on hand.');
        } else {
            $stockToRemove = abs($transaction->quantity);
            $original_stock = unserialize(serialize($this));

            // Loop through available stock on hand,
            // while there is still items to remove, keep using up stock
            while ($stockToRemove > 0) {
                if ($this->isEmpty()) {
                    // If stock on hand is all used up and there is still more to remove,
                    // restore stock to what it was before removal of stock numbers
                    // and flag error that Quantity applied exceeds quantity on hand
                    $this->front = $original_stock->front;
                    $this->end = $original_stock->end;
                    $this->transactions = $original_stock->transactions;
                    $this->stock = $original_stock->stock;
                    throw new Exception('Quantity applied exceeds quantity on hand.');
                }

                $quantity_removed = $this->stock[$this->front]->quantity;
                if ($stockToRemove >= $this->stock[$this->front]->quantity) {
                    // If front is all used up, set to 0
                    // and remove from the next item in the queue
                    $removed = new Transaction([$transaction->date, 'Application', $this->stock[$this->front]->quantity, $this->stock[$this->front]->price]);
                    $removed_list[] = $removed;
                    $this->stock[$this->front]->quantity = 0;
                    $this->front++;
                } else {
                    $this->stock[$this->front]->quantity -= $stockToRemove;
                    $removed = new Transaction([$transaction->date, 'Application', $stockToRemove, $this->stock[$this->front]->price]);
                    $removed_list[] = $removed;
                }
                $stockToRemove -= $quantity_removed;
            }  
            return $removed_list;
        }
    }

    /**
     * Initializes stocks on hand
     * Loops through transactions 
     * adds/removes them according to transaction type
     *
     * @return Array/Boolean
     * Returns Array of returned 
     */
    public function initializeStock()
    {
        foreach($this->transactions as $transaction) {
            if ($transaction->type === 'Purchase') {
                $this->addStock($transaction);
            } else {
                $this->removeStock($transaction);
            }
        }
        return true;
    }

    /**
     * Gets total valuation of stock passed
     *
     * @return Array/Boolean
     * Returns Array of returned 
     */
    public static function getAppliedValuation($applied)
    {
        $valuation = 0;
        foreach($applied as $appliedItem) {
            $valuation += $appliedItem->quantity * $appliedItem->price;
        }
        return $valuation;
    }
}
?>