<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Re&ccedil;u de paiement</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/style.css" rel="stylesheet" />
    <script src="https://payment.cmi.co.ma/fim/resource2/js/jquery-1.10.2.min.js"></script>
    <!--[if lt IE 9]>
        <script src="https://payment.cmi.co.ma/fim/resource2/js/html5shiv.js"></script>
        <script src="https://payment.cmi.co.ma/fim/resource2/js/respond.min.js"></script>
    <![endif]-->
    <style>
    @media print {
        .btn-info {
            display: none;
        }
        #retour {
            display: none;
        }
    }
    </style>
    <script>
    $(window).load(function() {
        $("img").each(function() {
            var image = $(this);
            if (image.context.naturalWidth == 0 || image.readyState == 'uninitialized') {
                $(image).unbind("error").hide();
            }
        });

        var CustomOid = $("input[name='oid']").val();
        var ReturnOid = $("input[name='ReturnOid']").val();
        if (CustomOid == undefined || CustomOid == "") {
            document.getElementById("ReturnOid").innerHTML = ReturnOid;
        }
    });

    function senddata() {
        if (typeof document.forms["printForm"].okUrl == "undefined") {
            document.forms["printForm"].action =
                "https://www.ancfcc.gov.ma/AdminLogin/surface/CertificatsSurface/GetInfo";
            document.forms["printForm"].submit();
        } else {
            document.forms["printForm"].action = document.forms["printForm"].okUrl.value;
            document.forms["printForm"].submit();
        }
    }
    </script>
</head>

