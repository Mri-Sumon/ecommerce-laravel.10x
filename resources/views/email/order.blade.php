<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Order Email</title>
    </head>

    <body style="font-family: Arial, Helvetica, sans-serif; font-size: 16px;">

        @if ($mailData['userType'] == 'customer')
            <h1>Thanks for your Order!</h1>
            <h2>Your order id is : #{{ $mailData['order']->id }}</h2>
        @else
            <h1>You have received an Order.</h1>
            <h2>Order id : #{{ $mailData['order']->id }}</h2>
        @endif


        <h2 class="h5 mb-3">Shipping Address</h2>
        <address>
            <strong>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name }}</strong><br>
            {{ $mailData['order']->address }}<br>
            {{ $mailData['order']->city }},
            {{ $mailData['order']->zip }},
            {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
            Phone: {{ $mailData['order']->mobile }}<br>
            Email: {{ $mailData['order']->email }}
        </address>


        <h2>Product</h2>
        <table cellpadding="3" cellspecing="3" border="0" width="700">

            <thead>
                <tr style="background-color: #ccc">
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($mailData['order']->items as $orderItem)
                    <tr>
                        <td>{{ $orderItem->name }}</td>
                        <td>${{ number_format($orderItem->price, 2) }}</td>
                        <td>{{ $orderItem->qty }}</td>
                        <td>${{ number_format($orderItem->total, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="3" align="right">Subtotal:</th>
                    <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
                </tr>

                <tr>
                    <th colspan="3" align="right">Discount:{{ (!empty($mailData['order']->coupon_code) ? '('.$mailData['order']->coupon_code.')' : '') }}</th>
                    <td>${{ number_format($mailData['order']->discount, 2) }}</td>
                </tr>

                <tr>
                    <th colspan="3" align="right">Shipping:</th>
                    <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
                </tr>

                <tr>
                    <th colspan="3" align="right">Grand Total:</th>
                    <td>${{ number_format($mailData['order']->grant_total, 2) }}</td>
                </tr>

            </tbody>

        </table>
    </body>
</html>



























