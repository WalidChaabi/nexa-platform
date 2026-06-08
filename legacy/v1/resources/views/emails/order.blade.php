<html>

<body
    style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table
        style="max-width:670px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px rgb(127, 40, 185);">
        <thead>
            <tr>
                <th style="text-align:left;">{{ $store->name }}</th>
                <th style="text-align:right;font-weight:400;">
                        <img src="{{(auth()->user()->store->logo)!='' ? asset(getImageByModel(auth()->user()->store->id,'stores',auth()->user()->store->logo)):'' }}" width="75" height="75">

                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height:35px;"></td>
            </tr>
            <tr>
                <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">{{ trans('Date') }}</span>{{ $invoice->invoice_date }}</p>

                  
                    <p style="font-size:14px;margin:0 0 6px 0;"><span
                            style="font-weight:bold;display:inline-block;min-width:150px">{{ trans('Statut/Commande') }}</span><b
                            style="color:{{ getInvoiceColorByStatus($invoice->status) }};font-weight:normal;margin:0">{{ getInvoiceStatus($invoice->status) }}</b>
                    </p>
                    <p style="font-size:14px;margin:0 0 6px 0;"><span
                            style="font-weight:bold;display:inline-block;min-width:146px">{{ trans('Type de Paiement') }}</span>{{ getPaiementMethod($invoice->payment_method) }}
                    </p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span
                            style="font-weight:bold;display:inline-block;min-width:146px">{{ trans('Montant Payé') }}</span>{{ $invoice->paid_amount }}
                        Mad</p>
                </td>
            </tr>
            <tr>
                <td style="height:35px;"></td>
            </tr>
            <tr>
                <td style="width:50%;padding:20px;vertical-align:top">
                    
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span
                            style="display:block;font-weight:bold;font-size:13px;">{{ trans('E-mail') }}</span>
                        {{ $invoice->customer->email }}</p>
                </td>
                <td style="width:50%;padding:20px;vertical-align:top">

                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span
                            style="display:block;font-weight:bold;font-size:13px;">{{ trans('Téléphone') }}</span>{{ $invoice->customer->phone }}
                    </p>
                    <p style="margin:0 0 10px 0;padding:0;font-size:14px;"><span
                            style="display:block;font-weight:bold;font-size:13px;">{{ trans('Mes Points') }}</span>{{ convertToPoints($invoice->customer->points, $store->coeff) }}
                        ({{ trans('Points') }})</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:20px;padding:30px 15px 0 15px;">{{ trans('Produits/Préstations') }}
                </td>
            </tr>
            <table style="width:150%;border: 1px solid black;">
                <tr style="border: 1px solid black;">
                    <th style="border: 1px solid black;">{{ trans('Produits/Préstations') }}</th>
                    <th style="border: 1px solid black;">{{ trans('Quantité') }}</th>
                    <th style="border: 1px solid black;">{{ trans('Prix') }}</th>
                    <th style="border: 1px solid black;">{{ trans('Sous Total') }}</th>
                </tr>
                @foreach ($invoice->items as $item)
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">{{ $item->product->label }}</td>
                        <td style="border: 1px solid black;">{{ $item->qte }}</td>
                        <td style="border: 1px solid black;">{{ $item->price }}</td>
                        <td style="border: 1px solid black;">{{ $item->price * $item->qte }}</td>
                    </tr>
                @endforeach
            </table>
            <tr></tr>
            <table>
                <tr>
                    <th style="text-align:left"><span contenteditable>{{ trans('Total HT') }}</span></th>
                    <td><span>{{ $invoice->total_ht }}</span><span data-prefix>Mad</span></td>
                </tr>
                <tr>
                    <th style="text-align:left"><span contenteditable>{{ trans('R.R.R') }}</span></th>
                    <td><span contenteditable>{{ $invoice->points }}</span><span data-prefix></span></td>
                </tr>
                <!--<tr>-->
                <!--    <th style="text-align:left"><span contenteditable>{{ trans('Réduction d\'ouverture') }} 10% </span></th>-->
                <!--    <td><span>{{$invoice->total * 0.1}}</span><span data-prefix>Mad</span></td>-->
                <!--</tr>-->
                <tr>
                    <th style="text-align:left"><span contenteditable>{{ trans('TVA *') }}</span></th>
                    <td><span>{{ $invoice->tva }}</span><span data-prefix>(%)</span></td>
                </tr>
                <tr>
                    <th style="text-align:left"><span contenteditable>{{ trans('Total TTC') }}</span></th>
                    <td><span>{{ $invoice->total }}</span><span data-prefix>Mad</span></td>
                </tr>
                <tr>
                    <th style="text-align:left"><span contenteditable>{{ trans('Montant Payé') }}</span></th>
                    <td><span>{{ $invoice->paid_amount }}</span><span data-prefix>Mad</span></td>
                </tr>
            </table>

        </tbody>
        <tfooter>
            <tr>
                <td colspan="2" style="font-size:14px;padding:50px 15px 0 15px;">
                    <p style="text-align: justify">
                        {{ $store->invoice_message }}
                    </p>
                    <strong>{{ trans('Info Magasin') }}</strong><br>
                    <b>{{ trans('Téléphone') }}:</b>{{ $store->phone }} <br>
                    <b>{{ trans('Addresse') }}:</b> {{ $store->address }}
                </td>
            </tr>
        </tfooter>
    </table>
</body>

</html>
