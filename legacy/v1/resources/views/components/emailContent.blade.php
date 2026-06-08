@extends('templates.mail.email')
@section('emial_content')
    <!-- CONTENT -->
    <div>
        <br><br>
        <div>
            <p
                style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;font-weight: 600;">
                Cher {{ $detail->name }},</p>
​
            <table>
                <tr>
                    <td style="color:#434343; font-size:13px; line-height:18px;">
                        <p style="color:#434343; margin:0px 0 0;">
                            {{__('Félicitations')}}, <br>
                            
                            
                           Name: {{ $detail->name}} <br><br>
​
​
​
                            Nom decommerce :{{$detail->commerce}}<br><br>
​
​
​
                            Phone : {{$detail->phone}} <br><br>
 
​
​
                            Ville: {{$detail->ville}}<br><br>
​
                        </p>
​
                        <p style="color:#434343; margin:0px 0 0;">
                            Cordialement, L’équipe SHERIN.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
​
    </div>
    <!-- CONTENT -->
@endsection