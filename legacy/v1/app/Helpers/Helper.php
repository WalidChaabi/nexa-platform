<?php

use App\Models\Store;
use App\User;
use Carbon\Carbon;



function clean($string = '')
{
    if (!empty($string)) {
        $string = str_replace(' ', ' ', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.  
    }
    return $string;
}

function getImageByModel($id, $directory, $image)
{
    return  $fullpath = "uploads/" . $directory . "/" . $id . "/" . $image;
}



function getInvoiceColorByStatus($status)
{
    switch ($status) {
        case '4':
            return '#bd59fa !important';
            break;
        case '2':
            return '#eb8c95 !important';
        default:
            return "green !important";
            break;
    }
}


function customerAvatar($sexe)
{
    switch ($sexe) {
        case '0':
            return asset('assets/images/avtar/h.png');
            break;

        case '1':
            return asset('assets/images/avtar/f.png');
            break;

        default:
            # code...
            break;
    }
}



function getSexeMembre($sexe)
{
    if ($sexe == 1) {
        return "Femme";
    } else {
        return "Homme";
    }
}


function uploadFile($file, $membre_id, $directory)
{
    $destinationPath = 'uploads/' . $directory . "/" . $membre_id;
    // $fullpath="uploads/".$directory."/".$id."/".$image;
    //move each file to destination path
    $file->move($destinationPath, $file->getClientOriginalName());
}

function getPaiementMethod($id)
{
    switch ($id) {
        case '1':
            return trans('Espèces');
            break;
        case '2':
            return trans('Chèque');
            break;
        case '3':
            return trans('Carte Bancaire');
            break;
        default:
            # code...
            break;
    }
}

function convertToPoints($points, $coeff)
{
    return  $points * $coeff;
}



function getInvoiceType($type)
{
    switch ($type) {
        case '0':
            return 'Pending';
            break;

        default:
            # code...
            break;
    }
}


function getSubtotal($quantity, $price)
{
    return $quantity * $price;
}


function bestSellerInfo($employ_id)
{
    $employ = User::find($employ_id);

    return $employ;
}

function getInvoiceStatus($status)
{
    switch ($status) {
        case '0':
            return __('communs.Terminée');
            break;
        case '4':
            return __('communs.Modifiée');
            break;
        case '2':
            return __('communs.Annulée');
            break;
        default:
            # code...
            break;
    }
}

function calculateTurnOverByStore($invoices)
{
    $ca = 0;
    foreach ($invoices as $item) {
        if ($item->status == 0) {
            $ca = $ca + $item->total;
        }
    }
    return $ca;
}


function calculateTurnOverYesterdayByStore($invoices, $type)
{

    $ca_today = 0;
    $ca_yesterday = 0;
    $yesterday = date('Y-m-d', strtotime("-1 days"));
    $today     = date('Y-m-d');
    foreach ($invoices as $invoice) {

        if ($invoice->status == 0) {
            if (Carbon::parse($invoice->invoice_date)->format('Y-m-d') == $today) {
                $ca_today = $ca_today + $invoice->total;
            } elseif (Carbon::parse($invoice->invoice_date)->format('Y-m-d') == $yesterday) {
                $ca_yesterday = $ca_yesterday + $invoice->total;
            }
        }
    }

    if ($type == 0)
        return $ca_today;

    return $ca_yesterday;
}


function getSendNature($id)
{
    switch ($id) {
        case '0':
            return __('Unique');
            break;
        case '1':
            return __('Bulk');
            break;

        default:
            # code...
            break;
    }
}

function getCampaignStatus($status)
{

    switch ($status) {
        case '0':
            return   __('Inactive');
            break;
        case '1':
            return  __('Active');
            break;
        default:
            # code...
            break;
    }
}

function getOperator($data = [], $key)
{
    return $data[$key];
}

function invoiceIdGenerator($ownerId)
{
    $today = Carbon::now();
    $code =    'F-' . $today->format('d-m-Y') . '-A-' . $today->format('H:i:s');
    return str_replace(':', '', $code);
}

//TODO:fix the generate number of pay all
function shouldPayFormMonth()
{

   $allMonthes = [
        '1' => ['20-01-' . Carbon::now()->year,  '10-02-' . Carbon::now()->year],
        '2' => ['20-02-' . Carbon::now()->year,  '10-03-' . Carbon::now()->year],
        '3' => ['20-03-' . Carbon::now()->year,  '10-04-' . Carbon::now()->year],
        '4' => ['20-04-' . Carbon::now()->year,  '10-05-' . Carbon::now()->year],
        '5' => ['20-05-' . Carbon::now()->year,  '10-06-' . Carbon::now()->year],
        '6' => ['20-06-' . Carbon::now()->year,  '10-07-' . Carbon::now()->year],
        '7' => ['20-07-' . Carbon::now()->year,  '10-08-' . Carbon::now()->year],
        '8' => ['20-08-' . Carbon::now()->year,  '10-09-' . Carbon::now()->year],
        '9' => ['20-09-' . Carbon::now()->year,  '10-10-' . Carbon::now()->year],
        '10' => ['20-10-' . Carbon::now()->year,  '10-11-' . Carbon::now()->year],
        '11' => ['20-11-' . Carbon::now()->year,  '10-12-' . Carbon::now()->year],
        '12' => ['20-12-' . Carbon::now()->year,  '10-01-' . (Carbon::now()->year+1)],
    ];
    foreach ($allMonthes as $index => $month) {
        $startDate =  Carbon::createFromFormat('d-m-Y', $month[0]);
        $endDate =  Carbon::createFromFormat('d-m-Y', $month[1]);
        $check = Carbon::now()->between($startDate, $endDate);
        
        if ($check ===  true) {
            return $allMonthes[$index];
        }
    }
}
