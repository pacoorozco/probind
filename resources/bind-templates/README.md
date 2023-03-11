# ProBIND3 templates
This application uses templates to render the BIND configuration and zone files. 

Templates are written using [Blade](https://laravel.com/docs) templating engine.

## Servers
The default templates are located at `/resources/bind-templates/defaults`. 

Primary servers use `primary-server.blade.php` while secondary servers use `secondary-server.blade.php`.

There are several objects that you can use inside these templates, these objects will be populated when `probind` will render the configuration file:

- `$server`: current Server object (see `/app/Models/Server.php`).
- `$zones`: collection of all zones managed by this server (see `/app/Models/Zone.php`).

### How to customize configuration rendering
The BIND configuration file for a specific server could be customized by creating a specific template.

Specific templates are located in the `/resources/bind-templates/servers` folder. The name of the template should be the same of the server name, **replacing dots '.' per underscores '_'**, and suffixing it with `.blade.php`.

Let's imagine that you want to customize the configuration of your server `my-server.com`. First, you should create a template file at `/resources/bind-templates/servers/my-server_com.blade.php`. The best way to do it is to make a copy of the default one:

```bash
$ cd resources/bind-templates
$ cp defaults/primary-server.blade.php servers/my-server_com.blade.php
```

And then you can modify the template as you need.

## Zones
The default template is located at `/resources/bind-templates/defaults/zone.blade.php`.

There are several objects that you can use inside these templates, these objects will be populated when `probind` will render the zone file:

- `$zone`: current Zone object (see `/app/Models/Zone.php`).
- `$servers`: collection of nameservers (see `/app/Models/Server.php`).
- `$records`: collection of all resource records inside the zone (see `/app/Models/ResourceRecord.php`).

### How to customize zone rendering
The BIND zone file for a specific zone could be customized by creating a specific template. 

Specific templates are located in the `/resources/bind-templates/zones` folder. The name of the template should be the same of the domain name, **replacing dots '.' per underscores '_'** and suffixing it with `.blade.php`.

Let's imagine that you want to customize how the Zone `my-domain.com` is rendered. First, I should create a template file at `/resources/bind-templates/zones/my-domain_com.blade.php`. The best way to do it is to make a copy of the default one:

```bash
$ cd resources/bind-templates
$ cp defaults/zone.blade.php zones/my-domain_com.blade.php
```

And then you can modify the template as you need.
