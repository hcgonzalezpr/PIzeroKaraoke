# PIzeroKaraoke

The code is pretty basic, all it does it trigger VLC from the command line and a simple website to search and trigger loading the file in VLC

Requirements:
cvlc
webserver+php ( Ex: Lighttpd + PHP )

Songs should be stored in :
/home/pi/songs/

The bg image is loaded from:
/home/pi/bg.jpg

Set your RPI to auto login to bash or use the systemd service example.

If vlc lags like crazy make sure /boot/config.txt file has the following lines:

framebuffer_width=288

framebuffer_height=192
