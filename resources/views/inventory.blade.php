<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fertiliser</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('styles.css') }}" rel="stylesheet">
    </head>
    <body>
        <div style="width:100%;">
            <div>
                <label>Quantity:</label>
                <input type="number" type="text" id="quantity" min="1">
                <button type="button" onclick="checkInventory()">Check Inventory</button>
            </div>
            <div style="float:left; width:50%;">
                <div>
                    @isset($applied)
                        <h4>Applied</h4>
                        <table style="border: 1px solid green;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Valuation</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($applied as $appliedItem)
                                        <tr>
                                            <td>{{ $appliedItem->date }}</td>
                                            <td>{{ $appliedItem->type }}</td>
                                            <td>{{ $appliedItem->quantity }}</td>
                                            <td>{{ $appliedItem->price }}</td>
                                            <td>{{ $appliedItem->quantity * $appliedItem->price }}</td>
                                        </tr>
                                    @endforeach
                                    <tr id="total_row">
                                        <td colspan="4">Total: </td>
                                        <td>{{ $total_valuation }}</td>
                                    </tr>
                            </tbody>
                        </table>
                    @endisset

                    @isset($error)
                        <h4 style="border: 1px solid red;">Error: {{$error}}</h4>
                    @endisset
                    
                </div>
                <div>
                    <h4>Remaining stock on hand</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Valuation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockItems as $stockItem)
                                <tr>
                                    <td>{{ $stockItem->date }}</td>
                                    <td>{{ $stockItem->type }}</td>
                                    <td>{{ $stockItem->quantity }}</td>
                                    <td>{{ $stockItem->price }}</td>
                                    <td>{{ $stockItem->quantity * $stockItem->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
            <div>
                <h4>All transactions</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Valuation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->type }}</td>
                                <td>{{ $transaction->quantity }}</td>
                                <td>{{ $transaction->price }}</td>
                                <td>{{ $transaction->quantity * $transaction->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
<script>
    function checkInventory() {
        let quantity = document.getElementById('quantity').value;

        if (quantity && quantity >= 0) {
            window.location = "/"+quantity;
        }
    }
</script>