@rem @author: Eka Syahwan
@rem @Date: 2017-09-14 06:18:06
@rem @last Modified by: Eka Syahwan
@rem Modified time: 2018-01-12 04:25:37
@echo off
set PATH=%PATH%;c:\xampp\php
title Sendinbox Prov2 (2018) - Bmarket.or.id
:runsendinbox
php sendinbox.php
pause
cls
goto runsendinbox