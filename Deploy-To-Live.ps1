# PPG4 Deployment Script for Ventrap FTP Server
# This script deploys changes from local development to live server via FTP

param(
    [Parameter(Mandatory=$true)]
    [string]$FtpHost,
    
    [Parameter(Mandatory=$true)]
    [string]$FtpUser,
    
    [Parameter(Mandatory=$true)]
    [string]$FtpPass,
    
    [string]$FtpPort = "21",
    
    [string]$RemotePath = "/public_html",
    
    [switch]$DryRun = $false
)

# Configuration
$LocalPath = "C:\xampp\htdocs\ppg4"
$DeployIgnoreFile = "$LocalPath\.deployignore"
$LogFile = "$LocalPath\deployment.log"

# Color output
function Write-Info {
    param([string]$Message)
    Write-Host $Message -ForegroundColor Cyan
    Add-Content -Path $LogFile -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] INFO: $Message"
}

function Write-Success {
    param([string]$Message)
    Write-Host $Message -ForegroundColor Green
    Add-Content -Path $LogFile -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] SUCCESS: $Message"
}

function Write-Error-Log {
    param([string]$Message)
    Write-Host $Message -ForegroundColor Red
    Add-Content -Path $LogFile -Value "[$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')] ERROR: $Message"
}

# Initialize log
Write-Info "=========================================="
Write-Info "Starting PPG4 Deployment"
Write-Info "From: $LocalPath"
Write-Info "To: ftp://$FtpHost$RemotePath"
Write-Info "Dry Run: $DryRun"
Write-Info "=========================================="

# Parse .deployignore patterns
function Should-Ignore {
    param([string]$FilePath)
    
    if (-not (Test-Path $DeployIgnoreFile)) {
        return $false
    }
    
    $RelativePath = [System.IO.Path]::GetRelativePath($LocalPath, $FilePath)
    
    $ignorePatterns = Get-Content $DeployIgnoreFile | 
        Where-Object { $_ -and -not $_.StartsWith("#") } |
        ForEach-Object { $_.Trim() }
    
    foreach ($pattern in $ignorePatterns) {
        if ($RelativePath -like $pattern -or $RelativePath -match [regex]::Escape($pattern).Replace("\*", ".*")) {
            return $true
        }
    }
    
    return $false
}

# Get all files that should be deployed
$FilesToDeploy = Get-ChildItem -Path $LocalPath -Recurse -File |
    Where-Object { -not (Should-Ignore $_.FullName) } |
    Select-Object -ExpandProperty FullName

Write-Info "Found $($FilesToDeploy.Count) files to deploy"

# Create FTP session
try {
    $FtpCredential = New-Object System.Net.NetworkCredential($FtpUser, $FtpPass)
    $FtpUri = "ftp://$FtpHost`:$FtpPort$RemotePath/"
    
    Write-Info "Connecting to FTP server..."
    
    if ($DryRun) {
        Write-Success "DRY RUN - Would upload $($FilesToDeploy.Count) files to $FtpUri"
        Write-Info "Files to deploy:"
        $FilesToDeploy | ForEach-Object {
            $RelativePath = [System.IO.Path]::GetRelativePath($LocalPath, $_)
            Write-Host "  → $RelativePath" -ForegroundColor Yellow
        }
        Write-Success "Dry run completed successfully"
        exit 0
    }
    
    # Upload each file
    $UploadCount = 0
    foreach ($File in $FilesToDeploy) {
        try {
            $RelativePath = [System.IO.Path]::GetRelativePath($LocalPath, $File)
            $RemoteFile = $FtpUri + $RelativePath.Replace("\", "/")
            
            # Create remote directory if needed
            $RemoteDir = Split-Path -Parent $RemoteFile
            
            $FtpRequest = [System.Net.FtpWebRequest]::Create($RemoteFile)
            $FtpRequest.Credentials = $FtpCredential
            $FtpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
            $FtpRequest.UseBinary = $true
            $FtpRequest.KeepAlive = $false
            
            $FileStream = [System.IO.File]::OpenRead($File)
            $FtpStream = $FtpRequest.GetRequestStream()
            $FileStream.CopyTo($FtpStream)
            $FtpStream.Close()
            $FileStream.Close()
            
            $Response = $FtpRequest.GetResponse()
            $Response.Close()
            
            $UploadCount++
            Write-Info "Uploaded: $RelativePath"
            
        } catch {
            Write-Error-Log "Failed to upload $($File): $($_.Exception.Message)"
        }
    }
    
    Write-Success "=========================================="
    Write-Success "Deployment Complete!"
    Write-Success "Uploaded $UploadCount / $($FilesToDeploy.Count) files"
    Write-Success "Check deployment.log for details"
    Write-Success "=========================================="
    
} catch {
    Write-Error-Log "Deployment failed: $($_.Exception.Message)"
    exit 1
}
