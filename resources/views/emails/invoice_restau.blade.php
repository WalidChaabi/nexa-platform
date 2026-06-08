<div id="invoice" style="display: flex;flex-direction: column;">
    <div class="info"
        style="justify-content:center;align-items:center;text-align: center;padding-top: -30px!important;top:0px !important">
        <div style="left:0px!important;right:0px;top:0px !important;">
            <img src="{{ auth()->user()->store->logo != '' ? asset(getImageByModel(auth()->user()->store->id, 'stores', auth()->user()->store->logo)) : 'https://via.placeholder.com/350x150' }}"
                style="height:75px;width:75px;margin-top:-30px">
        </div>
        <strong style="text-align: center;padding-top: -90px!important">
            {{ auth()->user()->store->name }}
        </strong>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div id="invoice-POS" style=" margin:0px auto ;width: 325px;">
        <center id="top">
            <div id="info">
                <div class="info" style="text-align:justify;">
                    <span style="text-align: left">{{ trans('Ticket') }}</span> : <span style="text-align: right">
                        {{ $content->first()[0]->invoice_date }} // #{{ $content->first()[0]->id }} </span><br>
                    <span style="text-align: left">{{ trans('Client') }}</span> : <span style="text-align: right">
                        {{ $content->first()[0]->firstname . ' ' . $content->first()[0]->lastname }} // Points :
                        {{ convertToPoints($content->first()[0]->myPoints, auth()->user()->store->coeff) }} </span><br>
                </div>
            </div>
        </center>
        <!--End InvoiceTop-->

        <!--End Invoice Mid-->
        <p style="text-align: center">*************************************</p>
        <div id="bot">

            <table class="info-table" id="customers" style="width:325px;left:10px;right:10px">
                <tbody>
                    {{ $total = 0 }}
                    {{ $i = 0 }}
                    @foreach ($content as $val)
                        @php
                            $i++;
                        @endphp
                        <tr @if ($i == $content->count() + 1) style="border-style:groove;" @endif>
                            <td class="text-center font fontC">{{ $val->first()->label }} @if (count($val) > 1)
                                    ({{ count($val) }})
                                @endif
                            </td>
                            <td class="text-center font fontC" colspan="3">
                                @if (count($val) > 1)
                                    {{ $val->first()->price * count($val) }}
                                @else
                                    {{ $val->first()->price }}
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    <tr style="border-style: groove;">
                        <td class="text-center font fontC">Total HT</td>
                        <td class="text-center font fontC" colspan="3">{{ $total }}</td>
                    </tr>
                    <tr style="border-style: groove;">
                        <td class="text-center font fontC">R.R.R</td>
                        <td class="text-center font fontC" colspan="3">{{ $content->first()[0]->points }}</td>
                    </tr>
                    <!--<tr style="border-style: groove;">-->
                    <!--    <td class="text-center font fontC" >Réduction d'ouverture 10%</td>-->
                    <!--    <td class="text-center font fontC">{{ ceil($total * 0.1) }}</td>-->
                    <!--</tr>-->
                    <tr style="border-style: groove;">
                        <td class="text-center font fontC">TVA * {{ $content->first()[0]->tva }} (%)</td>
                        <td class="text-center font fontC" colspan="3">
                            {{ ($total * $content->first()[0]->tva) / 100 }} </td>
                    </tr>

                    <tr style="border-style: groove;">
                        <td class="text-center font fontC">Total TTC</td>
                        <td class="text-center font fontC" colspan="3">{{ $content->first()[0]->total }}</td>
                    </tr>
                    <tr style="border-style: groove;">
                        <td class="text-center font fontC">Montant Payé</td>
                        <td class="text-center font fontC" colspan="3">{{ $content->first()[0]->paid_amount }}</td>
                    </tr>
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
            {{ $content->first()[0]->store_address }} <br>
            {{ trans('Num') }} : {{ auth()->user()->store->phone }}
        </p>

    </div>
</div>

<!--End Invoice-->
<style>
    #invoice {
        left: 0;
        right: 0;
        margin: 0 auto;
        position: fixed;
    }

    #bot {
        width: 250px;
    }

    .cp-text {
        text-align: center;
        color: black
    }

    p {
        font-size: 10px !important;
    }

    .info {
        display: block;
        margin: 0;
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
        margin: 0px;
    }

    td {
        //padding: 5px 0 5px 15px;
        //border: 1px solid #EEE
    }
</style>
