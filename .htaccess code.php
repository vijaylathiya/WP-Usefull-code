Redirect http links to https:

# HTTPS redirect
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule (.*) https://%{HTTP_HOST}/$1 [R=301,L]
</IfModule>