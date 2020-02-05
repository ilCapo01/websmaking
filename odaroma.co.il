#server {
#        listen 80;
#        server_name www.odaroma.co.il odaroma.co.il;
#        rewrite ^ https://www.odaroma.co.il$request_uri redirect;
#}

server {
    listen 80;
#    listen 443 ssl;
    server_name www.odaroma.co.il odaroma.co.il;

    # LOG
    access_log /var/log/nginx/odaroma.co.il_access;
    error_log /var/log/nginx/odaroma.co.il_error;

    #if ($scheme ~ 'https'){
    #    rewrite ^ https://www.odaroma.co.il$request_uri redirect;
    #}
    if ($host = 'odaroma.co.il'){
        rewrite ^ https://www.odaroma.co.il$request_uri redirect;
    }

    # SSL 
    ssl on;
    ssl_session_cache  builtin:1000  shared:SSL:10m;
    ssl_protocols  TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers HIGH:!aNULL:!eNULL:!EXPORT:!CAMELLIA:!DES:!MD5:!PSK:!RC4;
    ssl_prefer_server_ciphers on;
    ssl_certificate /etc/letsencrypt/live/odaroma.co.il/fullchain.pem; # manage$
    ssl_certificate_key /etc/letsencrypt/live/odaroma.co.il/privkey.pem; # mana$

    # Redirect to SSL, 4now.
    if ($scheme ~ "https") {
        rewrite ^ https://www.odaroma.co.il$request_uri redirect;
    }

    # Reverse Proxy
#    location ~* ^/(.*)/(.*)$ {
    location ~* ^/(.*)$ {
#      proxy_pass http://31.22.4.233/sites.php?do=$1&id=$2;
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
      proxy_set_header        X-Forwarded-Proto https; #$scheme;
      proxy_set_header        domain $host;
      proxy_read_timeout  90;
    }
}

