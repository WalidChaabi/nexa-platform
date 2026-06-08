    
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
    <title>Demande de paiement</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="https://payment.cmi.co.ma/fim/resource2/css/style.css" rel="stylesheet" />
    <script src="https://payment.cmi.co.ma/fim/resource2/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://payment.cmi.co.ma/fim/resource2/js/ajax.js"></script>
    <!--[if lt IE 9]>
        <script src="https://payment.cmi.co.ma/fim/resource2/js/html5shiv.js"></script>
        <script src="https://payment.cmi.co.ma/fim/resource2/js/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">

        function submitform()
        {
            $('#patienter').show();
            $('#confirmer').hide();
            $('#annuler').hide();
            
            $('#cardHolderName').val($.trim($('#cardHolderName').val()));
        }

        function executer1() {
            mp = document.getElementById("ml");
            if (mp.checked == true) {


                return true;
            } else {
                alert("Merci de lire et accepter les conditions g&eacute;n&eacute;rales d'utilisation du service");
                return false;
            }

        }

        function popup2() {
            window.open('https://payment.cmi.co.ma/fim/resource2/img/cgu.html', 'CGU', config = 'height=750, width=900, toolbar=no, menubar=no, resizable=no, location=no, directories=no, status=no')
        }

        function AppendChild(name, value) {
            if (name == "retainedPoRes") {
                document.getElementById("retainedPoRes").value = value;
                return true;
            }

            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", name);
            input.setAttribute("value", value);
            document.getElementById("mtcw").appendChild(input);
            return true;
        }

        function validatePORequest(retainedPoRes, currentAmount) {
            var amount = currentAmount;

            if (retainedPoRes == null || retainedPoRes == "" || retainedPoRes == "undefined") {
                AppendChild("retainedPoRes", retainedPoRes);
                return true;
            }

            var retainedPoRes = retainedPoRes.split(",", 6);
            var attemptAllowed = retainedPoRes[0];
            var attemptCount = retainedPoRes[1];
            var previousAmount = retainedPoRes[2];
            var previousCCN = retainedPoRes[3];
            var errorDesc = retainedPoRes[4];
            var errorNum = retainedPoRes[5];
            if (attemptAllowed == "Y" || attemptAllowed == "N") {
                var currentCCN = document.mtcw.CCN.value;
                currentAmount = currentAmount.replace(/[^0-9]/g, "");

                if (attemptAllowed == "N") {
                    if ((previousAmount == currentAmount) && (previousCCN == currentCCN)) {
                        var choiceOfBuyer = confirm("You have exceeded maximum number of retries to get payment options for credit card:[" + currentCCN + "] and amount:[" + amount +
                            "].\n\nPress  'Ok'  to place order without payment options. (Or)\nPress  'Cancel'  to place order with different card/amount. ");

                        if (choiceOfBuyer) {
                            //AppendChild("retainedPoRes", "undefined");
                            AppendChild("ContinueWithoutOptions", "TRUE");
                            AppendChild("PO_Retry_Count", attemptCount);
                            AppendChild("PO_Error_Desc", errorDesc);
                            return true;
                        } else {
                            AppendChild("retainedPoRes", retainedPoRes);
                            return false;
                        }
                    } else {
                        AppendChild("retainedPoRes", "undefined");
                        return true;
                    }

                } else if (attemptAllowed == "Y") {
                    AppendChild("retainedPoRes", "undefined");
                    if (errorNum == "102" && previousAmount == currentAmount && previousCCN == currentCCN)
                        AppendChild("PO_Retry_Count", attemptCount);
                    return true;
                }
            } else {
                AppendChild("retainedPoRes", retainedPoRes);
                return true;

            }
        }

        $(window).load(function() {
            $("img").each(function() {
                var image = $(this);
                if (image.context.naturalWidth == 0 || image.readyState == 'uninitialized') {
                    $(image).unbind("error").hide();
                }
            });
        });
        function getParameterByName(name)
        {
            url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[#&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
        
    </script>
</head>

<body>



    <!-- MENU SECTION END-->
    <form name="mtcw" id="mtcw" autocomplete="off" action="est3Dgate" method="post" name="logoform" >
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <img src="merchantLogo.png?dimUid=600000390" id="logo" style="float: right; position: relative; ">
                        <h1 class="page-head-line">Demande de paiement</h1>
                    </div>
                </div>

                <div class="row" style="display: none;" id="div-row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <center>
                               <font id="b-alert" class="important" style="font-size: initial;" id="font"></font>
                            </center>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    if( window.location.href.substring(window.location.href.indexOf('#')) == "#ProcReturnCode=&ErrMsg=")
                    {
                        document.getElementById("b-alert").innerHTML =  "Impossible de proc&eacute;der au paiement";
                        document.getElementById("div-row").style.display = "block";
                    }else
                    if(getParameterByName('ErrMsg') && getParameterByName('ProcReturnCode'))
                    {
                        document.getElementById("b-alert").innerHTML =  atob(getParameterByName('ErrMsg')) + ' (' + getParameterByName('ProcReturnCode') + ')';
                        document.getElementById("div-row").style.display = "block";
                    }
                </script>

                <div class="row">




                    <div class="col-md-6">
                                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                                D&eacute;tail de Paiement <div style="float:right; color: #999; text-transform: none; font-size:12px">mercredi, f&eacute;vrier 10, 2021 13:20:23</div>
                                            </div>
                                            <div class="panel-body">

                                                <div class="form-group">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <b> M&eacute;thode de paiement : </b>
                                        </td>
                                        <td>
                                            <div class="imgPay">
                                                <img src="https://payment.cmi.co.ma/fim/resource2/img/cartes-min.png" border="0" align="absmiddle" hspace="3" style="height: 27px; padding-left: 10px;" >
                                            </div>
                                        </td>
                                    </tr>
                            </table>
                        </div>
<input disabled  type="hidden" name="xid" value="4q2opfU5krUvXbhyt3efWoGs114=">
<input disabled  disabled  type="hidden" name="clientid" value="600000390">
<input disabled  type="hidden" name="amount" value="100">
<input disabled  type="hidden" name="okUrl" value="https://www.ancfcc.gov.ma/AdminLogin/surface/CertificatsSurface/GetInfo">
<input disabled  type="hidden" name="failUrl" value="https://www.ancfcc.gov.ma/FailUrlPage">
<input disabled  type="hidden" name="TranType" value="PreAuth">
<input disabled  type="hidden" name="CallbackResponse" value="true">
<input disabled  type="hidden" name="callbackUrl" value="https://www.ancfcc.gov.ma/AdminLogin/surface/CertificatsSurface/callBackOK">
<input disabled  type="hidden" name="shopurl" value="https://www.ancfcc.gov.ma/FailUrlPage">
<input disabled  type="hidden" name="currency" value="504">
<input disabled  type="hidden" name="storetype" value="3D_PAY_HOSTING">
<input disabled  type="hidden" name="lang" value="fr">
<input disabled  type="hidden" name="hashAlgorithm" value="ver3">
<input disabled  type="hidden" name="BillToName" value="EL MOUMARINE JILALI">
<input disabled  type="hidden" name="BillToCompany" value="">
<input disabled  type="hidden" name="BillToCity" value="">
<input disabled  type="hidden" name="BillToStateProv" value="">
<input disabled  type="hidden" name="BillToStreet1" value="Azli 725, Settat">
<input disabled  type="hidden" name="BillToPostalCode" value="">
<input disabled  type="hidden" name="BillToCountry" value="">
<input disabled  type="hidden" name="email" value="jilali@nejmatech.com">
<input disabled  type="hidden" name="tel" value="+212600000601">
<input disabled  type="hidden" name="oid" value="CPP63748560021783">
<input disabled  type="hidden" name="hash" value="UbJHGwHOdSLELBrCWnf4aNMW7vD8lXycUIGcyahKLdi85aYaO4DoMXtp86uvn/cpBazEhxsx22y+6f7oqSTHLw==">
<input disabled  type="hidden" name="refreshtime" value="300">

                        <div class="form-group">
                            <label></label>
                            <input disabled  type="radio" name="paymentType" value="CARD" checked> &nbsp; &nbsp; <label>Carte bancaire</label>
                        </div>

                        <!--
                        <div class="col-md-10">
                            <input disabled  type="radio" name="paymentType" value="PAYPAL" checked> &nbsp; &nbsp; Paypal
                        </div>
                        --> <!--
                        <div class="col-md-10">
                            <input disabled  type="radio" name="paymentType" value="UPOP" checked> &nbsp; &nbsp; China UnionPay
                        </div>
                        -->


                        <div class="form-group">
                            <label> Nom du porteur de la carte</label>
                            <input disabled  type="text" style="text-transform: uppercase" onkeyup="this.value=this.value.replace(/[^a-zA-Z ]/g, '')" class="form-control" name="cardHolderName" maxlength="50" id="cardHolderName" value="" required placeholder="Nom du porteur de la carte" />
                        </div>
                        <div class="form-group">
                            <label> Num&eacute;ro de carte de paiement</label>
                            <input disabled  type="tel" style="text-transform: uppercase" pattern="[0-9\-]{13,19}" autocomplete="off" required="" onkeyup="getInstalmentTableIfParamNotSent('+zYF1XZRpVT0W/N9qUvSht893iDY3edQmqq0NlEej3z+2UcXt6tmgRGtCj8uyPyGBimvO1iP2OKI9gQxSANu6w==')" class="form-control" name="pan" maxlength="19" id="pan" required placeholder="Num&eacute;ro de carte de paiement" />
                        </div>

                        <div class="form-group">
                            <label> Date d'expiration</label>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%">
                                        <select id="date-validite" class="form-control" disabled name="Ecom_Payment_Card_ExpDate_Month">
                                            <option selected="selected" value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </td>
                                    <td style="width: 2%">

                                    </td>
                                    <td style="width: 40%">
                                        <select  disabled name="Ecom_Payment_Card_ExpDate_Year" class="form-control">
<option value=21>2021</option><option value=22>2022</option><option value=23>2023</option><option value=24>2024</option><option value=25>2025</option><option value=26>2026</option><option value=27>2027</option><option value=28>2028</option><option value=29>2029</option><option value=30>2030</option><option value=31>2031</option><option value=32>2032</option><option value=33>2033</option><option value=34>2034</option><option value=35>2035</option><option value=36>2036</option><option value=37>2037</option><option value=38>2038</option><option value=39>2039</option><option value=40>2040</option>                                            @@years@@
                                        </select>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <label> Code de v&eacute;rification</label>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%">
                                        <input disabled  id="code-verification" pattern="[0-9\-]{3,}" required required="" name="cv2"  maxlength="3" type="tel"  class="form-control" size="3" placeholder="" />
                                        <input disabled  type="hidden" name="CVVPresence" value="1">
                                    </td>
                                    <td style="width: 10%">
                                        <center><a style="color: #0EB0D0; font-size: 9px; text-decoration: none;" href="#" > (?)</a></center>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </table>
                        </div>

                        
                       <!--  <div class="form-group" id="installment-wrapper" data-installment="No Installment" style="display:none">
                            <label> Versements</label>
                            <div id="installment-select-wrapper">
                                <div id="tr_instalment"  style="display:none;">
                            <select id="instalment" class="form-control" name="instalment" >
                                <option value="">-</option>
                            </select>
                        </div>
                            </div>
                            <div class="col-md-6" id="installment-param-wrapper" style="display:none">
                                No Installment
                            </div>

                        </div> -->
                        
                        <!--
                        <div class="form-group">
                            <label> Paiement r&eacute;current </label>
							<p>Un total de  transactions seront op&eacute;r&eacute;es avec une fr&eacute;quence d'une transaction chaque  </p>
                        </div>
                        -->

                        <div class="form-group">
                            <label> </label>
                            <input disabled  type="checkbox" required style="vertical-align: bottom; position: relative; bottom: 4px; margin-right: 15px;" name="choix1" id="ml">Confirmer l'acceptation des <a href="#" >conditions g&eacute;n&eacute;rales d'utilisation du service </a>
                        </div>

                       

                    </div>
                    <div class="panel-footer">
                        Les informations sur le paiement vous concernant resteront confidentielles.
                    </div>

                </div>
            </div>
            
            <div>
            <div class="col-md-6">
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
                                        <td style="width: 75%">
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
                                            <div id="curdiv" style="display:none;" >
                                            <span id="amountCurspan" ></span> <span id="symbolCurspan" ></span>
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
                                        <td class="total" style="width: 75%;vertical-align: top;" >
                                          <select style="margin-right: 20px;">
<option value="7.90 GBP">7.90 GBP</option><option value="9.90 USD">9.90 USD</option><option value="9.30 EUR">9.30 EUR</option>                                              @@exchangeamounts@@
                                          </select>
                                        </td>
                                    </tr>
                                    <tr id="Changemessage" style="display:none;" >
                                        <td colspan=2></td><td><small>La valeur en devise est approximative sous r&eacute;serve des variations des taux de change journaliers</small></td>
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
                                            <th class="" valign="middle" >Code</th>
                                            <th class="" valign="middle" >Id</th>
                                            <th class="" valign="middle">Description</th>
                                            <th valign="middle" colspan="2" style="text-align: right;">Montant</th>
                                        </tr>
                                    </thead>
									<tbody>

									</tbody>
                                   <tfoot>
										
                                        <tr>
                                            <td colspan="3" >Surcharge</td>
                                            <td colspan="2"> MAD</td> 
                                        </tr>
                                        
                                        <tr>
                                            <td rowspan="2" style=" border-bottom: none;vertical-align: middle;"><b>Total</b></td>
                                           <td  colspan="3" class="total" style="text-align: right;"><b style="color: #D2021B;">
										   <input disabled  id="amount" style="text-align: right;" class="input" name="amount" type="text" value="100"readOnly='true' style='background-color: #EBEBE4;'></td>
										   <td>MAD</td>
                                  
                                        </tr>
                                        <tr id="curdiv2" style="display:none; border-bottom: 1px solid #ddd;" >
                                           <td style="border-top: none;text-align: right;"><span id="amountCurspan2" ></span></td>
										   <td style="border-top: none;"><span id="symbolCurspan2" ></span></td>
                                  
                                        </tr>
										                               
                                        <tr style="display:none;" id="Change2" >
                                            <td colspan="3" >Change</td>
                                            <td class="total" colspan="2"><b style="color: #D2021B;">

                                              <select style="margin-right: 20px;">
<option value="7.90 GBP">7.90 GBP</option><option value="9.90 USD">9.90 USD</option><option value="9.30 EUR">9.30 EUR</option>                                                  @@exchangeamounts@@
                                              </select>
                                              </td>
                                        </tr>
                                        
                                        <tr id="Changemessage2" style="display:none;" >
                                        <td colspan=5><small>La valeur en devise est approximative sous r&eacute;serve des variations des taux de change journaliers</small></td>
                                        </tr>
                                     </tfoot>
                                  </table>
                            </div>
                          <div style="display:none">--></div>




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
                        </table>

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
                     <div class="form-group" id="confirmer" >
                            <label></label>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 49%">
                                        <a href="{{url('owner/payment/validate')}}" class="btn btn-success btn-lg" style="width: 100%"  >Valider le paiement</a>
                                    </td>
                                    <td style="width: 2%">
                                    </td>
                                    
                                    <td style="width: 49%">
                                        <a class="btn btn-danger btn-lg " href="#"  style="width: 100%">Annuler</a>
                                    </td>
                                    
                                </tr>
                            </table>
                        </div>
						<div class="form-group" style="display:none;" id="patienter">
                            <center>
                                <img src="https://payment.cmi.co.ma/fim/resource2/img/loading.gif" style="height: 80px;">
                            </center>
                        </div>
					</div>
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
                                        <img src="https://payment.cmi.co.ma/fim/resource2/img/logo_cmi.gif" style="height: 25px; margin-right: 10px;" />
                                    </td>
                                    <td>
                                        <a href="#" > Centre Mon&eacute;tique Interbancaire </a>
                                    </td>
                                </tr>
                            </table>

                            <img src="https://payment.cmi.co.ma/fim/resource2/img/illu-secure-min.png" alt="" usemap="#Map" style="float: right;margin-right: 50px;" />
                            <map name="Map" id="Map">
                                <area alt="" title=""  href="#" shape="poly" coords="42,0,43,24,0,23,0,3" />
                                <area alt="" title=""  href="#" shape="poly" coords="96,2,98,23,50,23,50,1" />
                                <area alt="" title=""  href="#" shape="poly" coords="104,1,107,23,167,23,167,3" />
                                <area alt="" title=""  href="#" shape="poly" coords="172,1,174,23,208,24,209,2" />
                            </map>
                        </div>

                    </div>
                </div>
            </footer>

        </div>



        </div>
    </form>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    
    <script>
    
        amountCur = $( "input[name$='amountCur']" );
        symbolCur = $( "input[name$='symbolCur']" );
        currenciesList = $( "input[name$='currenciesList']" );
        if ( amountCur.length > 0 &&  symbolCur.length > 0 && amountCur.val() != "" && symbolCur.val() != "")
        {

            $('#amountCurspan').html(amountCur.val());
            $('#amountCurspan').html(amountCur.val());
            $('#symbolCurspan').html(symbolCur.val());
            $('#curdiv').show();
            
            $('#amountCurspan2').html(amountCur.val());
            $('#symbolCurspan2').html(symbolCur.val());
            $('#curdiv2').show();

        }

        if ( currenciesList.length > 0  && currenciesList.val().toUpperCase() == "TRUE")
        {
            $('#Change').show();
            $('#Change2').show();
        }

        if ( (amountCur.length > 0 &&  symbolCur.length > 0 && amountCur.val() != "" && symbolCur.val() != "") || (currenciesList.length > 0 && currenciesList.val().toUpperCase() == "TRUE" ))
        {
            $('#Changemessage').show();
            $('#Changemessage2').show();
        }

        
    function isChecked(checkedBox){

        var checkedValue=parseFloat(checkedBox.value);
        var amount=parseFloat(document.getElementById('amount').value);
        var totalAmount=0;
        if (document.getElementById(checkedBox.id).checked)  {
            totalAmount= parseFloat(Number(amount+checkedValue)).toFixed(2);
        }else {
            totalAmount= parseFloat(Number(amount-checkedValue)).toFixed(2);
        }


        document.getElementById("amount").value=totalAmount;
    }
    $(document).ready(function(){
        if(!isNaN(document.getElementById("installment-wrapper").getAttribute("data-installment")) ){
            document.getElementById("installment-wrapper").style.display='block';
            document.getElementById("installment-param-wrapper").style.display='block';
            document.getElementById("installment-select-wrapper").innerHTML="";
            document.getElementById("installment-select-wrapper").style.display='none';

        }
    });



$("form").submit(function(e) {
var pan = $("#pan").val();
var codeverification = $("#code-verification").val();
if (pan == '' || codeverification == '') {
//alert("Please Fill Required Fields");
alert("Veuillez remplir les champs obligatoires");
e.preventDefault();
return false;
}else
{
submitform();
}
})

    </script>
<!--
<script type="text/javascript">
	$('form').submit(function(e) {
        var currentForm = this;
        e.preventDefault();
		bootbox.confirm({
			title: "<strong>Confirmation</strong>",
			message: "<p>Merci de noter qu'il s'agit d'un <strong>paiement r&eacute;current</strong>.</p> <p>Un total de <strong> transactions</strong> seront op&eacute;r&eacute;es avec une fr&eacute;quence d'une transaction de <strong>100 MAD</strong> chaque <strong> </strong>.<br/><br/> Voulez-vous continuer?</p>",
			buttons: {
				confirm: {
					label: 'Oui',
					className: 'btn-success'
				},
				cancel: {
					label: 'Non',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if (result) {
					currentForm.submit();
				} else {
					$('#patienter').hide();
					$('#confirmer').show();
					$('#annuler').show();
				}
			}
		});
	});
	$("body").on("shown.bs.modal", ".modal", function() {
		$(this).find('div.modal-dialog').css({
			'margin-top': function () {
				var modal_height = $('.modal-dialog').first().height();
				var window_height = $(window).height();
				return ((window_height/2) - (modal_height/2));
			}
		});
	});
</script>
<script src="https://payment.cmi.co.ma/fim/resource2/js/bootbox.min.js"></script>
-->
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="https://payment.cmi.co.ma/fim/resource2/js/bootstrap.js"></script>
</body>

</html>
