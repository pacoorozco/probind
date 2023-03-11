---
title: "Introduction"
description: "ProBIND3's features, license and motivation."
weight: 50
date: "2022-03-01"
lastmod: "2022-03-01"
---

**ProBIND3** is a web application designed for managing the DNS zones for one or more servers running the [ISC BIND DNS server](https://www.isc.org/downloads/bind/) software. It works best for companies that need to manage a medium-sized pool of domains across a set of servers.

The application has been written using [Laravel framework](https://laravel.com). It stores its data in a database (see [Laravel Database Backend](https://laravel.com/docs)) and generates configuration files for BIND on-demand.

### What ProBIND3 Is

**ProBIND3** is meant to be a time-saving tool for busy administrators, aiding in managing the configuration of DNS zones across multiple servers. It is intended for use by those already familiar with the components of a DNS zone file and who understand DNS concepts and methods.

This software acts as a configuration repository to help keep zones well-maintained and has several helping tools to ensure that common DNS issues are minimized.

### What ProBIND3 Is Not

* Although ProBIND3 uses a database to store zone data, **it is not a replacement backend for ISC BIND**. ProBIND3 merely creates the proper zone files for use with the default configuration method of BIND. If you are looking for a live SQL backend for ISC BIND, this is not one.

* ProBIND3 is not a tool for those unfamiliar with DNS concepts. It assumes you know the differences between a CNAME and an A record. It also assumes you know about SOA records, what a lame server is, and what glue is.

* ProBIND3 is not the ultimate solution to DNS management. It fits the needs of those who develop it, and it is hoped that others will also find it useful.

