server {
	listen 80;
	server_name www.tipost.co.il tipost.co.il;
	rewrite ^ https://www.tipost.co.il$request_uri redirect;
}

server {
    listen 443 ssl;
    server_name www.tipost.co.il tipost.co.il;

    # LOG
    access_log /var/log/nginx/tipost.co.il_access;
    error_log /var/log/nginx/tipost.co.il_error;

    if ($host = 'tipost.co.il'){
        rewrite ^ https://www.tipost.co.il$request_uri redirect;
    }

    # SSL
    ssl_certificate /etc/letsencrypt/live/tipost.co.il/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/tipost.co.il/privkey.pem; # managed by Certbot

    ssl on;
    ssl_session_cache  builtin:1000  shared:SSL:10m;
    ssl_protocols  TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers HIGH:!aNULL:!eNULL:!EXPORT:!CAMELLIA:!DES:!MD5:!PSK:!RC4;
    ssl_prefer_server_ciphers on;

    # Reverse Proxy
    location ~* ^/(.*)$ {
      proxy_pass http://31.22.4.233/sites.php?id=$1;

      proxy_set_header        Host www.websmaking.com;
      proxy_set_header        X-Real-IP $remote_addr;
      proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header        X-Original-URI $request_uri;
      proxy_set_header        X-Forwarded-Proto https; #$scheme;
      proxy_set_header        domain $host;
      proxy_read_timeout  90;
    }
    location / {
      proxy_pass http://31.22.4.233/sites.php;

      proxy_set_header        Host www.websmaking.com;
      proxy_set_header        X-Real-IP $remote_addr;
      proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header        X-Original-URI $request_uri;
      proxy_set_header        X-Forwarded-Proto $scheme;
      proxy_set_header        domain $host;
      proxy_read_timeout  90;
    }
}
