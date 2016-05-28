# Start VLC
cvlc -A alsa -f -I http --http-host 0.0.0.0 --http-port 8080 --http-password 'vlc' --no-osd -V fb --no-fb-tty --fbdev /dev/fb0 --swscale-mode 4 /home/pi/bg.jpg
