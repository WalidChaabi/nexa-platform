<div class="info" style="text-align: center;padding-top: -30px!important;top:0px !important">
    <div style="margin-left: 250px!important;top:0px !important">
        {{-- <img src="{{(auth()->user()->store->logo)!='' ? asset(getImageByModel(auth()->user()->store->id,'stores',auth()->user()->store->logo)):'https://via.placeholder.com/350x150' }}" width="75" height="75"> --}}
        <img src="{{public_path('uploads/stores/1/logo-sherin.png')}}" width="75" height="75">
    </div>
    <h2 style="text-align: center;padding-top: -30px!important">
        {{ auth()->user()->store->name }}
    </h2>
</div>

<div id="invoice-POS" style="padding-top: -20px!important">

    <center id="top">
        <div id="info">
            <div class="info" style="text-align:justify;">
                <span style="text-align: left">{{ trans('Date Ticket') }}</span> : <span style="text-align: right">
                    {{ $content[0]->invoice_date }}</span><br>
                <span style="text-align: left">{{ trans('Ticket') }} N°</span> : <span style="text-align: right">
                    #{{ $content[0]->id }}</span><br>
                {{-- <span style="text-align: left">{{ trans('Magasin') }}</span>    : <span style="text-align: right">  {{ $content[0]->store_name }}</span><br> --}}
                <span style="text-align: left">{{ trans('M.Paiement ') }}</span>: <span style="text-align: right">
                    {{ getPaiementMethod($content[0]->payment_method) }}</span><br>
                <span style="text-align: left">{{ trans('Client') }}</span> : <span style="text-align: right">
                    {{ $content[0]->firstname . ' ' . $content[0]->lastname }} </span><br>
                <span style="text-align: left">{{ trans('Mes Points') }}</span> : <span style="text-align: right">
                    {{ convertToPoints($content[0]->myPoints, auth()->user()->store->coeff) }} </span>

            </div>
        </div>
    </center>
    <!--End InvoiceTop-->

    <!--End Invoice Mid-->
    <p style="text-align: center">*************************************</p>
    <div id="bot">

        <table class="info-table" id="customers" style="border-style: dotted;">

            <tr>
                <th class="wd-15p font">Produit</th>
                <th class="wd-15p font">Qté</th>
                <th class="wd-15p font">P.U</th>
                <th class="wd-15p font">Total Mad</th>

            </tr>

            <tbody>
                {{ $total = 0 }}
                @foreach ($content as $val)
                    {{ $total += $val->price * $val->qte }}
                    <tr style="border-style: dotted;">
                        <td class="text-center font fontC">{{ $val->label }}</td>
                        <td class="text-center font fontC">{{ $val->qte }}</td>
                        <td class="text-center font fontC">{{ $val->price }}</td>
                        <td class="text-center font fontC">{{ $val->price * $val->qte }}</td>
                    </tr>
                @endforeach
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">Total HT</td>
                    <td class="text-center font fontC">{{ $total }}</td>
                </tr>
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">R.R.R</td>
                    <td class="text-center font fontC">{{ $content[0]->points }}</td>
                </tr>
                <!--<tr style="border-style: dotted;">-->
                <!--    <td class="text-center font fontC" colspan="3">Réduction d'ouverture 10%</td>-->
                <!--    <td class="text-center font fontC">{{ ceil($total * 0.1) }}</td>-->
                <!--</tr>-->
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">TVA * {{ $content[0]->tva }} (%)</td>
                    <td class="text-center font fontC"> {{ ($total * $content[0]->tva) / 100 }} </td>
                </tr>

                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">Total TTC</td>
                    <td class="text-center font fontC">{{ $content[0]->total }}</td>
                </tr>
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">Montant Payé</td>
                    <td class="text-center font fontC">{{ $content[0]->paid_amount }}</td>
                </tr>
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">Reste</td>
                    <td class="text-center font fontC">{{ $content[0]->total - $content[0]->paid_amount }}</td>
                </tr>
                @if(in_array(auth()->user()->store->id,[19,22]))
                <tr style="border-style: dotted;">
                    <td class="text-center font fontC" colspan="3">Reste Payer</td>
                    <td class="text-center font fontC">{{ $content[0]->reste_payer }}</td>
                </tr>
                @endif
            </tbody>
        </table>


        <!--End Table-->
    </div>
    <!--End InvoiceBot-->
    <p class="cp-text" style="text-align:justify;font-weight: bold;">
        {{ auth()->user()->store->invoice_message }}
    </p>
    <p class="cp-text">
        {{ trans('Info Magasin') }} :
    </p>
    <p class="cp-text" style="font-weight: bold; ">
        {{ auth()->user()->store->name }} <br>
        {{ $content[0]->store_address }} <br>
        {{ trans('Num') }} : {{ auth()->user()->store->phone }}
    </p>

</div>
<!--End Invoice-->
<style>
    .cp-text {
        text-align: center;
        color: black
    }

    p {
        font-size: 10px !important;
    }

    .info {
        display: block;
        margin-left: 0;
    }

    .title {
        float: right;
    }

    .title p {
        text-align: right;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 5px 0 5px 15px;
        border: 1px solid #EEE
    }
</style>
