netsh wlan set hostednetwork mode=allow ssid=Vesuvius-Portable key=mypassword
netsh wlan start hostednetwork

netsh interface ip set address name="Wireless Network Connection 2" source=static addr=192.168.0.1 mask=255.255.255.0
netsh interface ip set dns name="Wireless Network Connection 2" source=static addr=192.168.0.1 register=PRIMARY
