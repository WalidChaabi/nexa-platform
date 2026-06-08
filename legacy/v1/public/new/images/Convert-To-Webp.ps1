param(
    [string]$Path='.',
    [switch]$Recurse,
    [int]$Quality=90,
    [int]$Depth=5,
    [string]$File
)

$Extensions=@(".jpg",".jpeg",".png")
$Expression=".*\.(jpe?|pn)g$"

function Format-FileSize {
    param ([int64]$size)
    if     ($size -gt 1TB) {[string]::Format("{0:0.00} TB", $size / 1TB)}
    elseIf ($size -gt 1GB) {[string]::Format("{0:0.00} GB", $size / 1GB)}
    elseIf ($size -gt 1MB) {[string]::Format("{0:0.00} MB", $size / 1MB)}
    elseIf ($size -gt 1KB) {[string]::Format("{0:0.00} kB", $size / 1KB)}
    elseIf ($size -gt 0)   {[string]::Format("{0:0.00} B", $size)}
    else                   {""}
}

function Convert-To-Webp {
    param ($file)

    $filename = $file.FullName;
    $filename_without_extension = [System.IO.Path]::GetFileNameWithoutExtension($file.Name);
    $parent = $file.Directory;
    $destination_filename="$parent\$filename_without_extension.webp"
    try {
        $converted = File-Exists($destination_filename);
        if (-Not $converted) {
            Invoke-Expression -Command "cwebp.exe `"$filename`" -q $Quality -alpha_q 100 -m 6 -o `"$destination_filename`" 2>`$null";
            $destination_file=Get-Item $destination_filename
            $saving=Format-FileSize($file.Length - $destination_file.Length);
            Write-Host "$filename_without_extension converted. You saved $saving";
        } else {
            Write-Host "$filename_without_extension already converted"
        }
    } catch {}
}

function File-Exists {
    param($filename)
    return Test-Path $filename
}

function Check-Extension {
    param($filename)
    return $filename -match $Expression
}

if (-Not $File) {
    if ($Recurse.IsPresent) {
        $files = Get-ChildItem -Path "$Path\*" -Include "*.png","*.jpg","*.jpeg" -File -Depth $Depth -Recurse;
    } else {
        $files = Get-ChildItem -Path "$Path\*" -Include "*.png","*.jpg","*.jpeg" -File;
    }

    #foreach ($it in $files) {
    #    Convert-To-Webp $it
    #}

    $files | ForEach-Object -Process { Convert-To-Webp($_) }
} else {
    if (Check-Extension $File) {
        if (File-Exists $File) {
            Convert-To-Webp(Get-Item $File);
        } elseif (File-Exists "`"$Path\$File`"") {
            Convert-To-Webp(Get-Item "`"$Path\$File`"");
        } else {
            $Host.UI.WriteErrorLine("File doesn't exists");
        }
    } else {
        $Host.UI.WriteErrorLine("File must be an image!");
    }
}
