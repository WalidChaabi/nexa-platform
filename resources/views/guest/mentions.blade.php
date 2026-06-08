@extends('templates.index')
@section('content')

    <!--about us section start-->
    <section id="about" class="about-us ptb-100 gray-light-bg">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                {{-- <div class="col-md-5">
                    <div class="about-content-right">
                        <div class="single-feature mb-4">
                            <div class="single-feature mt-4 mb-3">
                                <p class="info">Raison sociale: PALM D’OR MULTI-SERVICES SARL </p>
                                <p class="info">– Téléphone: +212 (06) 63 42 37 33  </p>
                                <p class="info">– Email: contact@sherin.ma</p>
                                <p class="info">– Site internet: www.sherin.ma </p>
                                <p class="info">– Adresse du siège: Hay Inara Bloc 19 N° 762 MARRAKECH 40000, Maroc. </p>
                            </div>
                            <div class="single-feature mb-3">
                                <p class="info">Directeur de la publication: M. LAHBIRI Othmane – lahbiri.othmane@sherin.ma</p>
                                <p class="info">Web Design & Développement: PALM D’OR MULTI-SERVICES SARL– http://www.sherin.ma </p>
                                <p class="info">L’hébergement du site est assuré par la société ADK MEDIA</p>
                            </div>

                                <p class="info">RC : 4381 - IF : 05702080 - Patente : 42106613 </p>
                                <p class="info">CNSS : 7233258 - ICE : 000100071000018 </p>
                                <p class="info">Déclaration CNDP : A-GC-130/2017 en date du 21/07/2017</p>
                                <p class="info">Label e-thiq@ délivré par la CGEM le 24/05/2016 </p>
                                Prestataire agréé par l'ANRT sous numéro ANRT/SVA/014577 </p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <div class="about-content-left section-heading">
                        <span class="text-uppercase color-secondary sub-title">Mentions légales</span>
                        <h2>INFORMATIONS GÉNÉRALES</h2>

                        <div class="single-feature mb-4 mt-4">
                            {{-- <div class="icon-box-wrap d-flex align-items-center mb-2"> --}}
                                <p class="mb-0" style="text-align: justify">Raison sociale: PALM D’OR MULTI-SERVICES SARL</p>
                                <p class="mb-0" style="text-align: justify">– Téléphone: +212 (06) 63 42 37 33</p>
                                <p class="mb-0" style="text-align: justify">– Email: contact@sherin.ma</p>
                                <p class="mb-0" style="text-align: justify">– Site internet: www.sherin.ma</p>
                                <p class="mb-0" style="text-align: justify">– Adresse du siège: Hay Inara Bloc 19 N° 762 MARRAKECH 40000, Maroc.</p>
                            {{-- </div> --}}
                        </div>
                        <h4 style="color: #9629e6; margin:0">TARIFS</h4>

                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-2">
                                <p class="mb-0" style="text-align: justify">Les prix mentionnés sur le site www.sherin.ma sont des prix publics conseillés. Le prix applicable correspond au 
                                    prix affiché sur le site au jour de la commande. Les prix sont exprimés en MAD (HT) 
                                    (taux de TVA est de 20%). Règlement Règlement par carte bancaire, ce service est assuré par le centre monétaire intérbancaire.
                                </p>
                            </div>
                        </div>
                        <h4 style="color: #9629e6; margin:0">PROPRIÉTÉ INTELLECTUELLE ET CONTREFAÇONS</h4>

                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-2">
                                <p class="mb-0" style="text-align: justify">PALM D’OR MULTI-SERVICES SARL est propriétaire des droits de propriété intellectuelle 
                                    ou détient les droits d’usage sur tous les éléments accessibles sur le site, notamment les textes,
                                    images, graphismes, logo, icônes, sons, logiciels. Toute reproduction, représentation, modification, 
                                    publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, 
                                    est interdite, sauf autorisation écrite préalable de : PALM D’OR MULTI-SERVICES SARL.
                                </p>
                            </div>
                        </div>
                        <h4 style="color: #9629e6; margin:0">GESTION DES DONNÉES PERSONNELLES</h4>

                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Les utilisateurs de ce site sont tenus de respecter les dispositions de la loi 09-08 du 18 février 2009 relative 
                                    à la Protection des Personnes Physiques à l'égard du traitement des données à caractère personnel. Ils doivent s’abstenir de 
                                    toute collecte, captation, déformation ou utilisation des informations auxquelles ils accèdent et, d’une manière générale, de 
                                    tout acte susceptible de porter atteinte à la vie privée des personnes.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Les coordonnées des utilisateurs ne sont utilisées que pour mieux les informer et, éventuellement, ne leur adresser 
                                    que les informations qu’ils souhaitent recevoir.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Conformément à la Loi Informatique Loi 09-08 relative à la protection des personnes physiques à l’égard du traitement des 
                                    données à caractère personnel, les personnes concernées bénéficient d’un droit d’accès, de rectification ou d’opposition sur les données
                                     nominatives collectées les concernant. Ces droits peuvent être exercés en nous contactant à l’adresse électronique : support@sherin.m
                                     a ou à l’adresse suivante :
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">PALM D’OR MULTI-SERVICES SARL</p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Hay Inara Bloc 19 N° 762 MARRAKECH 40000, Maroc.</p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify"> ailleurs, l’utilisateur s’engage à fournir des informations exactes quant à ses coordonnées personnelles, étant 
                                    précisé que ces informations sont protégées par la législation en vigueur et notamment la loi n° 09-08 relative à la 
                                    protection des personnes physiques à l’égard du traitement des données à caractère personnel.
                                </p>
                            </div>
                        </div>
                        
                        <h4 style="color: #9629e6; margin:0">CONDITIONS GÉNÉRALES D’UTILISATION DU SITE ET DES SERVICES PROPOSÉS</h4>
                        
                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">L’utilisation du site Sherin.ma implique l’acceptation pleine et entière des conditions générales d’utilisation ci-après décrites.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Ces conditions d’utilisation sont susceptibles d’être modifiées ou complétées à 
                                    tout moment, les utilisateurs du site Sherin.ma sont donc invités à les consulter de manière régulière.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Ce site est normalement accessible à tout moment aux utilisateurs. Une interruption pour 
                                    raison de maintenance technique peut être toutefois décidée par PALM D’OR MULTI-SERVICES SARL, qui s’efforcera alors de communiquer
                                     préalablement aux utilisateurs les dates et heures de l’intervention.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Le site Sherin.ma est mis à jour régulièrement par PALM D’OR MULTI-SERVICES SARL.</p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">De la même façon, les mentions légales peuvent être modifiées à tout moment : elles s’imposent néanmoins 
                                    à l’utilisateur qui est invité à s’y référer le plus souvent possible afin d’en prendre connaissance.</p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify"> ailleurs, l’utilisateur s’engage à fournir des informations exactes quant à ses coordonnées personnelles, étant 
                                    précisé que ces informations sont protégées par la législation en vigueur et notamment la loi n° 09-08 relative à la 
                                    protection des personnes physiques à l’égard du traitement des données à caractère personnel.
                                </p>
                            </div>
                        </div>

                        <h4 style="color: #9629e6; margin:0">LIMITATIONS CONTRACTUELLES SUR LES DONNÉES TECHNIQUES</h4>
                        
                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Le site utilise la technologie JavaScript et Laravel PhP.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Le site Internet ne pourra être tenu responsable de dommages 
                                    matériels liés à l’utilisation du site. De plus, l’utilisateur du site s’engage à accéder au site en 
                                    utilisant un matériel récent, ne contenant pas de virus et avec un navigateur de dernière génération mis-à-jour
                                </p>
                            </div>
                        </div>
                        
                        <h4 style="color: #9629e6; margin:0">LIMITATIONS DE RESPONSABILITÉ</h4>

                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">PALM D’OR MULTI-SERVICES SARL ne pourra être tenue responsable
                                     des dommages directs et indirects causés au matériel de l’utilisateur, lors de l’accès au site Sherin.ma,
                                      et résultant soit de l’utilisation d’un matériel ne répondant pas aux spécifications indiquées au point 2, 
                                      soit de l’apparition d’un bug ou d’une incompatibilité.</p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">PALM D’OR MULTI-SERVICES SARL ne pourra également être tenue responsable 
                                    des dommages indirects (tels par exemple qu’une perte de marché ou perte d’une chance) consécutifs à l’utilisation du site Sherin.ma.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">PALM D’OR MULTI-SERVICES SARL se réserve le droit de supprimer, sans mise en demeure préalable, 
                                    tout contenu déposé dans cet espace qui contreviendrait à la législation applicable au Maroc, en particulier aux dispositions relatives 
                                    à la protection des données.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Le cas échéant, PALM D’OR MULTI-SERVICES SARL se réserve également la possibilité de mettre en 
                                    cause la responsabilité civile et/ou pénale de l’utilisateur, notamment en cas de message à caractère raciste, injurieux, diffamant, 
                                    ou pornographique, quel que soit le support utilisé (texte, photographie…).
                                </p>
                            </div>
                        </div>
                        <h4 style="color: #9629e6; margin:0">LIENS HYPERTEXTES ET COOKIES</h4>

                        <div class="single-feature mb-4 mt-4">
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Le site Sherin.ma contient un certain nombre de liens hypertextes vers d’autres sites, 
                                    mis en place avec l’autorisation de PALM D’OR MULTI-SERVICES SARL.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Cependant, PALM D’OR MULTI-SERVICES SARL n’a pas la possibilité de vérifier le contenu des 
                                    sites ainsi visités, et n’assumera en conséquence aucune responsabilité de ce fait. La navigation sur le site Sherin.ma est susceptible 
                                    de provoquer l’installation de cookie(s) sur l’ordinateur de l’utilisateur. Un cookie est un fichier de petite taille, qui ne permet 
                                    pas l’identification de l’utilisateur, mais qui enregistre des informations relatives à la navigation d’un ordinateur sur un site. 
                                    Les données ainsi obtenues visent à faciliter la navigation ultérieure sur le site, et ont également vocation à permettre diverses 
                                    mesures de fréquentation. Le refus d’installation d’un cookie peut entraîner l’impossibilité d’accéder à certains services. 
                                    L’utilisateur peut toutefois configurer son ordinateur de la manière suivante, pour refuser l’installation des cookies :
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Sous Internet Explorer : onglet outil (pictogramme en forme de rouage en haut a droite) / 
                                    options internet. Cliquez sur Confidentialité et choisissez Bloquer tous les cookies. Validez sur Ok.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Sous Firefox : en haut de la fenêtre du navigateur, cliquez sur le bouton Firefox, 
                                    puis aller dans l’onglet Options. Cliquer sur l’onglet Vie privée.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Paramétrez les Règles de conservation sur : utiliser les paramètres personnalisés pour 
                                    l’historique. Enfin décochez-la pour désactiver les cookies.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Sous Safari : Cliquez en haut à droite du navigateur sur le pictogramme de menu 
                                    (symbolisé par un rouage). Sélectionnez Paramètres. Cliquez sur Afficher les paramètres avancés. Dans la section 
                                    « Confidentialité », cliquez sur Paramètres de contenu. Dans la section « Cookies », vous pouvez bloquer les cookies.
                                </p>
                            </div>
                            <div class="icon-box-wrap d-flex align-items-center mb-3">
                                <p class="mb-0" style="text-align: justify">Sous Chrome : Cliquez en haut à droite du navigateur sur le pictogramme de menu 
                                    (symbolisé par trois lignes horizontales). Sélectionnez Paramètres. Cliquez sur Afficher les paramètres avancés. Dans 
                                    la section « Confidentialité », cliquez sur préférences. Dans l’onglet « Confidentialité », vous pouvez bloquer les cookies.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--about us section end-->
    <section class="client-section ptb-100">
        <div class="container">
            <!--clients logo start-->

            <!--clients logo end-->
        </div>
    </section>
@endsection
