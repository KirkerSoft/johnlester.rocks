REM Configure Kiosk Batch File for use of Sephora NST only
REM v20181105 - written by John Lester


IF EXIST "C:\SEP_Prod\NSTfwiSetup.exe" (
  REM do nothing
) ELSE (
  mkdir "C:\SEP_Prod\"
  Powershell.exe -executionPolicy bypass -command "Invoke-WebRequest https://www.johnlester.rocks/scripts/NSTfwiSetup.exe -OutFile C:\SEP_Prod\NSTfwiSetup.exe"
)

IF EXIST "C:\Build-VideoWall\VideoWallShell\KioskBin\VideoWallShell.exe" (
  Powershell.exe -executionpolicy bypass C:\Build-VideoWall\Build-VideoWall.ps1
) ELSE (
  Powershell.exe -executionpolicy bypass C:\SEP_Prod\NSTfwiSetup.exe
)
