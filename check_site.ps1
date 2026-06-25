try {
    $response = Invoke-WebRequest -Uri "http://SEOFAST.test" -UseBasicParsing -TimeoutSec 10
    Write-Host "HTTP Status: $($response.StatusCode)"
    Write-Host "SEOFAST.test is working!"
} catch {
    Write-Host "Error: $_"
}