<body>



    <!-- MENU SECTION END-->
    <div class="content-wrapper" style="display:none;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <img src="merchantLogo.png?dimUid=600000390" id="logo" style="float: right; position: relative; ">
                    <h1 class="page-head-line" id="rp">Re&ccedil;u de paiement</h1>
                </div>
            </div>

            <!--                 <div class="row">
                    <div class="col-md-12">
                        Votre paiement a &eacute;t&eacute; accept&eacute;
                    </div>

                </div> -->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert" class="alert">
                        <center>
                            <b>
                                <font class="important" style="font-size: initial;" id="font"></font>
                            </b>

                            <br />
                            <a id="retour">Cliquez ici pour retourner au site
                                marchand</a>

                        </center>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="dpp">
                            D&eacute;tail de Paiement
                        </div>
                        <div class="panel-body">

                            <table style="width: 95%;">
                                <tr style="vertical-align: top;">
                                    <td id="dp" style="width: 35%">
                                        <b>Date de paiement</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        10/02/2021 13:24:33
                                    </td>
                                </tr>
                                <tr id="d1" style="vertical-align: top;">
                                    <td style="width: 35%">
                                        <b>N&deg; de paiement</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        968305
                                    </td>
                                </tr>
                                <tr id="d2" style="vertical-align: top;">
                                    <td style="width: 35%">
                                        <b>Code d'autorisation</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        009530
                                    </td>
                                </tr>

                                <tr id="d3" style="vertical-align: top;">
                                    <td style="width: 35%">
                                        <b>M&eacute;thode de paiement</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        VISA
                                    </td>
                                </tr>
                                <tr id="d4" style="vertical-align: top;">
                                    <td style="width: 35%">
                                        <b>N&deg; de carte de paiement</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        **** **** **** <script>
                                        document.write("4779 10** **** 6283".substr(15, 19));
                                        </script>
                                    </td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td style="width: 35%">
                                        <b id="nt">N&deg; transaction </b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        21041NYaD14217
                                    </td>
                                </tr>
                                <tr style="vertical-align: top; display:none" id="recurrent">
                                    <td style="width: 35%;vertical-align: top;">
                                        <b id="nt">Paiement r&eacute;current </b>
                                    </td>
                                    <td style="width: 5%;vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 60%">
                                        Un total de transactions seront op&eacute;r&eacute;es avec une fr&eacute;quence
                                        d'une transaction chaque
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>


                <div class="col-md-6" id="detail_commande">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            D&eacute;tail de la commande
                        </div>


                        <div class="panel-body">
                            <table style="width: 95%;">
                                <tr style="vertical-align: top;">
                                    <td style="width: 20%">
                                        <b>Identifiant</b>
                                    </td>
                                    <td style="width: 5%">
                                        :
                                    </td>
                                    <td style="width: 75%" id="ReturnOid">
                                        CPP63748560021783
                                    </td>
                                </tr>



                                <tr style="vertical-align: top;">
                                    <td style="width: 20%;vertical-align: top;">
                                        <b>Montant</b>
                                    </td>
                                    <td style="width: 5%;vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 75%;vertical-align: top;">
                                        100
                                        <span> MAD</span>
                                        <br>
                                        <div id="curdiv" style="display:none;">
                                            <span id="amountCurspan"></span> <span id="symbolCurspan"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="display:none;" id="Change">
                                    <td style="width: 20%;vertical-align: top;">
                                        <b>Change</b>
                                    </td>
                                    <td style="width: 5%;vertical-align: top;">
                                        :
                                    </td>
                                    <td class="total" style="width: 75%;vertical-align: top;">
                                        <select style="margin-right: 20px;">
                                            @@exchangeamounts@@
                                        </select>
                                    </td>
                                </tr>
                                <tr id="Changemessage" style="display:none;">
                                    <td colspan=2></td>
                                    <td><small>La valeur en devise est approximative sous r&eacute;serve des variations
                                            des taux de change journaliers</small></td>
                                </tr>

                                <!--
                                    <tr style="vertical-align: top;">
                                        <td style="width: 20%">
                                            <b>Surcharge</b>
                                        </td>
                                        <td style="width: 5%">
                                            :
                                        </td>
                                        <td style="width: 75%">
                                             <span>MAD</span>
                                        </td>
                                    </tr>
                                    -->

                            </table>
                        </div>
                        <!--
                           <div class="table-responsive-sm">
                                 <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="" valign="middle">Code</th>
                                            <th class="" valign="middle">Id</th>
                                            <th class="" valign="middle">Description</th>
                                            <th valign="middle" colspan="2">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>



									</tbody>
                                    <tfoot>
                                        
                                        <tr>
                                            <td colspan="3">Surcharge</td>
                                            <td colspan="2"> MAD</td> 
                                        </tr>
                                        
										 <tr>
                                            <td rowspan="2" style="border-bottom: none;vertical-align: middle;"><b>Total</b></td>
                                           <td colspan="3" class="total" style="text-align: right;"><b style="color: #D2021B;">100</td>
										   <td>MAD</td>                                  
                                        </tr>			
										
										<tr id="curdiv2" style="display:none; border-bottom: 1px solid #ddd;" >
                                           <td style="border-top: none;text-align: right;"><span id="amountCurspan2" ></span></td>
										   <td style="border-top: none;"><span id="symbolCurspan2" ></span></td>
                                  
                                        </tr>								
										
										
										 <tr style="display:none;" id="Change2" >
                                             <td colspan="3"
											 >Change</td>
                                            <td class="total" colspan="2"><b style="color: #D2021B;">

                                              <select style="margin-right: 20px;">
                                                  @@exchangeamounts@@
                                              </select>
                                              </td>
                                        </tr>
										
										<tr id="Changemessage2" style="display:none;" >
										<td colspan=5><small>La valeur en devise est approximative sous r&eacute;serve des variations des taux de change journaliers</small></td>
										</tr>
										
										
                              </tfoot>
                          </table>
                    </div>
				  <div style="display:none">-->
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Informations du Client
                    </div>
                    <div class="panel-body">
                        <table style="width: 95%;">
                            <tr style="vertical-align: top;">
                                <td style="width: 20%">
                                    <b>Nom</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 75%">
                                    EL MOUMARINE JILALI
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td style="width: 20%">
                                    <b>Adresse</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 75%">
                                    Azli 725, Settat
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td style="width: 20%">
                                    <b>T&eacute;l</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 75%">
                                    +212600000601
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td style="width: 20%">
                                    <b>E-mail</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 75%">
                                    jilali@nejmatech.com
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        D&eacute;tail Marchand
                    </div>
                    <div class="panel-body">
                        <table style="width: 95%;">
                            <tr style="vertical-align: top;">
                                <td style="width: 35%">
                                    <b>Nom du marchand</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 60%">
                                    ANCFCC GRAND PUBLIC (600000390)
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td style="width: 35%">
                                    <b>Adresse du marchand</b>
                                </td>
                                <td style="width: 5%">
                                    :
                                </td>
                                <td style="width: 60%">
                                    Angle Avenue My Youssef et Avenue My Hassan 1er. Rabat Rabat 10000 504
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <form name="printForm" id="printForm" action="/fim/exportorderreceipt" method="post">
                    <input type="hidden" name="ReturnOid" value="CPP63748560021783">
                    <input type="hidden" name="TRANID" value="27296267">
                    <input type="hidden" name="hashAlgorithm" value="ver3">
                    <input type="hidden" name="PAResSyntaxOK" value="">
                    <input type="hidden" name="BillToCompany" value="">
                    <input type="hidden" name="CallbackResponse" value="true">
                    <input type="hidden" name="refreshtime" value="300">
                    <input type="hidden" name="lang" value="fr">
                    <input type="hidden" name="merchantID" value="600000390">
                    <input type="hidden" name="maskedCreditCard" value="4779 10** **** 6283">
                    <input type="hidden" name="amount" value="100">
                    <input type="hidden" name="ACQBIN" value="439218">
                    <input type="hidden" name="BillToStreet1" value="Azli 725, Settat">
                    <input type="hidden" name="Ecom_Payment_Card_ExpDate_Year" value="24">
                    <input type="hidden" name="EXTRA.CARDBRAND" value="VISA">
                    <input type="hidden" name="MaskedPan" value="477910***6283">
                    <input type="hidden" name="acqStan" value="968305">
                    <input type="hidden" name="paymentType" value="CARD">
                    <input type="hidden" name="clientIp" value="196.65.128.77">
                    <input type="hidden" name="BillToName" value="EL MOUMARINE JILALI">
                    <input type="hidden" name="cardHolderName" value="jilali Mazouzi">
                    <input type="hidden" name="CVVPresence" value="1">
                    <input type="hidden" name="iReqDetail" value="">
                    <input type="hidden" name="okUrl"
                        value="https://www.ancfcc.gov.ma/AdminLogin/surface/CertificatsSurface/GetInfo">
                    <input type="hidden" name="choix1" value="on">
                    <input type="hidden" name="BillToCity" value="">
                    <input type="hidden" name="md"
                        value="477910:881B300D3910242B2F403881D684221599EFCE1FB8E7327661FA2DEBC922A5CE:3518:##600000390">
                    <input type="hidden" name="ProcReturnCode" value="00">
                    <input type="hidden" name="payResults.dsId" value="1">
                    <input type="hidden" name="vendorCode" value="">
                    <input type="hidden" name="TransId" value="21041NYaD14217">
                    <input type="hidden" name="EXTRA.TRXDATE" value="10/02/2021 13:24:26">
                    <input type="hidden" name="email" value="jilali@nejmatech.com">
                    <input type="hidden" name="tel" value="+212600000601">
                    <input type="hidden" name="EXTRA.CVVVERIFICATION" value="M">
                    <input type="hidden" name="BillToPostalCode" value="">
                    <input type="hidden" name="Ecom_Payment_Card_ExpDate_Month" value="09">
                    <input type="hidden" name="storetype" value="3D_PAY_HOSTING">
                    <input type="hidden" name="iReqCode" value="">
                    <input type="hidden" name="BillToCountry" value="">
                    <input type="hidden" name="Response" value="Approved">
                    <input type="hidden" name="SettleId" value="1">
                    <input type="hidden" name="mdErrorMsg" value="">
                    <input type="hidden" name="BillToStateProv" value="">
                    <input type="hidden" name="ErrMsg" value="">
                    <input type="hidden" name="PAResVerified" value="">
                    <input type="hidden" name="cavv" value="AAABAlBBUAAAAANBUHEhAAAAAAA=">
                    <input type="hidden" name="shopurl" value="https://www.ancfcc.gov.ma/FailUrlPage">
                    <input type="hidden" name="TranType" value="PreAuth">
                    <input type="hidden" name="digest" value="digest">
                    <input type="hidden" name="HostRefNum" value="104113968305">
                    <input type="hidden" name="AuthCode" value="009530">
                    <input type="hidden" name="failUrl" value="https://www.ancfcc.gov.ma/FailUrlPage">
                    <input type="hidden" name="callbackCall" value="true">
                    <input type="hidden" name="EXTRA.HOSTMSG" value="APPROVED">
                    <input type="hidden" name="cavvAlgorithm" value="">
                    <input type="hidden" name="xid" value="4q2opfU5krUvXbhyt3efWoGs114=">
                    <input type="hidden" name="callbackUrl"
                        value="https://www.ancfcc.gov.ma/AdminLogin/surface/CertificatsSurface/callBackOK">
                    <input type="hidden" name="encoding" value="UTF-8">
                    <input type="hidden" name="currency" value="504">
                    <input type="hidden" name="oid" value="CPP63748560021783">
                    <input type="hidden" name="SID" value="">
                    <input type="hidden" name="mdStatus" value="1">
                    <input type="hidden" name="dsId" value="1">
                    <input type="hidden" name="eci" value="05">
                    <input type="hidden" name="version" value="">
                    <input type="hidden" name="ccnHash" value="/OO6ZuEa6Tpz4zGSWRnM14xiudxb+WgSVPU+ZzIIW1s=">
                    <input type="hidden" name="EXTRA.CARDISSUER" value="BMCE">
                    <input type="hidden" name="clientid" value="600000390">
                    <input type="hidden" name="txstatus" value="Y">
                    <input type="hidden" name="HASH"
                        value="h+3MOrvDDA5sW4YxRK818Krz5kdDPMaMXWhYNvq2b+wSIZF6DBqwdO6eYu7PBQbM1lnnLD7TojABvSOPRNPotA==">
                    <input type="hidden" name="rnd" value="ckQoEcn+SHH37APrIVP9">
                </form>
                <center><button class="btn btn-info btn-lg  col-md-4" style="float: none;" id="print"><i
                            class="fa fa-file-pdf-o"></i> &nbsp; Imprimer</button></center>
                <br />
            </div>
        </div>
        <!-- CONTENT-WRAPPER SECTION END-->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <table style="float: left;">
                            <tr>
                                <td>
                                    <img src="https://payment.cmi.co.ma/fim/resource2/img/logo_cmi.gif"
                                        style="height: 25px; margin-right: 10px;" />
                                </td>
                                <td>
                                    <a href="#"> Centre Mon&eacute;tique Interbancaire </a>
                                </td>
                            </tr>
                        </table>

                        <img src="https://payment.cmi.co.ma/fim/resource2/img/illu-secure-min.png" alt="" usemap="#Map"
                            style="float: right;margin-right: 50px;" />
                        <map name="Map" id="Map">
                            <area alt="" title="" href="#" shape="poly" coords="42,0,43,24,0,23,0,3" />
                            <area alt="" title="" href="#" shape="poly" coords="96,2,98,23,50,23,50,1" />
                            <area alt="" title="" href="#" shape="poly" coords="104,1,107,23,167,23,167,3" />
                            <area alt="" title="" href="#" shape="poly" coords="172,1,174,23,208,24,209,2" />
                        </map>
                    </div>

                </div>
            </div>
        </footer>

    </div>



    </div>
    </div>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <!-- BOOTSTRAP SCRIPTS  -->
    <script>
    if ($("input[name$='EXTRA.RECURRINGID']").length > 0) $('#recurrent').show();
    amountCur = $("input[name$='amountCur']");
    symbolCur = $("input[name$='symbolCur']");
    currenciesList = $("input[name$='currenciesList']");

    if (amountCur.length > 0 && symbolCur.length > 0 && amountCur.val() != "" && symbolCur.val() != "") {

        $('#amountCurspan').html(amountCur.val());
        $('#symbolCurspan').html(symbolCur.val());
        $('#curdiv').show();

        $('#amountCurspan2').html(amountCur.val());
        $('#symbolCurspan2').html(symbolCur.val());
        $('#curdiv2').show();

    }

    if (currenciesList.length > 0 && currenciesList.val().toUpperCase() == "TRUE") {
        //$('#Change').show();
        //$('#Change2').show();
    }

    if ((amountCur.length > 0 && symbolCur.length > 0 && amountCur.val() != "" && symbolCur.val() != "") || (
            currenciesList.length > 0 && currenciesList.val().toUpperCase() == "TRUE")) {
        $('#Changemessage').show();
        $('#Changemessage2').show();
    }



    if (($("input[name$='AutoRedirect']").length > 0 && $("input[name$='AutoRedirect']").val().toUpperCase() ==
        "TRUE")) {
        senddata();
    } else {
        $('.content-wrapper').show();
    }
    </script>


    <script>
    if ("Votre paiement a &eacute;t&eacute; accept&eacute;" != "") {
        if ("Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 4) == "RES-" &&
            "Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 8) != "RES-0001") {

            if ("Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 8) == "RES-0002") {
                document.getElementById("alert").className += " alert-danger";
                document.title = "R&eacute;sultat de paiement";
                document.getElementById("rp").innerHTML = "R&eacute;sultat de paiement";
                document.getElementById("dp").innerHTML = "Date de l�op&eacute;ration";
                document.getElementById("nt").innerHTML = "N&deg; op&eacute;ration";
                document.getElementById("dpp").innerHTML = "D&eacute;tail de l�op&eacute;ration";
                document.getElementById("d1").style.display = "none";
                document.getElementById("d2").style.display = "none";
                document.getElementById("d3").style.display = "none";
                document.getElementById("d4").style.display = "none";
                document.getElementById("print").style.display = "none";
            } else if ("Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 8) == "RES-0003" ||
                "Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 8) == "RES-0004") {

                document.getElementById("alert").className += " alert-danger";
                document.title = "Re&ccedil;u d�autorisation de paiement";
                document.getElementById("rp").innerHTML = "Re&ccedil;u d�autorisation de paiement";

            } else if ("Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 8) == "RES-0000") {
                document.getElementById("alert").className += " alert-warning";
            }

        } else
            document.getElementById("alert").className += " alert-success";
        if ("Votre paiement a &eacute;t&eacute; accept&eacute;".substring(0, 4) == "RES-")
            document.getElementById("font").innerHTML = "Votre paiement a &eacute;t&eacute; accept&eacute;".substr(9);
        else
            document.getElementById("font").innerHTML = "Votre paiement a &eacute;t&eacute; accept&eacute;";
    } else {
        document.getElementById("alert").className += " alert-info";
        document.getElementById("font").innerHTML =
            "L�autorisation de paiement a &eacute;t&eacute; trait&eacute;e correctement.";
    }

    if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(
            /Android/i) || navigator.userAgent.match(/mobile/i) || navigator.userAgent.match(/BlackBerry/i)) {
        document.getElementById("print").style.display = "none";
    }
    </script>
    <script src="https://payment.cmi.co.ma/fim/resource2/js/bootstrap.js"></script>
</body>

</html>