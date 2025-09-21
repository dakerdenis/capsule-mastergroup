# Переходим в папку views
Set-Location "$PSScriptRoot/../mastergroup/resources/views"

# Список файлов
$files = @(
  'layouts\auth.blade.php',
  'layouts\app.blade.php',
  'layouts\admin.blade.php',
  'partials\alerts.blade.php',
  'partials\errors.blade.php',
  'auth\login.blade.php',
  'auth\register.blade.php',
  'auth\register_user.blade.php',
  'auth\register_company.blade.php',
  'auth\passwords\forgot.blade.php',
  'auth\passwords\reset.blade.php',
  'account\dashboard.blade.php',
  'catalog\index.blade.php',
  'cart\index.blade.php',
  'orders\index.blade.php',
  'admin\auth\login.blade.php',
  'admin\dashboard.blade.php'
)

# Создание директорий и файлов
foreach ($file in $files) {
    $dir = Split-Path $file
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
    if (-not (Test-Path $file)) {
        New-Item -ItemType File -Path $file -Force | Out-Null
        Write-Host "Создан файл: $file"
    } else {
        Write-Host "Уже существует: $file"
    }
}
